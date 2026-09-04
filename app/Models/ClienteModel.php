<?php
namespace App\Models;
use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table            = 'cliente';
    protected $primaryKey       = 'id_cliente';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['cedula', 'nombres', 'apellidos', 'telefono', 'direccion'];

    protected $validationRules = [
        'cedula'    => 'required|exact_length[10]|is_unique[cliente.cedula,id_cliente,{id_cliente}]',
        'nombres'   => 'required|max_length[100]',
        'apellidos' => 'required|max_length[100]'
    ];
    
    protected $validationMessages = [
        'cedula' => [
            'required'     => 'La cédula es obligatoria.',
            'exact_length' => 'La cédula debe tener exactamente 10 dígitos.',
            'is_unique'    => 'Esta cédula ya está registrada en el sistema.'
        ],
        'nombres' => [
            'required' => 'Los nombres son obligatorios.'
        ],
        'apellidos' => [
            'required' => 'Los apellidos son obligatorios.'
        ]
    ];
}