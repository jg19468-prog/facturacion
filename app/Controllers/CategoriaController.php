<?php

namespace App\Controllers;

use App\Models\CategoriaModel;
use CodeIgniter\Controller;

class CategoriaController extends BaseController
{
    protected $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new CategoriaModel();
    }

    public function index()
    {
        $data = [
            'categorias' => $this->categoriaModel->findAll(),
        ];

        return view('categorias/index', $data);
    }

    public function save()
    {
        $id = $this->request->getPost('id_categoria');
        
        $data = [
            'nombre' => $this->request->getPost('nombre')
        ];

        if ($id) {
            // Actualizar
            if ($this->categoriaModel->update($id, $data)) {
                return redirect()->to('/categorias')->with('success', 'Categoría actualizada con éxito.');
            }
        } else {
            // Crear
            if ($this->categoriaModel->insert($data)) {
                return redirect()->to('/categorias')->with('success', 'Categoría registrada con éxito.');
            }
        }

        // Si falla la validación
        return redirect()->to('/categorias')->with('errors', $this->categoriaModel->errors());
    }

    public function delete($id)
    {
        // En un entorno real, verificar si la categoría está en uso en productos antes de eliminar
        if ($this->categoriaModel->delete($id)) {
            return redirect()->to('/categorias')->with('success', 'Categoría eliminada con éxito.');
        }
        
        return redirect()->to('/categorias')->with('error', 'No se pudo eliminar la categoría.');
    }
}