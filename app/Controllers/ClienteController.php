<?php
namespace App\Controllers;
use App\Models\ClienteModel;
use CodeIgniter\Controller;

class ClienteController extends BaseController
{
    protected $clienteModel;

    public function __construct()
    {
        // Verificar si la sesión existe y si el rol es diferente a administrador
        if (session()->get('rol') !== 'administrador') {
            // Si es encargado, lo expulsamos de aquí y lo mandamos a facturas
            header('Location: ' . base_url('facturas'));
            exit(); 
        }

        $this->clienteModel = new ClienteModel();
    }

    public function index()
    {
        $data = [
            'clientes' => $this->clienteModel->findAll(),
        ];
        return view('clientes/index', $data);
    }

    public function save()
    {
        $id = $this->request->getPost('id_cliente');
        $cedula = $this->request->getPost('cedula');
        
        // 1. Validar matemáticamente la cédula
        if (!$this->validarCedula($cedula)) {
            return redirect()->to('/clientes')->with('errors', ['La cédula ingresada no es válida. Revise los dígitos.']);
        }

        $data = [
            'id_cliente' => $id, // <-- Agregado para evitar errores de validación (is_unique) al actualizar
            'cedula'    => $cedula,
            'nombres'   => $this->request->getPost('nombres'),
            'apellidos' => $this->request->getPost('apellidos'),
            'telefono'  => $this->request->getPost('telefono'),
            'direccion' => $this->request->getPost('direccion')
        ];

        if ($id) {
            // Actualizar
            if ($this->clienteModel->update($id, $data)) {
                return redirect()->to('/clientes')->with('success', 'Cliente actualizado con éxito.');
            }
        } else {
            // Crear
            if ($this->clienteModel->insert($data)) {
                return redirect()->to('/clientes')->with('success', 'Cliente registrado con éxito.');
            }
        }

        return redirect()->to('/clientes')->with('errors', $this->clienteModel->errors());
    }

    public function delete($id)
    {
        if ($this->clienteModel->delete($id)) {
            return redirect()->to('/clientes')->with('success', 'Cliente eliminado con éxito.');
        }
        return redirect()->to('/clientes')->with('error', 'No se pudo eliminar el cliente.');
    }

    /**
     * Valida el algoritmo de Módulo 10 para cédulas
     */
    private function validarCedula($cedula)
    {
        if (strlen($cedula) != 10 || !is_numeric($cedula)) {
            return false;
        }

        $provincia = intval(substr($cedula, 0, 2));
        if ($provincia < 1 || $provincia > 24) {
            return false;
        }

        $tercerDigito = intval($cedula[2]);
        if ($tercerDigito >= 6) {
            return false; 
        }

        $coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
        $suma = 0;

        for ($i = 0; $i < 9; $i++) {
            $valor = intval($cedula[$i]) * $coeficientes[$i];
            if ($valor > 9) {
                $valor -= 9;
            }
            $suma += $valor;
        }

        $digitoVerificador = intval($cedula[9]);
        $decenaSuperior = ceil($suma / 10) * 10;
        $resultado = $decenaSuperior - $suma;
        
        if ($resultado == 10) {
            $resultado = 0;
        }

        return $resultado == $digitoVerificador;
    }
}