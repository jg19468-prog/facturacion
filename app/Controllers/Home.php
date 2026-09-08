<?php

namespace App\Controllers;

use App\Models\FacturaModel;

class Home extends BaseController
{
    // OJO: Ya no lleva ": string" al lado de index()
    public function index()
    {
        // Candado de seguridad: Si NO ha iniciado sesión, lo regresa al login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $facturaModel = new FacturaModel();

        $data = [
            'facturasDelMes'   => $facturaModel->facturasDelMes(),
            'ingresosDelMes'   => $facturaModel->ingresosDelMes(),
            'pendientesCobro'  => $facturaModel->pendientesDeCobro(),
            'facturasAnuladas' => $facturaModel->totalAnuladas(),
            'ultimasFacturas'  => $facturaModel->ultimasFacturas(6),
            'graficoIngresos'  => $facturaModel->ingresosUltimosMeses(6),
        ];

        // Mostramos el dashboard del sistema de facturación
        return view('facturacion/index', $data);
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
