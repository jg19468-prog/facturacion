<?php
namespace App\Controllers;
use App\Models\ProveedorModel;
use CodeIgniter\Controller;

class ProveedorController extends BaseController
{
    protected $proveedorModel;

    public function __construct()
    {
        // Verificar si la sesión existe y si el rol es diferente a administrador
        if (session()->get('rol') !== 'administrador') {
            // Si es encargado, lo expulsamos de aquí y lo mandamos a facturas
            header('Location: ' . base_url('facturas'));
            exit(); 
        }

        $this->proveedorModel = new ProveedorModel();
    }

    public function index()
    {
        $data = [
            'proveedores' => $this->proveedorModel->findAll(),
        ];
        return view('proveedores/index', $data);
    }

    public function save()
    {
        $id = $this->request->getPost('id_proveedor');
        
        $data = [
            'identificacion' => $this->request->getPost('identificacion'),
            'nombre'         => $this->request->getPost('nombre'),
            'telefono'       => $this->request->getPost('telefono')
        ];

        if ($id) {
            // Actualizar
            if ($this->proveedorModel->update($id, $data)) {
                return redirect()->to('/proveedores')->with('success', 'Proveedor actualizado con éxito.');
            }
        } else {
            // Crear
            if ($this->proveedorModel->insert($data)) {
                return redirect()->to('/proveedores')->with('success', 'Proveedor registrado con éxito.');
            }
        }

        // Si falla la validación
        return redirect()->to('/proveedores')->with('errors', $this->proveedorModel->errors());
    }

    public function delete($id)
    {
        if ($this->proveedorModel->delete($id)) {
            return redirect()->to('/proveedores')->with('success', 'Proveedor eliminado con éxito.');
        }
        
        return redirect()->to('/proveedores')->with('error', 'No se pudo eliminar el proveedor.');
    }
}