<?php
namespace App\Models;
use CodeIgniter\Model;

class FacturaModel extends Model
{
    protected $table            = 'factura';
    protected $primaryKey       = 'id_factura';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'numero_factura', 'id_cliente', 'fecha', 'subtotal', 'iva', 'total', 'estado', 'observaciones'
    ];

    protected $validationRules = [
        'id_cliente' => 'required|numeric',
        'fecha'      => 'required',
    ];

    protected $validationMessages = [
        'id_cliente' => [
            'required' => 'Debe seleccionar un cliente.',
            'numeric'  => 'El cliente seleccionado no es válido.'
        ],
        'fecha' => [
            'required' => 'La fecha de la factura es obligatoria.'
        ]
    ];

    /**
     * Listado de facturas con el nombre del cliente (para el historial).
     */
    public function listarConCliente()
    {
        return $this->select('factura.*, cliente.nombres, cliente.apellidos, cliente.cedula')
                    ->join('cliente', 'cliente.id_cliente = factura.id_cliente', 'left')
                    ->orderBy('factura.fecha', 'DESC')
                    ->orderBy('factura.id_factura', 'DESC')
                    ->findAll();
    }

    /**
     * Cantidad de facturas emitidas (no anuladas) durante el mes actual.
     */
    public function facturasDelMes()
    {
        [$inicio, $fin] = $this->rangoMesActual();

        return $this->where('fecha >=', $inicio)
                    ->where('fecha <=', $fin)
                    ->where('estado !=', 'anulada')
                    ->countAllResults();
    }

    /**
     * Suma de los ingresos (facturas pagadas) del mes actual.
     */
    public function ingresosDelMes()
    {
        [$inicio, $fin] = $this->rangoMesActual();

        $result = $this->selectSum('total')
                        ->where('fecha >=', $inicio)
                        ->where('fecha <=', $fin)
                        ->where('estado', 'pagada')
                        ->first();

        return (float) ($result['total'] ?? 0);
    }

    /**
     * Cantidad de facturas pendientes de cobro (todas, no solo del mes).
     */
    public function pendientesDeCobro()
    {
        return $this->where('estado', 'pendiente')->countAllResults();
    }

    /**
     * Cantidad de facturas anuladas.
     */
    public function totalAnuladas()
    {
        return $this->where('estado', 'anulada')->countAllResults();
    }

    /**
     * Últimas facturas registradas, con datos del cliente (para el dashboard).
     */
    public function ultimasFacturas($limite = 6)
    {
        return $this->select('factura.*, cliente.nombres, cliente.apellidos')
                    ->join('cliente', 'cliente.id_cliente = factura.id_cliente', 'left')
                    ->orderBy('factura.fecha', 'DESC')
                    ->orderBy('factura.id_factura', 'DESC')
                    ->findAll($limite);
    }

    /**
     * Ingresos (facturas no anuladas) de los últimos $meses meses, para graficar.
     * Devuelve un arreglo [ ['mes' => 'Ene', 'total' => 123.45], ... ]
     */
    public function ingresosUltimosMeses($meses = 6)
    {
        $data = [];

        for ($i = $meses - 1; $i >= 0; $i--) {
            $referencia = strtotime("first day of -$i months");
            $inicio = date('Y-m-01', $referencia);
            $fin    = date('Y-m-t', $referencia);

            $result = $this->selectSum('total')
                            ->where('fecha >=', $inicio)
                            ->where('fecha <=', $fin)
                            ->where('estado !=', 'anulada')
                            ->first();

            $data[] = [
                'mes'   => ucfirst(date('M', $referencia)),
                'total' => (float) ($result['total'] ?? 0),
            ];
        }

        return $data;
    }

    private function rangoMesActual(): array
    {
        return [date('Y-m-01'), date('Y-m-t')];
    }
}
