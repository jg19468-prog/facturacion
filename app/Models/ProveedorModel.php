<?php
namespace App\Models;
use CodeIgniter\Model;

class ProveedorModel extends Model
{
    protected $table            = 'proveedor';
    protected $primaryKey       = 'id_proveedor';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['identificacion', 'nombre', 'telefono'];

    // Validaciones
    protected $validationRules = [
        'identificacion' => 'required|max_length[20]|is_unique[proveedor.identificacion,id_proveedor,{id_proveedor}]',
        'nombre'         => 'required|max_length[100]'
    ];
    
    protected $validationMessages = [
        'identificacion' => [
            'required'  => 'La identificación (RUC/Cédula) es obligatoria.',
            'max_length'=> 'La identificación no puede exceder los 20 caracteres.',
            'is_unique' => 'Este proveedor ya está registrado con esta identificación.'
        ],
        'nombre' => [
            'required'  => 'El nombre de la empresa o proveedor es obligatorio.',
            'max_length'=> 'El nombre no puede exceder los 100 caracteres.'
        ]
    ];
}