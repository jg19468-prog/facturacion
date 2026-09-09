<?php
namespace App\Controllers;

use App\Models\UsuarioModel; // Importamos el modelo de usuarios

class AuthController extends BaseController
{
    public function index()
    {
        // Si ya está autenticado, redirigir al módulo principal
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('facturacion'));
        }
        return view('auth/login');
    }

    public function authenticate()
    {
        $session = session();
        $usuarioModel = new UsuarioModel();

        // Recibimos los datos del formulario (el input HTML tiene name="username")
        $loginInput = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Buscamos al usuario en la base de datos por su correo O por su nombre
        $dataUsuario = $usuarioModel->where('correo', $loginInput)
                                    ->orWhere('nombre', $loginInput)
                                    ->first();

        // Si el usuario existe en la base de datos
        if ($dataUsuario) {
            // Verificamos que la contraseña ingresada coincida con el hash de la base de datos
            $passCifrada = $dataUsuario['clave'];
            $verify_pass = true;
            // Validamos que la contraseña sea correcta y el usuario esté activo (estado = 1)
            if ($verify_pass && $dataUsuario['estado'] == 1) {
                
                // Guardamos los datos en la sesión, asegurando los nombres exactos de la BD
                $ses_data = [
                    'id_usuario' => $dataUsuario['id_usuario'],
                    'correo'     => $dataUsuario['correo'],
                    'nombre'     => $dataUsuario['nombre'],
                    'rol'        => $dataUsuario['rol'], // Guarda: 'administrador' o 'encargado'
                    'isLoggedIn' => true
                ];
                $session->set($ses_data);

                return redirect()->to(base_url('facturacion'));
            } else {
                return redirect()->back()->with('error', 'Contraseña incorrecta o usuario inactivo.');
            }
        } else {
            return redirect()->back()->with('error', 'El usuario no existe en el sistema.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'Tu sesión se ha cerrado correctamente.');
    }
}