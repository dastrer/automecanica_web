<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Unidad
{
	//Implementamos nuestro constructor 
	public function __construct()
	{

	} 
 
	//Implementamos un método para insertar registros
	public function insertar($Unidadmedida,$Descripcion)
	{
		//enviamos valores a los campos
		//que valores enviamos los que estamos recibiendo aqui abajo
		$sql="INSERT INTO unidadmedida  (Unidadmedida,Descripcion,Estado)
		VALUES ('$Unidadmedida','$Descripcion','1')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	//va a recibir 3 parametros como los que estan arriba nombre y descripcion
	//pero esta vez recibira el id_categoria, por que ya esta creada un id_categoria
	public function editar($Idunidadmedida,$Unidadmedida,$Descripcion)
	{
		$sql="UPDATE unidadmedida  SET Unidadmedida='$Unidadmedida',Descripcion='$Descripcion'
		WHERE Idunidadmedida='$Idunidadmedida'";
		//llamamos al metodo ejecutarConsulta
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($Idunidadmedida)
	{
		$sql="UPDATE unidadmedida SET Estado='0' WHERE Idunidadmedida='$Idunidadmedida'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($Idunidadmedida)
	{
		$sql="UPDATE unidadmedida SET Estado='1' WHERE Idunidadmedida='$Idunidadmedida'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($Idunidadmedida)
	{
		$sql="SELECT * FROM unidadmedida WHERE Idunidadmedida='$Idunidadmedida'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM unidadmedida";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros y mostrar en el select
	public function selectUnidad()
	{
		$sql="SELECT * FROM unidadmedida WHERE Estado=1";
		return ejecutarConsulta($sql);		
	}

	// implementar un metodo para verificar si los registros fueron ingresados
	public function verificarDuplicidad($Unidadmedida)
	{
	   $sql="SELECT * FROM unidadmedida WHERE Unidadmedida='$Unidadmedida'";
	   return ejecutarConsulta($sql);  
	}
}
?>