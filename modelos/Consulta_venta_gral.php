<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Consulta_venta_gral
{
	//Implementamos nuestro constructor
	public function __construct() 
	{
 
	}
	public function listar($fecha_inicio, $fecha_fin, $Idcliente) 
	{
		$sql = "SELECT v.*, pl.Nombres as Usuario, pe.Nombres, pe.Num_documento, pe.Tipo_documento
				FROM venta v 
				JOIN persona pe ON v.Idcliente = pe.Idpersona 
				JOIN usuario u ON u.Idusuario = v.Idusuario 
				JOIN persona pl ON u.Idpersona = pl.Idpersona
				WHERE v.Estado = 'Aceptado'";
	
		if ($fecha_inicio !== "Todos" && $fecha_fin !== "Todos") {
			$sql .= " AND DATE(v.Fecha) >= '$fecha_inicio' AND DATE(v.Fecha) <= '$fecha_fin'";
		}
	
		if ($Idcliente !== "Todos") {
			$sql .= " AND v.Idcliente = '$Idcliente'";
		}
	
		$sql .= " ORDER BY v.Idventa DESC";
	
		return ejecutarConsulta($sql);
	}



}

?>