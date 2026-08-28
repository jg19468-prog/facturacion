<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        // Aquí cargas la vista principal. 
        // Cambié 'welcome_message' por 'facturacion/index' según lo que intentabas pegar.
        return view('facturacion/index');
    }
}