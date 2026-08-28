<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function saludo($nombre,$apellido){
        echo "Hola " . $nombre . " " . $apellido;
    }
    public function sumita($numero1, $numero2)
{
  
    $resultado = $numero1 + $numero2;
    
    echo "El resultado es: " . $resultado;
}

}
