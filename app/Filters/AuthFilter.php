<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Verifica si la variable de sesión no existe o es falsa
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Debes iniciar sesión primero.');
        }

        // Verifica si la ruta exige un rol específico (pasado como argumento en Routes.php)
        if ($arguments !== null) {
            $rol_actual = $session->get('rol');
            
            // Si el rol del usuario actual no está dentro de los permitidos, se bloquea el acceso
            if (!in_array($rol_actual, $arguments)) {
                return redirect()->to(base_url('facturacion'))->with('error', 'Acceso denegado: No tienes permisos para acceder a este módulo.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No requiere acciones posteriores
    }
}