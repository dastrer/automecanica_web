<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Consulta_pago
{
	//Implementamos nuestro constructor
	public function __construct() 
	{
 
	}
	 
public function listar($fecha_inicio, $fecha_fin, $Idservicio) 
{
	$sql = "SELECT s.Tipo_comprobante, s.Num_comprobante,s.Estado,v.Placa, v.Marca,
	               ps.Fecha as FechaPago, ps.Saldo, ps.Acuenta, ps.Monto, ps.Cambio,ps.Saldo, ps.Metodo_pago
	        FROM servicio s
	        INNER JOIN vehiculo v ON s.Idvehiculo = v.Idvehiculo
	        INNER JOIN pago_servicio ps ON s.Idservicio = ps.Idservicio
	        WHERE  DATE(ps.Fecha) >= '$fecha_inicio' AND DATE(ps.Fecha) <= '$fecha_fin' 
            AND s.Idservicio = '$Idservicio'
            ORDER BY ps.Fecha ASC";
	return ejecutarConsulta($sql);

}







}

?>