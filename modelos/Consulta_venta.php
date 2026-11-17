<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Consulta_venta
{
	//Implementamos nuestro constructor
	public function __construct() 
	{
 
	} 

	public function listar($fecha_inicio, $fecha_fin, $Idarticulo)
{
    $sql = "SELECT v.Tipo,
                v.Fecha, v.Tipo_comprobante, v.Num_comprobante,
                ar.Nombre AS Articulo, av.Cantidad_venta,av.Precio_compra, av.Precio_venta,
                ROUND(av.Precio_venta - av.Precio_compra, 2) AS Utilidad, 
                av.Descuento,
                ROUND(av.Cantidad_venta * av.Precio_venta, 2) AS Subtotal,
                ROUND(av.Cantidad_venta * av.Precio_compra, 2) AS Costo_Total,
                ROUND((av.Precio_venta - av.Precio_compra) * av.Cantidad_venta, 2) AS Utilidad_Total
            FROM 
                venta v 
            JOIN articulo_venta av ON v.Idventa = av.Idventa
            JOIN articulo ar ON av.Idarticulo = ar.Idarticulo
            WHERE v.Estado = 'Aceptado'";
 
    if ($fecha_inicio !== "Todos" && $fecha_fin !== "Todos") 
    {
        $sql .= " AND DATE(v.Fecha) >= '$fecha_inicio' AND DATE(v.Fecha) <= '$fecha_fin'";
    }

    if ($Idarticulo !== "Todos") {
        $sql .= " AND ar.Idarticulo = '$Idarticulo'";
    }

    return ejecutarConsulta($sql);
}




}

?>