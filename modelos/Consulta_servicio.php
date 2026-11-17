<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Consulta_servicio
{
	//Implementamos nuestro constructor
	public function __construct() 
	{
 
	}
	public function listar($fecha_inicio, $fecha_fin, $Idvehiculo) 
	{
		$sql = "SELECT  s.Idservicio, s.Fecha,s.Fecha_entrega, s.Tipo_comprobante, s.Num_comprobante, s.Descripcion, s.Total, s.Estado,
  		v.Placa, v.Marca, v.Color, v.Tipo,
		p1.Nombres AS Cliente,
		p.Nombres AS Usuario,
        pgt.Monto AS Monto,
        pgt.Cambio AS Cambio,
        pgt.Metodo_pago
		FROM servicio s 
       INNER JOIN vehiculo v on s.Idvehiculo=v.Idvehiculo
       INNER JOIN persona p1 on v.Idcliente=p1.Idpersona
       INNER JOIN usuario u on s.Idusuario=u.Idusuario
       INNER JOIN persona p on u.Idpersona=p.Idpersona
	   INNER JOIN (
				SELECT *
				FROM pago_servicio
				WHERE Idpago IN (
					SELECT MAX(Idpago)
					FROM pago_servicio
					GROUP BY Idservicio
				)
			) pgt ON s.Idservicio = pgt.Idservicio
		WHERE s.Estado='Reparado'";
	
		if ($fecha_inicio !== "Todos" && $fecha_fin !== "Todos") {
			$sql .= " AND DATE(s.Fecha) >= '$fecha_inicio' AND DATE(s.Fecha) <= '$fecha_fin'";
		}
	
		if ($Idvehiculo !== "Todos") {
			$sql .= " AND s.Idvehiculo = '$Idvehiculo'";
		}
	
		$sql .= " ORDER BY s.Idservicio DESC";
	
		return ejecutarConsulta($sql);
	}



}

?>