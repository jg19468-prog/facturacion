<?php
namespace App\Models;
use CodeIgniter\Model;

class MarcaModel extends Model
{
    protected $table            = 'marca';
    protected $primaryKey       = 'id_marca';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['nombre'];

    // Validaciones
    protected $validationRules = [
        'nombre' => 'required|max_length[50]|is_unique[marca.nombre,id_marca,{id_marca}]'
    ];
    
    protected $validationMessages = [
        'nombre' => [
            'required'  => 'El nombre de la marca es obligatorio.',
            'max_length'=> 'El nombre no puede exceder los 50 caracteres.',
            'is_unique' => 'Ya existe una marca con este nombre.'
        ]
    ];
}