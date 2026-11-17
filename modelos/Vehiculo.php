<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Vehiculo
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function insertar($Fecha, $Placa, $Marca, $Color, $Observaciones, $Tipo, $Imagen, $Idcliente)
	{

		$sql = "INSERT INTO vehiculo(Fecha,Placa,Marca,Color,Observaciones,Tipo,Imagen,Idcliente,Estado)
            VALUES ('$Fecha','$Placa','$Marca','$Color','$Observaciones','$Tipo','$Imagen','$Idcliente',1)";

		return ejecutarConsulta($sql);
	}
	public function editar($Idvehiculo, $Fecha, $Placa, $Marca, $Color, $Observaciones, $Tipo, $Imagen, $Idcliente)
	{
		$sql = "UPDATE vehiculo  SET Fecha='$Fecha',Placa='$Placa',Marca='$Marca',Color='$Color',Observaciones='$Observaciones',Tipo='$Tipo',Imagen='$Imagen',Idcliente='$Idcliente'
		WHERE Idvehiculo='$Idvehiculo'";
		//llamamos al metodo ejecutarConsulta
		return ejecutarConsulta($sql);
	}

	public function verificarDuplicidad($Num_documento)
	{
		$sql = "SELECT * FROM persona
		WHERE   Num_documento='$Num_documento'";
		return ejecutarConsulta($sql);
	}

	public function verificarDuplicidadPlaca($Placa)
	{
		$sql = "SELECT * FROM vehiculo WHERE Placa = '$Placa'";
		return ejecutarConsulta($sql);
	}


	public function insertarNuevo($Tipopersona, $Nombres, $Num_documento, $Tipo_documento, $Telefono,$Direccion, $Fecha, $Placa, $Marca, $Color, $Observaciones, $Tipo, $Imagen)
	{
		// Consulta de inserción en la tabla persona
		$sqlcliente = "INSERT INTO persona (Tipopersona,Nombres,Num_documento,Tipo_documento,Telefono,Direccion,Estado)
		VALUES ('$Tipopersona','$Nombres','$Num_documento','$Tipo_documento','$Telefono','$Direccion',1)";
		$IdclienteNew = ejecutarConsulta_retornarID($sqlcliente);
		$sql = "INSERT INTO vehiculo(Fecha,Placa,Marca,Color,Observaciones,Tipo,Imagen,Idcliente,Estado)
				VALUES ('$Fecha','$Placa','$Marca','$Color','$Observaciones','$Tipo','$Imagen','$IdclienteNew',1)";

		return ejecutarConsulta($sql);
	}

	public function editarNuevo($Tipopersona, $Nombres, $Num_documento, $Tipo_documento, $Telefono,$Direccion, $Idvehiculo, $Fecha, $Placa, $Marca, $Color, $Observaciones, $Tipo, $Imagen)
	{
		// Consulta de inserción en la tabla persona
		$sqlcliente = "INSERT INTO persona (Tipopersona,Nombres,Num_documento,Tipo_documento,Telefono,Direccion,Estado)
		VALUES ('$Tipopersona','$Nombres','$Num_documento','$Tipo_documento','$Telefono','$Direccion',1)";
		$IdclienteNew = ejecutarConsulta_retornarID($sqlcliente);

		$sql = "UPDATE vehiculo  SET Fecha='$Fecha',Placa='$Placa',Marca='$Marca',Color='$Color',Observaciones='$Observaciones',Tipo='$Tipo',Imagen='$Imagen',Idcliente='$IdclienteNew'
		WHERE Idvehiculo='$Idvehiculo'";
		//llamamos al metodo ejecutarConsulta
		return ejecutarConsulta($sql);
	}

	public function desactivar($Idvehiculo)
	{
		$sql = "UPDATE vehiculo SET Estado='0' WHERE Idvehiculo='$Idvehiculo'";
		return ejecutarConsulta($sql);
	}

	public function activar($Idvehiculo)
	{
		$sql = "UPDATE vehiculo SET Estado='1' WHERE Idvehiculo='$Idvehiculo'";
		return ejecutarConsulta($sql);
	}



	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($Idvehiculo)
	{
		$sql = "SELECT  v.Idvehiculo,v.Fecha,v.Placa,v.Marca,v.Color,Imagen,
                v.Observaciones,v.Tipo,pe.Idpersona as Idcliente,
                pe.Nombres,pe.Num_documento,pe.Tipo_documento,pe.Telefono,pe.Direccion
				FROM vehiculo v 
				  JOIN persona pe ON v.Idcliente=pe.Idpersona
                WHERE v.Idvehiculo='$Idvehiculo'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT v.*,pe.Nombres,pe.Num_documento,pe.Tipo_documento
		FROM vehiculo v 
        JOIN persona pe ON v.Idcliente=pe.Idpersona
        ORDER BY  v.Idvehiculo desc";
		return ejecutarConsulta($sql);
	}

		public function selectVehiculo()
	{
		$sql = "SELECT *
		FROM vehiculo WHERE Estado=1";
		return ejecutarConsulta($sql);
	}


	// DATOS ESTADISCOS
		public function vehiculos_mes()
	{
		$sql = "SELECT COUNT(*) AS vehiculos_mes
				FROM vehiculo
				WHERE MONTH(Fecha) = MONTH(CURDATE()) 
				AND YEAR(Fecha) = YEAR(CURDATE())";
		return ejecutarConsulta($sql);
	}

	public function vehiculos_activos()
	{
		$sql = "SELECT COUNT(*) AS activos 
				FROM vehiculo 
				WHERE Estado = 1";
		return ejecutarConsulta($sql);
	}

	public function vehiculos_inactivos()
	{
		$sql = "SELECT COUNT(*) AS inactivos 
				FROM vehiculo 
				WHERE Estado = 0";
		return ejecutarConsulta($sql);
	}

	public function vehiculos_con_servicios_pendientes()
	{
		$sql = "SELECT COUNT(DISTINCT Idvehiculo) AS pendientes
				FROM servicio
				WHERE Estado = 'En Proceso'";
		return ejecutarConsulta($sql);
	}







}

?>