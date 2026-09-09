<?php

namespace App\Controllers;

class InventarioController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        $productos = $db->table('producto')
            ->select('producto.*, categoria.nombre as categoria_nombre, marca.nombre as marca_nombre')
            ->join('categoria', 'categoria.id_categoria = producto.id_categoria', 'left')
            ->join('marca', 'marca.id_marca = producto.id_marca', 'left')
            ->get()->getResultArray();

        $categorias = $db->table('categoria')->get()->getResultArray();
        $marcas = $db->table('marca')->get()->getResultArray();

        $data = [
            'productos'  => $productos,
            'categorias' => $categorias,
            'marcas'     => $marcas
        ];

        return view('inventario/index', $data);
    }

    public function save()
    {
        $db = \Config\Database::connect();
        
        // Recibimos el ID oculto del formulario
        $id_producto = $this->request->getPost('id_producto');
        
        $data = [
            'codigo_barras' => $this->request->getPost('codigo_barras'),
            'nombre'        => $this->request->getPost('nombre'),
            'id_categoria'  => $this->request->getPost('id_categoria'),
            'id_marca'      => $this->request->getPost('id_marca'),
            'precio_venta'  => $this->request->getPost('precio_venta')
        ];

        if (!empty($id_producto)) {
            // Si hay un ID, significa que estamos EDITANDO
            $db->table('producto')->where('id_producto', $id_producto)->update($data);
            $mensaje = 'Producto actualizado exitosamente.';
        } else {
            // Si el ID viene vacío, estamos CREANDO uno nuevo
            $data['stock'] = 0; 
            $db->table('producto')->insert($data);
            $mensaje = 'Producto registrado exitosamente.';
        }

        return redirect()->to(base_url('inventario'))->with('success', $mensaje);
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        
        // Eliminamos el producto según el ID enviado por la URL
        $db->table('producto')->where('id_producto', $id)->delete();
        
        return redirect()->to(base_url('inventario'))->with('success', 'Producto eliminado correctamente.');
    }
}