<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Categoria
{
	//Implementamos nuestro constructor 
	public function __construct()
	{

	} 
 
	//Implementamos un método para insertar registros
	public function insertar($Nombre,$Descripcion)
	{
		//enviamos valores a los campos
		//que valores enviamos los que estamos recibiendo aqui abajo
		$sql="INSERT INTO categoria  (Nombre,Descripcion,Estado)
		VALUES ('$Nombre','$Descripcion','1')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	//va a recibir 3 parametros como los que estan arriba nombre y descripcion
	//pero esta vez recibira el id_categoria, por que ya esta creada un id_categoria
	public function editar($Idcategoria,$Nombre,$Descripcion)
	{
		$sql="UPDATE categoria  SET Nombre='$Nombre',Descripcion='$Descripcion'
		WHERE Idcategoria='$Idcategoria'";
		//llamamos al metodo ejecutarConsulta
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($Idcategoria)
	{
		$sql="UPDATE categoria SET Estado='0' WHERE Idcategoria='$Idcategoria'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($Idcategoria)
	{
		$sql="UPDATE categoria SET Estado='1' WHERE Idcategoria='$Idcategoria'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($Idcategoria)
	{
		$sql="SELECT * FROM categoria WHERE Idcategoria='$Idcategoria'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM categoria";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros y mostrar en el select
	public function selectCategoria()
	{
		$sql="SELECT * FROM categoria WHERE Estado=1";
		return ejecutarConsulta($sql);		
	}

	public function selectCategoriamostrar()
	{
		$sql="SELECT * FROM categoria  WHERE Estado=1 ";
		return ejecutarConsulta($sql);		
	}

	// implementar un metodo para verificar si los registros fueron ingresados
	public function verificarDuplicidad($Nombre)
	{
	   $sql="SELECT * FROM categoria WHERE Nombre='$Nombre'";
	   return ejecutarConsulta($sql);  
	}
}
?>