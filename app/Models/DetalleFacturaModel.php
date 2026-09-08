<?php
namespace App\Models;
use CodeIgniter\Model;

class DetalleFacturaModel extends Model
{
    protected $table            = 'detalle_factura';
    protected $primaryKey       = 'id_detalle';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['id_factura', 'descripcion', 'cantidad', 'precio_unitario', 'subtotal'];

    /**
     * Obtiene todas las líneas de una factura específica.
     */
    public function porFactura($id_factura)
    {
        return $this->where('id_factura', $id_factura)->findAll();
    }
}
