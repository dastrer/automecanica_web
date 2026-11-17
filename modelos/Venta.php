<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Venta 
{
	//Implementamos nuestro constructor
	public function __construct() 
	{
 
	}  
 
	public function insertar($Fecha,$Hora,$Tipo_comprobante,$Num_comprobante,$Tipo,$Monto,$Cambio,$Total, $Idusuario, $Idcliente,$Precio_compra,$Precio_venta,$Descuento, $Cantidad_venta, $Idarticulo)
{ 

    $sql = "INSERT INTO venta(Fecha,Hora,Tipo_comprobante, Num_comprobante,Tipo,Monto,Cambio,Total,Idusuario, Idcliente,Estado)
            VALUES ('$Fecha','$Hora','$Tipo_comprobante','$Num_comprobante','$Tipo','$Monto','$Cambio','$Total','$Idusuario','$Idcliente','Aceptado')";
    
    $Idventanew = ejecutarConsulta_retornarID($sql); 

    $num_elementos = 0;
    $sw = true;

    while ($num_elementos < count($Idarticulo)) {
        $sql_detalle = "INSERT INTO articulo_venta(Precio_compra,Precio_venta, Descuento,Cantidad_venta, Idarticulo, Idventa) 
                        VALUES ('$Precio_compra[$num_elementos]','$Precio_venta[$num_elementos]','$Descuento[$num_elementos]','$Cantidad_venta[$num_elementos]', '$Idarticulo[$num_elementos]', '$Idventanew')";
        ejecutarConsulta($sql_detalle) or $sw = false;
        $num_elementos++;
    }


    return $sw ? $Idventanew : false;
}

 	public function verificarDuplicidad($Num_documento)
		{
		   $sql="SELECT * FROM persona
		   WHERE   Num_documento='$Num_documento'";
		   return ejecutarConsulta($sql);  
		}
		
public function insertarNuevo($Tipopersona,$Nombres,$Num_documento,$Tipo_documento,$Telefono,$Fecha,$Hora,$Tipo_comprobante,$Num_comprobante,$Tipo,$Monto,$Cambio,$Total, $Idusuario,$Precio_compra,$Precio_venta,$Descuento, $Cantidad_venta, $Idarticulo)
{
	// Consulta de inserción en la tabla persona
	$sqlcliente = "INSERT INTO persona (Tipopersona,Nombres,Num_documento,Tipo_documento,Telefono,Direccion,Estado)
	VALUES ('$Tipopersona','$Nombres','$Num_documento','$Tipo_documento','$Telefono',NULL, 1)";
    $IdclienteNew =ejecutarConsulta_retornarID($sqlcliente);

    $sql = "INSERT INTO venta(Fecha,Hora,Tipo_comprobante, Num_comprobante,Tipo,Monto,Cambio,Total,Idusuario, Idcliente, Estado)
            VALUES ('$Fecha','$Hora','$Tipo_comprobante','$Num_comprobante','$Tipo','$Monto','$Cambio','$Total','$Idusuario','$IdclienteNew','Aceptado')";
    $Idventanew = ejecutarConsulta_retornarID($sql); 

    $num_elementos = 0;
    $sw = true;

    while ($num_elementos < count($Idarticulo)) {
        $sql_detalle = "INSERT INTO articulo_venta(Precio_compra,Precio_venta,Descuento,Cantidad_venta, Idarticulo, Idventa) 
                        VALUES ('$Precio_compra[$num_elementos]','$Precio_venta[$num_elementos]','$Descuento[$num_elementos]', '$Cantidad_venta[$num_elementos]', '$Idarticulo[$num_elementos]', '$Idventanew')";
        ejecutarConsulta($sql_detalle) or $sw = false;
        $num_elementos++;
    }

    return $sw ? $Idventanew : false;
}

	
public function anular($Idventa)
{
    $consultaVenta = "SELECT av.Idarticulo,av.Cantidad_venta AS Cantidad_venta FROM articulo_venta av  WHERE av.Idventa  = '$Idventa'";
    $resultados = ejecutarConsulta($consultaVenta);

    $datosVenta = array();
    while ($row = $resultados->fetch_assoc()) {
        $datosVenta[] = $row;
    }
 
    $sqlAnularVenta = "UPDATE venta SET Estado='Anulado' WHERE Idventa='$Idventa'";
    ejecutarConsulta($sqlAnularVenta);
 
    foreach ($datosVenta as $venta) {
        $Idarticulo = $venta['Idarticulo'];
        $cantidad = $venta['Cantidad_venta'];
		$sqlRestaurarStock = "UPDATE articulo 
								  SET Cantidad = Cantidad + $cantidad 
								  WHERE Idarticulo = '$Idarticulo'";
			ejecutarConsulta($sqlRestaurarStock);
    }
    return true; 
}


	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($Idventa)
	{
		$sql="SELECT  v.Idventa,v.Fecha,v.Hora,v.Tipo_comprobante,
                v.Num_comprobante,v.Tipo,v.Monto,v.Cambio,v.Total,pe.Idpersona as Idcliente,
                pe.Nombres,pe.Num_documento,pe.Tipo_documento,pe.Telefono
				FROM venta v 
				  JOIN persona pe ON v.Idcliente=pe.Idpersona
                WHERE v.Idventa='$Idventa'";
		return ejecutarConsultaSimpleFila($sql);
	}
 
	public function listarDetalle($Idventa)
	{
		$sql="SELECT av.Idventa,av.Idarticulo,a.Nombre,a.Cantidad,ua.Unidadmedida,av.Cantidad_venta,av.Precio_venta,av.Descuento,
		ROUND(av.Cantidad_venta*av.Precio_venta-av.Descuento,2) as Subtotal
		FROM articulo_venta av
        INNER JOIN articulo a ON av.Idarticulo=a.Idarticulo
        INNER JOIN categoria c  ON a.Idcategoria=c.Idcategoria
        INNER JOIN unidadmedida ua ON a.Idunidadmedida = ua.Idunidadmedida
		WHERE av.Idventa='$Idventa'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT v.*,p.Nombres as Usuario,pe.Nombres,pe.Num_documento,pe.Tipo_documento
		FROM venta v 
        JOIN persona pe ON v.Idcliente=pe.Idpersona
		JOIN usuario u ON u.Idusuario=v.Idusuario 
        JOIN persona p on u.Idpersona=p.Idpersona
        ORDER BY  v.Idventa desc";
		return ejecutarConsulta($sql);		
	}
 
	//Metodo para mostrar el numero del comprobante
	public function generarNumero($Tipo_comprobante)
	{
		if($Tipo_comprobante=="Ticket")
		{
			$sql="SELECT COALESCE(MAX(Num_comprobante),0) AS ultimo_numero_recibo FROM venta
			WHERE Tipo_comprobante='Ticket' AND YEAR(Fecha) = YEAR(CURDATE()) ";
			return ejecutarConsulta($sql); 
		}
		else
		{
			if($Tipo_comprobante=="Recibo")
			{
				$sql="SELECT  COALESCE(MAX(Num_comprobante), 0) AS ultimo_numero_recibo FROM venta
				WHERE Tipo_comprobante='Recibo'AND YEAR(Fecha) = YEAR(CURDATE())";
				return ejecutarConsulta($sql);
			}
		}

	}

	public function ventacabecera($Idventa){
		$sql="SELECT v.*,p1.Nombres as usuario,p2.Nombres AS cliente,p2.Tipo_documento,p2.Num_documento,p2.Telefono,v.Tipo
		FROM venta v 
        JOIN persona p2 on v.Idcliente=p2.Idpersona
		JOIN usuario u ON u.Idusuario=v.Idusuario 
        JOIN persona p1 on u.Idpersona=p1.Idpersona
        WHERE v.Idventa='$Idventa'";
		return ejecutarConsulta($sql);
	}
 
	public function ventadetalle($Idventa){
		$sql="SELECT  av.Idventa,av.Idarticulo,a.Codigo,a.Nombre,a.Cantidad,av.Cantidad_venta,av.Precio_venta,av.Descuento,
		ROUND(av.Cantidad_venta*av.Precio_venta-av.Descuento,2) as Subtotal
		FROM articulo_venta av
        INNER JOIN articulo a ON av.Idarticulo=a.Idarticulo
		WHERE av.Idventa='$Idventa'";
		return ejecutarConsulta($sql);
	}

// DATOS ESTADISCOS

	public function ventahoy(){
		$sql="SELECT IFNULL(COUNT(v.Idventa), 0) as ventahoy 
		FROM venta v
		WHERE DATE(v.Fecha) = CURDATE() AND v.Estado = 'Aceptado'";
		return ejecutarConsulta($sql);
	} 

	public function realizados(){
		$sql="SELECT IFNULL(SUM(v.Total), 0) as vendido 
		FROM venta v
		WHERE DATE(v.Fecha) = CURDATE() AND v.Estado = 'Aceptado' ";
		return ejecutarConsulta($sql);
	}

	public function ventas_mes(){
		$sql="SELECT IFNULL(SUM(v.Total), 0) AS ventas_mes
		FROM venta v
		WHERE MONTH(v.Fecha) = MONTH(CURDATE()) 
		AND YEAR(v.Fecha) = YEAR(CURDATE()) 
		AND v.Estado = 'Aceptado'";
		return ejecutarConsulta($sql);
	}



	public function anulados(){
		$sql="SELECT IFNULL(COUNT(v.Idventa), 0) as anulados 
		FROM venta v
		WHERE DATE(v.Fecha) = CURDATE() AND v.Estado = 'Anulado'";
		return ejecutarConsulta($sql);
}


 



}

?>