<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table            = 'categoria';
    protected $primaryKey       = 'id_categoria';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['nombre'];

    // Validaciones
    protected $validationRules = [
        'nombre' => 'required|max_length[50]|is_unique[categoria.nombre,id_categoria,{id_categoria}]'
    ];
    
    protected $validationMessages = [
        'nombre' => [
            'required'  => 'El nombre de la categoría es obligatorio.',
            'max_length'=> 'El nombre no puede exceder los 50 caracteres.',
            'is_unique' => 'Ya existe una categoría con este nombre.'
        ]
    ];
}