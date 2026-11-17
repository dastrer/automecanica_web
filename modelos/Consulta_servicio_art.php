<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Consulta_servicio_art
{
	//Implementamos nuestro constructor
	public function __construct() 
	{
 
	} 

	public function listar($fecha_inicio, $fecha_fin, $Idarticulo)
{
    $sql = "SELECT v.Idvehiculo, v.Placa,
                s.Fecha, s.Tipo_comprobante, s.Num_comprobante,
                ar.Nombre AS Articulo, sa.Cantidad_venta,sa.Precio_compra, sa.Precio_venta,
                ROUND(sa.Precio_venta - sa.Precio_compra, 2) AS Utilidad, 
                sa.Descuento,
                ROUND(sa.Cantidad_venta * sa.Precio_venta, 2) AS Subtotal,
                ROUND(sa.Cantidad_venta * sa.Precio_compra, 2) AS Costo_Total,
                ROUND((sa.Precio_venta - sa.Precio_compra) * sa.Cantidad_venta, 2) AS Utilidad_Total
            FROM 
                servicio s 
            JOIN vehiculo v ON s.Idvehiculo = v.Idvehiculo  
            JOIN servicio_articulo sa ON s.Idservicio = sa.Idservicio
            JOIN articulo ar ON sa.Idarticulo = ar.Idarticulo
            WHERE s.Estado = 'Reparado'";
 
    if ($fecha_inicio !== "Todos" && $fecha_fin !== "Todos") 
    {
        $sql .= " AND DATE(s.Fecha) >= '$fecha_inicio' AND DATE(s.Fecha) <= '$fecha_fin'";
    }

    if ($Idarticulo !== "Todos") {
        $sql .= " AND ar.Idarticulo = '$Idarticulo'";
    }

    return ejecutarConsulta($sql);
}




}

?>