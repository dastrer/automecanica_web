<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Proveedor
{
	//Implementamos nuestro constructor 
	public function __construct()
	{
 
	} 
 	//Implementamos un método para insertar registros
	 public function insertar($Tipopersona,$Nombres,$Num_documento,$Tipo_documento,$Telefono ,$Direccion,$Nombre_empresa)
 { 
	 // Consulta de inserción en la tabla persona
	 $sql = "INSERT INTO persona (Tipopersona,Nombres,Num_documento,Tipo_documento,Telefono,Direccion,Estado)
			 VALUES ('$Tipopersona', '$Nombres',  '$Num_documento', '$Tipo_documento', '$Telefono', '$Direccion','1')";
 
	 // Ejecutar la consulta e insertar en la tabla persona
	 $Idpersonanew = ejecutarConsulta_retornarID($sql);
 
	 if ($Idpersonanew) {
		 // Inserción en la tabla estudiante
		 $sql2 = "INSERT INTO proveedor (Nombre_empresa,Idpersona)
				  VALUES ('$Nombre_empresa','$Idpersonanew')";
		 $proveedor = ejecutarConsulta($sql2);

		 return $proveedor;
	 }
 
	 return false;
 }
 
 
	
	public function editar($Idproveedor,$Tipopersona,$Nombres,$Num_documento,$Tipo_documento,$Telefono ,$Direccion,$Nombre_empresa)
	{ 
		// Actualización en la tabla persona
		$sql = "UPDATE persona SET Tipopersona='$Tipopersona',Nombres='$Nombres',Num_documento='$Num_documento',Tipo_documento='$Tipo_documento',Telefono='$Telefono',Direccion='$Direccion' 
				WHERE Idpersona = (SELECT Idpersona FROM proveedor WHERE Idproveedor = '$Idproveedor')";
		
		$resultado1 = ejecutarConsulta($sql);
	
		if ($resultado1) {
			// Actualización en la tabla proveedor
			$sql2 = "UPDATE proveedor SET  Nombre_empresa='$Nombre_empresa' WHERE Idproveedor = '$Idproveedor'";
			ejecutarConsulta($sql2);
		}
	
		return $resultado1;
	}


		//Implementamos un método para desactivar categorías
		public function desactivar($Idproveedor)
		{
			$sql = "UPDATE persona
					SET Estado = '0'
					WHERE Idpersona = (SELECT Idpersona FROM proveedor WHERE Idproveedor = '$Idproveedor')";
			return ejecutarConsulta($sql);
		}
		//Implementamos un método para activar categorías
		public function activar($Idproveedor)
		{
			$sql = "UPDATE persona
			SET Estado = '1'
			WHERE Idpersona = (SELECT Idpersona FROM proveedor WHERE Idproveedor = '$Idproveedor')";
	        return ejecutarConsulta($sql);
		}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($Idproveedor)
	{
		$sql="SELECT p.Idpersona,p.Nombres,p.Tipopersona,p.Num_documento,p.Tipo_documento,p.Telefono,pr.Idproveedor,pr.Nombre_empresa,p.Direccion FROM proveedor pr INNER JOIN persona p on pr.Idpersona=p.Idpersona WHERE pr.Idproveedor = '$Idproveedor'";
		return ejecutarConsultaSimpleFila($sql);
	}
 
	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT p.Idpersona,p.Nombres,p.Tipopersona,p.Num_documento,p.Tipo_documento,p.Telefono,pr.Idproveedor,pr.Nombre_empresa,p.Direccion,p.Estado 
		FROM proveedor pr 
		INNER JOIN persona p on pr.Idpersona=p.Idpersona 
		order by pr.Idproveedor desc"; 
		return ejecutarConsulta($sql); 
	}

	public function listarP()
	{
		$sql = "SELECT pr.Idproveedor,p.Nombres,pr.Nombre_empresa FROM proveedor pr 
		INNER JOIN persona p on pr.Idpersona=p.Idpersona WHERE p.Estado=1"; 
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