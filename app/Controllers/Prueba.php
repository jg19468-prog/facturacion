<?php

namespace App\Controllers;

class Prueba extends BaseController
{
    public function index(): string 
    {
       //echo "HOLA";
       $datos['nombre'] = "Josue Perez";
       $datos['direccion'] = "Calle Principal 123, Ibarra, Imbabura";

        return view('prueba/index',$datos);
    }
}

    


