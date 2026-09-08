<?php
namespace App\Models;
use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuario';
    protected $primaryKey       = 'id_usuario';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['nombre', 'correo', 'clave', 'rol', 'estado'];

    protected $validationRules = [
        // ---> SOLUCIÓN: Agregamos la regla para el id_usuario <---
        'id_usuario' => 'permit_empty|is_natural_no_zero', 
        'nombre'     => 'required|max_length[100]',
        'correo'     => 'required|valid_email|is_unique[usuario.correo,id_usuario,{id_usuario}]',
        'rol'        => 'required|in_list[administrador,encargado]'
    ];
    
    protected $validationMessages = [
        'nombre' => [
            'required'   => 'El nombre es obligatorio.',
            'max_length' => 'El nombre no puede exceder los 100 caracteres.'
        ],
        'correo' => [
            'required'    => 'El correo es obligatorio.',
            'valid_email' => 'Ingrese un formato de correo electrónico válido.',
            'is_unique'   => 'Este correo ya está registrado en el sistema.'
        ],
        'rol' => [
            'required' => 'Debe seleccionar un rol válido.',
            'in_list'  => 'El rol seleccionado no es válido.'
        ]
    ];
}