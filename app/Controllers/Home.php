<?php

namespace App\Controllers;

class Home extends BaseController
{
    // OJO: Ya no lleva ": string" al lado de index()
    public function index()
    {
        // Candado de seguridad: Si NO ha iniciado sesión, lo regresa al login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        // Si pasó la validación, mostramos el sistema de facturación
        return view('facturacion/index');
    }

    public function saludo($nombre, $apellido)
    {
        echo "Hola " . $nombre . " " . $apellido;
    }

    public function suma($num1, $num2)
    {
        return "La suma de $num1 + $num2 es: " . ($num1 + $num2);
    }
}