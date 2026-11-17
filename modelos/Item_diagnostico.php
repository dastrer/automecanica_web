<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Item_diagnostico
{
	//Implementamos nuestro constructor 
	public function __construct()
	{

	} 
 
	//Implementamos un método para insertar registros
	public function insertar($Nombre,$Descripcion,$Precio)
	{
		//enviamos valores a los campos
		//que valores enviamos los que estamos recibiendo aqui abajo
		$sql="INSERT INTO item_diagnostico  (Nombre,Descripcion,Precio,Estado)
		VALUES ('$Nombre','$Descripcion','$Precio','1')";
		return ejecutarConsulta($sql);
	}

	public function editar($Iditem_diagnostico,$Nombre,$Descripcion,$Precio)
	{
		$sql="UPDATE item_diagnostico  SET Nombre='$Nombre',Descripcion='$Descripcion',Precio='$Precio'
		WHERE Iditem_diagnostico='$Iditem_diagnostico'";
		//llamamos al metodo ejecutarConsulta
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($Iditem_diagnostico)
	{
		$sql="UPDATE item_diagnostico SET Estado='0' WHERE Iditem_diagnostico='$Iditem_diagnostico'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($Iditem_diagnostico)
	{
		$sql="UPDATE item_diagnostico SET Estado='1' WHERE Iditem_diagnostico='$Iditem_diagnostico'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($Iditem_diagnostico)
	{
		$sql="SELECT * FROM item_diagnostico WHERE Iditem_diagnostico='$Iditem_diagnostico'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM item_diagnostico";
		return ejecutarConsulta($sql);		
	}

		public function listarDiagnostico()
	{
		$sql="SELECT * FROM item_diagnostico WHERE Estado=1";
		return ejecutarConsulta($sql);		
	}


	// implementar un metodo para verificar si los registros fueron ingresados
	public function verificarDuplicidad($Nombre)
	{
	   $sql="SELECT * FROM item_diagnostico WHERE Nombre='$Nombre'";
	   return ejecutarConsulta($sql);  
	}
}
?>