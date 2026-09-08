<?php
namespace App\Controllers;
use App\Models\MarcaModel;
use CodeIgniter\Controller;

class MarcaController extends BaseController
{
    protected $marcaModel;

    public function __construct()
    {
        // Verificar si la sesión existe y si el rol es diferente a administrador
        if (session()->get('rol') !== 'administrador') {
            // Si es encargado, lo expulsamos de aquí y lo mandamos a facturas
            header('Location: ' . base_url('facturas'));
            exit(); 
        }

        $this->marcaModel = new MarcaModel();
    }

    public function index()
    {
        $data = [
            'marcas' => $this->marcaModel->findAll(),
        ];
        return view('marcas/index', $data);
    }

    public function save()
    {
        $id = $this->request->getPost('id_marca');
        
        $data = [
            'nombre' => $this->request->getPost('nombre')
        ];

        if ($id) {
            // Actualizar
            if ($this->marcaModel->update($id, $data)) {
                return redirect()->to('/marcas')->with('success', 'Marca actualizada con éxito.');
            }
        } else {
            // Crear
            if ($this->marcaModel->insert($data)) {
                return redirect()->to('/marcas')->with('success', 'Marca registrada con éxito.');
            }
        }

        // Si falla la validación
        return redirect()->to('/marcas')->with('errors', $this->marcaModel->errors());
    }

    public function delete($id)
    {
        // En un entorno real, verificar si la marca está en uso en productos antes de eliminar
        if ($this->marcaModel->delete($id)) {
            return redirect()->to('/marcas')->with('success', 'Marca eliminada con éxito.');
        }
        
        return redirect()->to('/marcas')->with('error', 'No se pudo eliminar la marca.');
    }
}