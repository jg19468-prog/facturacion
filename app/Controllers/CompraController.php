<?php

namespace App\Controllers;

class CompraController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        // Traemos las compras con el nombre del proveedor
        $compras = $db->table('compra')
            ->select('compra.*, proveedor.nombre as proveedor_nombre')
            ->join('proveedor', 'proveedor.id_proveedor = compra.id_proveedor', 'left')
            ->orderBy('compra.id_compra', 'DESC')
            ->get()->getResultArray();

        return view('compras/index', ['compras' => $compras]);
    }

    public function nueva()
    {
        $db = \Config\Database::connect();
        
        $data = [
            'proveedores' => $db->table('proveedor')->get()->getResultArray(),
            'productos'   => $db->table('producto')->get()->getResultArray()
        ];

        return view('compras/nueva', $data);
    }

    public function save()
    {
        $db = \Config\Database::connect();
        
        // Iniciamos la transacción (Todo o nada)
        $db->transStart();

        // 1. Recibir datos de la cabecera
        $id_proveedor = $this->request->getPost('id_proveedor');
        $fecha = $this->request->getPost('fecha');
        $id_usuario = session()->get('id_usuario') ?? 1; // Fallback a 1 si no hay sesión activa en pruebas

        // 2. Recibir arreglos de detalles (los campos con [])
        $id_productos = $this->request->getPost('id_producto');
        $cantidades = $this->request->getPost('cantidad');
        $costos = $this->request->getPost('costo_unitario');

        // Calcular el total recorriendo los detalles
        $total_compra = 0;
        if ($id_productos) {
            foreach ($id_productos as $index => $id_prod) {
                $total_compra += ($cantidades[$index] * $costos[$index]);
            }
        }

        // 3. Insertar la Cabecera de la Compra
        $dataCompra = [
            'id_proveedor' => $id_proveedor,
            'id_usuario'   => $id_usuario,
            'fecha'        => $fecha,
            'total'        => $total_compra
        ];
        $db->table('compra')->insert($dataCompra);
        $id_compra = $db->insertID(); // Obtenemos el ID generado

        // 4. Insertar los Detalles y Actualizar Stock
        if ($id_productos) {
            foreach ($id_productos as $index => $id_prod) {
                $cantidad = $cantidades[$index];
                $costo = $costos[$index];
                $subtotal = $cantidad * $costo;

                // Guardar el detalle congelando el costo
                $db->table('detalle_compra')->insert([
                    'id_compra'      => $id_compra,
                    'id_producto'    => $id_prod,
                    'cantidad'       => $cantidad,
                    'costo_unitario' => $costo,
                    'subtotal'       => $subtotal
                ]);

                // SUMAR EL STOCK AL INVENTARIO
                $db->query("UPDATE producto SET stock = stock + ? WHERE id_producto = ?", [$cantidad, $id_prod]);
            }
        }

        // Finalizar transacción
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Ocurrió un error al guardar la compra.');
        }

        return redirect()->to(base_url('compras'))->with('success', 'Compra registrada. El inventario ha sido actualizado.');
    }
}