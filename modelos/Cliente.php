<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Cliente
{
	//Implementamos nuestro constructor 
	public function __construct()
	{
 
	} 
 	//Implementamos un método para insertar registros
	 public function insertar($Tipopersona,$Nombres,$Num_documento,$Tipo_documento,$Telefono,$Direccion)
 { 
	 // Consulta de inserción en la tabla persona
	 $sql = "INSERT INTO persona (Tipopersona,Nombres,Num_documento,Tipo_documento,Telefono,Direccion,Estado)
			 VALUES ('$Tipopersona','$Nombres','$Num_documento','$Tipo_documento','$Telefono','$Direccion',1)";
 
	 return  ejecutarConsulta($sql);
 }
 
 
	
	public function editar( $Idpersona,$Tipopersona,$Nombres,$Num_documento,$Tipo_documento,$Telefono,$Direccion)
	{ 
		// Actualización en la tabla persona
		$sql = "UPDATE persona SET Tipopersona='$Tipopersona',Nombres='$Nombres',Num_documento='$Num_documento',Tipo_documento='$Tipo_documento',Telefono='$Telefono',Direccion='$Direccion'
				WHERE Idpersona = '$Idpersona'";
		return  ejecutarConsulta($sql);
	}


		//Implementamos un método para desactivar categorías
		public function desactivar($Idpersona)
		{
			$sql = "UPDATE persona
					SET Estado = '0'
					WHERE Idpersona = '$Idpersona'";
			return ejecutarConsulta($sql);
		}
		//Implementamos un método para activar categorías
		public function activar($Idpersona)
		{
			$sql = "UPDATE persona
			SET Estado = '1'
			WHERE Idpersona = '$Idpersona'";
	        return ejecutarConsulta($sql);
		}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($Idpersona)
	{
		$sql="SELECT * FROM persona  WHERE Idpersona ='$Idpersona'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT * FROM persona  WHERE Tipopersona='Cliente'
		order by Idpersona desc"; 
		return ejecutarConsulta($sql); 
	}

	public function selectCliente()
	{
		$sql = "SELECT * FROM persona  WHERE Tipopersona='Cliente' AND Estado=1"; 
		return ejecutarConsulta($sql); 
	}



	public function verificarDuplicidad($Num_documento)
		{
		   $sql="SELECT * FROM persona
		   WHERE   Num_documento='$Num_documento'";
		   return ejecutarConsulta($sql);  
		}
		


}

?>