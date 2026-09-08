<?php
namespace App\Controllers;

use App\Models\FacturaModel;
use App\Models\DetalleFacturaModel;
use App\Models\ClienteModel;

class FacturaController extends BaseController
{
    protected $facturaModel;
    protected $detalleModel;
    protected $clienteModel;

    // IVA vigente en Ecuador
    const IVA_PORCENTAJE = 15;

    public function __construct()
    {
        $this->facturaModel = new FacturaModel();
        $this->detalleModel = new DetalleFacturaModel();
        $this->clienteModel = new ClienteModel();
    }

    /**
     * Historial de facturas.
     */
    public function index()
    {
        $data = [
            'facturas' => $this->facturaModel->listarConCliente(),
        ];
        return view('facturas/index', $data);
    }

    /**
     * Formulario para registrar una nueva factura.
     */
    public function nueva()
    {
        $data = [
            'clientes' => $this->clienteModel->orderBy('nombres', 'ASC')->findAll(),
            'iva'      => self::IVA_PORCENTAJE,
            'hoy'      => date('Y-m-d'),
        ];
        return view('facturas/nueva', $data);
    }

    /**
     * Procesa y guarda la nueva factura junto con su detalle.
     */
    public function save()
    {
        $id_cliente    = $this->request->getPost('id_cliente');
        $fecha         = $this->request->getPost('fecha') ?: date('Y-m-d');
        $observaciones = $this->request->getPost('observaciones');

        $descripciones = $this->request->getPost('descripcion') ?? [];
        $cantidades    = $this->request->getPost('cantidad') ?? [];
        $precios       = $this->request->getPost('precio_unitario') ?? [];

        if (!$id_cliente) {
            return redirect()->to('/facturas/nueva')->withInput()
                ->with('errors', ['Debe seleccionar un cliente.']);
        }

        // Filtrar líneas válidas y calcular subtotal
        $items    = [];
        $subtotal = 0;

        foreach ($descripciones as $i => $desc) {
            $desc   = trim((string) $desc);
            $cant   = (float) ($cantidades[$i] ?? 0);
            $precio = (float) ($precios[$i] ?? 0);

            if ($desc === '' || $cant <= 0 || $precio < 0) {
                continue;
            }

            $lineaSubtotal = round($cant * $precio, 2);
            $items[] = [
                'descripcion'     => $desc,
                'cantidad'        => $cant,
                'precio_unitario' => $precio,
                'subtotal'        => $lineaSubtotal,
            ];
            $subtotal += $lineaSubtotal;
        }

        if (empty($items)) {
            return redirect()->to('/facturas/nueva')->withInput()
                ->with('errors', ['Debe agregar al menos un producto o servicio válido.']);
        }

        $iva   = round($subtotal * (self::IVA_PORCENTAJE / 100), 2);
        $total = round($subtotal + $iva, 2);

        $db = \Config\Database::connect();
        $db->transStart();

        $facturaData = [
            'numero_factura' => null,
            'id_cliente'     => $id_cliente,
            'fecha'          => $fecha,
            'subtotal'       => $subtotal,
            'iva'            => $iva,
            'total'          => $total,
            'estado'         => 'pendiente',
            'observaciones'  => $observaciones,
        ];

        if (!$this->facturaModel->insert($facturaData)) {
            $db->transRollback();
            $errores = $this->facturaModel->errors() ?: ['No se pudo registrar la factura.'];
            return redirect()->to('/facturas/nueva')->withInput()->with('errors', $errores);
        }

        $id_factura = $this->facturaModel->getInsertID();

        // Número de factura correlativo, basado en el ID generado
        $numero = 'F-' . str_pad((string) $id_factura, 6, '0', STR_PAD_LEFT);
        $this->facturaModel->update($id_factura, ['numero_factura' => $numero]);

        foreach ($items as &$item) {
            $item['id_factura'] = $id_factura;
        }
        unset($item);

        $this->detalleModel->insertBatch($items);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/facturas/nueva')->withInput()
                ->with('errors', ['Ocurrió un error al guardar la factura. Intente nuevamente.']);
        }

        return redirect()->to('/facturas')->with('success', "Factura {$numero} registrada con éxito.");
    }

    /**
     * Devuelve en JSON el detalle de una factura (usado por el modal "Ver").
     */
    public function ver($id)
    {
        $factura = $this->facturaModel
            ->select('factura.*, cliente.nombres, cliente.apellidos, cliente.cedula, cliente.telefono, cliente.direccion')
            ->join('cliente', 'cliente.id_cliente = factura.id_cliente', 'left')
            ->find($id);

        if (!$factura) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Factura no encontrada']);
        }

        $factura['items'] = $this->detalleModel->porFactura($id);

        return $this->response->setJSON($factura);
    }

    /**
     * Marca una factura como pagada.
     */
    public function pagar($id)
    {
        if (!$this->facturaModel->find($id)) {
            return redirect()->to('/facturas')->with('error', 'Factura no encontrada.');
        }

        $this->facturaModel->update($id, ['estado' => 'pagada']);
        return redirect()->to('/facturas')->with('success', 'Factura marcada como pagada.');
    }

    /**
     * Anula una factura (cambia su estado, no la borra).
     */
    public function anular($id)
    {
        if (!$this->facturaModel->find($id)) {
            return redirect()->to('/facturas')->with('error', 'Factura no encontrada.');
        }

        $this->facturaModel->update($id, ['estado' => 'anulada']);
        return redirect()->to('/facturas')->with('success', 'Factura anulada con éxito.');
    }

    /**
     * Elimina definitivamente una factura y su detalle.
     */
    public function delete($id)
    {
        $this->detalleModel->where('id_factura', $id)->delete();

        if ($this->facturaModel->delete($id)) {
            return redirect()->to('/facturas')->with('success', 'Factura eliminada con éxito.');
        }

        return redirect()->to('/facturas')->with('error', 'No se pudo eliminar la factura.');
    }

    /**
     * Genera la vista de la factura y abre el cuadro de impresión automáticamente
     */
    public function imprimir($id)
    {
        // 1. Obtener los datos completos de la factura y el cliente
        $factura = $this->facturaModel
            ->select('factura.*, cliente.nombres, cliente.apellidos, cliente.cedula, cliente.telefono, cliente.direccion')
            ->join('cliente', 'cliente.id_cliente = factura.id_cliente', 'left')
            ->find($id);

        if (!$factura) {
            return redirect()->to('/facturas')->with('error', 'Factura no encontrada.');
        }

        // 2. Obtener el detalle (los productos/servicios)
        $factura['items'] = $this->detalleModel->porFactura($id);

        // 3. Generar el HTML con formato de Factura y el script de impresión
        $html = "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <title>Factura " . esc($factura['numero_factura'] ?? ('#' . $factura['id_factura'])) . "</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 30px; color: #333; font-size: 14px; }
                .header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                .details { margin-bottom: 30px; }
                .details p { margin: 5px 0; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
                th { background-color: #f8f9fa; font-weight: bold; }
                .text-right { text-align: right; }
                .text-center { text-align: center; }
                .totals-container { width: 40%; float: right; margin-top: 20px; }
                .totals-container table { border: none; }
                .totals-container th, .totals-container td { border: none; padding: 5px 10px; }
                
                /* Configuración especial para la hoja de impresión */
                @media print {
                    @page { margin: 1.5cm; }
                    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                }
            </style>
        </head>
        <body>
            <div class='header'>
                <h2>SISTEMA DE FACTURACIÓN</h2>
                <h3>DOCUMENTO: " . esc($factura['numero_factura'] ?? ('#' . $factura['id_factura'])) . "</h3>
            </div>
            
            <div class='details'>
                <p><strong>Fecha de Emisión:</strong> " . date('d/m/Y', strtotime($factura['fecha'])) . "</p>
                <p><strong>Cliente:</strong> " . esc($factura['nombres'] . ' ' . $factura['apellidos']) . "</p>
                <p><strong>Cédula:</strong> " . esc($factura['cedula']) . " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Teléfono:</strong> " . esc($factura['telefono']) . "</p>
                <p><strong>Dirección:</strong> " . esc($factura['direccion']) . "</p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th class='text-center'>Cant.</th>
                        <th class='text-right'>P. Unitario</th>
                        <th class='text-right'>Subtotal</th>
                    </tr>
                </thead>
                <tbody>";

        // Llenar la tabla con los productos
        foreach ($factura['items'] as $item) {
            $html .= "<tr>
                        <td>" . esc($item['descripcion']) . "</td>
                        <td class='text-center'>" . esc($item['cantidad']) . "</td>
                        <td class='text-right'>$" . number_format($item['precio_unitario'], 2) . "</td>
                        <td class='text-right'>$" . number_format($item['subtotal'], 2) . "</td>
                      </tr>";
        }

        $html .= "</tbody>
            </table>

            <div class='totals-container'>
                <table>
                    <tr>
                        <td class='text-right'><strong>Subtotal:</strong></td>
                        <td class='text-right'>$" . number_format($factura['subtotal'], 2) . "</td>
                    </tr>
                    <tr>
                        <td class='text-right'><strong>IVA:</strong></td>
                        <td class='text-right'>$" . number_format($factura['iva'], 2) . "</td>
                    </tr>
                    <tr>
                        <td class='text-right'><strong>TOTAL A COBRAR:</strong></td>
                        <td class='text-right'><strong>$" . number_format($factura['total'], 2) . "</strong></td>
                    </tr>
                </table>
            </div>
            
            <div style='clear:both;'></div>
            
            <p style='margin-top: 50px; text-align: center; font-size: 12px; color: #777;'>
                " . ($factura['observaciones'] ? "<strong>Observaciones:</strong> " . esc($factura['observaciones']) . "<br><br>" : "") . "
                Gracias por su compra.
            </p>

            <script>
                // 1. Abre el cuadro de diálogo de impresión automáticamente al cargar
                window.onload = function() {
                    window.print();
                };
                
                // 2. Cierra la pestaña automáticamente después de imprimir o cancelar
                window.onafterprint = function() {
                    window.close();
                };
            </script>
        </body>
        </html>";

        echo $html;
    }
}