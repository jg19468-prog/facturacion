<?php

namespace App\Controllers;

use App\Models\FacturaModel;
use App\Models\ClienteModel;
// Si ya creaste el modelo de Producto, descomenta la siguiente línea:
// use App\Models\ProductoModel; 

class Home extends BaseController
{
    public function index()
    {
        // Candado de seguridad: Si NO ha iniciado sesión, lo regresa al login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $facturaModel = new FacturaModel();
        $clienteModel = new ClienteModel();
        $db = \Config\Database::connect(); // Conexión directa por si no tienes ProductoModel aún

        // 1. Ventas de hoy (contar facturas con la fecha actual)
        $hoy = date('Y-m-d');
        $ventasHoy = $facturaModel->where('fecha', $hoy)->countAllResults();

        // 2. Ingresos del mes (sumar el campo 'total' del mes y año actual)
        $mesActual = date('m');
        $anioActual = date('Y');
        
        $queryIngresos = $facturaModel->selectSum('total')
                                      ->where('MONTH(fecha)', $mesActual)
                                      ->where('YEAR(fecha)', $anioActual)
                                      ->where('estado !=', 'anulada') // Es buena práctica no sumar las anuladas
                                      ->first();
        $ingresosMes = $queryIngresos['total'] ?? 0;

        // 3. Clientes registrados
        $clientesRegistrados = $clienteModel->countAllResults();

        // 4. Stock por revisar (productos con 5 o menos unidades)
        // Usamos el query builder directo asumiendo la tabla "producto" de tu BDD
        $stockRevisar = $db->table('producto')->where('stock <=', 5)->countAllResults();

        // Preparamos la data para la vista
        $data = [
            'ventasHoy'           => $ventasHoy,
            'ingresosMes'         => $ingresosMes,
            'clientesRegistrados' => $clientesRegistrados,
            'stockRevisar'        => $stockRevisar,
            
            // Mantengo las variables de gráficos por si tu vista las sigue utilizando
            'facturasDelMes'      => $facturaModel->facturasDelMes ?? [],
            'graficoIngresos'     => $facturaModel->ingresosUltimosMeses(6) ?? [],
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