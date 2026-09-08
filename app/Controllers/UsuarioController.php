<?php
namespace App\Controllers;
use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class UsuarioController extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        // Verificar si la sesión existe y si el rol es diferente a administrador
        if (session()->get('rol') !== 'administrador') {
            // Si es encargado, lo expulsamos de aquí y lo mandamos a facturas
            header('Location: ' . base_url('facturas'));
            exit(); 
        }

        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
    {
        $data = [
            'usuarios' => $this->usuarioModel->findAll(),
        ];
        return view('usuarios/index', $data);
    }

    public function save()
    {
        $id = $this->request->getPost('id_usuario');
        $clave = $this->request->getPost('clave');
        
        $data = [
            'id_usuario' => $id, // <--- SOLUCIÓN: Agregamos el ID para la validación del correo
            'nombre'     => $this->request->getPost('nombre'),
            'correo'     => $this->request->getPost('correo'),
            'rol'        => $this->request->getPost('rol'),
            // Si el checkbox está marcado llega 'on', de lo contrario null
            'estado'     => $this->request->getPost('estado') ? 1 : 0 
        ];

        // Solo actualizar la contraseña si el usuario escribió una nueva
        if (!empty($clave)) {
            $data['clave'] = password_hash($clave, PASSWORD_DEFAULT);
        } elseif (!$id) {
            // Si es un usuario nuevo, la clave es obligatoria
            return redirect()->to('/usuarios')->with('errors', ['La contraseña es obligatoria para usuarios nuevos.']);
        }

        if ($id) {
            // Actualizar
            if ($this->usuarioModel->update($id, $data)) {
                return redirect()->to('/usuarios')->with('success', 'Usuario actualizado con éxito.');
            }
        } else {
            // Crear
            if ($this->usuarioModel->insert($data)) {
                return redirect()->to('/usuarios')->with('success', 'Usuario registrado con éxito.');
            }
        }

        return redirect()->to('/usuarios')->with('errors', $this->usuarioModel->errors());
    }

    public function delete($id)
    {
        if ($this->usuarioModel->delete($id)) {
            return redirect()->to('/usuarios')->with('success', 'Usuario eliminado con éxito.');
        }
        return redirect()->to('/usuarios')->with('error', 'No se pudo eliminar el usuario.');
    }
}