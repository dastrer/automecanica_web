<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Articulo

{ 
	//Implementamos nuestro constructor
	public function __construct()
	{ 

	}  
  
	//Implementamos un método para insertar registros
	public function insertar($Nombre,$Descripcion,$Marca,$Idunidadmedida,$Cantidad,$Codigo,$Idcategoria,$Imagen)
	{
		 
		$sql="INSERT INTO articulo(Nombre,Descripcion,Marca,Idunidadmedida,Cantidad,Codigo,Idcategoria,Imagen,Estado	)
		VALUES ('$Nombre','$Descripcion','$Marca','$Idunidadmedida','$Cantidad','$Codigo','$Idcategoria','$Imagen','1')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($Idarticulo,$Nombre,$Descripcion,$Marca,$Idunidadmedida,$Cantidad,$Codigo,$Idcategoria,$Imagen)
	{
		$sql="UPDATE articulo SET Nombre='$Nombre',Descripcion='$Descripcion',Marca='$Marca',Idunidadmedida='$Idunidadmedida',Cantidad='$Cantidad',Codigo='$Codigo',Idcategoria='$Idcategoria',Imagen='$Imagen'
		WHERE Idarticulo='$Idarticulo'";
		return ejecutarConsulta($sql);
	}

   //Implementamos un metodo para desactivar 
   public function desactivar($Idarticulo)
   {
	$sql="UPDATE articulo SET Estado='0' WHERE Idarticulo='$Idarticulo'";
   return ejecutarConsulta($sql);
   }

   //Implemantamos un metodo para activar 
   public function activar($Idarticulo)
   {
	$sql="UPDATE articulo SET Estado='1' WHERE Idarticulo='$Idarticulo'";
	return ejecutarConsulta($sql);
   }

   public function verificarDuplicidad($Codigo)
   {
	   $sql="SELECT * FROM articulo WHERE Codigo='$Codigo'";
	   return ejecutarConsulta($sql);  
   }
   
    //Implementar un método para mostrar los datos de un registro a mostrar
	public function mostrar($Idarticulo) 
	{
		$sql="SELECT 
			a.*,ca.Nombre AS Categoria,ua.Unidadmedida
			FROM articulo a
			INNER JOIN categoria ca ON a.Idcategoria = ca.Idcategoria
			INNER JOIN unidadmedida ua ON a.Idunidadmedida = ua.Idunidadmedida
			WHERE a.Idarticulo = '$Idarticulo'";
		return ejecutarConsultaSimpleFila($sql); 
	}
	
	public function listar($Idcategoria2, $Stock)
	{
		if($Idcategoria2 == "Todos" && $Stock == "Todos")
		{
			$sql = "SELECT a.*, ca.Nombre AS Categoria,ua.Unidadmedida
					FROM articulo a
					INNER JOIN categoria ca ON a.Idcategoria = ca.Idcategoria
					INNER JOIN unidadmedida ua ON a.Idunidadmedida = ua.Idunidadmedida";
			return ejecutarConsulta($sql);
		}
		else if($Idcategoria2 == "Todos" && $Stock == "Bajo")
		{
			$sql = "SELECT a.*, ca.Nombre AS Categoria,ua.Unidadmedida
					FROM articulo a
					INNER JOIN categoria ca ON a.Idcategoria = ca.Idcategoria
					INNER JOIN unidadmedida ua ON a.Idunidadmedida = ua.Idunidadmedida
				    WHERE a.Cantidad <= 10"; 
			return ejecutarConsulta($sql);
		}
		else
		{ 
			$sql = "SELECT a.*, ca.Nombre AS Categoria,ua.Unidadmedida
					FROM articulo a
					INNER JOIN categoria ca ON a.Idcategoria = ca.Idcategoria
					INNER JOIN unidadmedida ua ON a.Idunidadmedida = ua.Idunidadmedida
				    WHERE ca.Idcategoria = '$Idcategoria2'";
		    return ejecutarConsulta($sql);
		}
		
	}
	
	


	//Implementar un método para listar los registros productos
public function listarActivos()
{
	$sql = "SELECT a.Idarticulo,a.Nombre,a.Descripcion,ua.Unidadmedida,a.Codigo,a.Imagen,
				a.Cantidad,c.Nombre AS Categoria,ai.Precio_venta,ai.Precio_compra
			FROM articulo a
			INNER JOIN categoria c ON a.Idcategoria = c.Idcategoria
            INNER JOIN unidadmedida ua ON a.Idunidadmedida = ua.Idunidadmedida
			LEFT JOIN articulo_ingreso ai ON ai.Idarticulo_ingreso = (SELECT MAX(ai2.Idarticulo_ingreso)FROM articulo_ingreso ai2 WHERE ai2.Idarticulo = a.Idarticulo)
			WHERE a.Estado = 1";
	return ejecutarConsulta($sql);		
}



	//Implementar un método para listar los registros activos
	public function selectArticulo()
	{
		$sql="SELECT * FROM articulo 
			 WHERE Estado=1"; 
		return ejecutarConsulta($sql);		
	}


	public function contararticulo()
	{
		$sql="SELECT COUNT(DISTINCT ca.Idcategoria) AS total
			FROM categoria ca
			WHERE ca.Estado = 1";
		return ejecutarConsulta($sql);
	}
	public function contararticulo2() 
	{
		$sql="SELECT COUNT(DISTINCT a.Idarticulo) AS totalsb
		FROM articulo a
		WHERE a.Estado = 1 
		AND a.Cantidad <= 10";
		return ejecutarConsulta($sql);
	}
	public function contararticulo3()
	{
		$sql="SELECT IFNULL (SUM(a.Cantidad),0) AS totalstk
		FROM articulo a
		WHERE a.Estado = 1 ";
		return ejecutarConsulta($sql);
	}
 
	public function contararticulo4()
	{
		$sql="SELECT COUNT(DISTINCT a.Idarticulo) AS totald
			FROM articulo a
			WHERE a.Estado = 0 ";
		return ejecutarConsulta($sql);
	}

	public function alerta() 
	{
		$sql = "SELECT a.Idarticulo, a.Nombre, a.Cantidad
				FROM articulo a
				WHERE a.Estado = 1 AND a.Cantidad <= 10 
				LIMIT 8";
		return ejecutarConsulta($sql);
	}


			 public function contarRegistros() {
				$sql = "SELECT COUNT(*) as total FROM articulo WHERE Estado = 1";
				$resultado = ejecutarConsulta($sql);
				$fila = $resultado->fetch_assoc(); 
				return $fila['total']; 
			}
			
			public function listarWeb2($start_from, $results_per_page) {
				$sql = "SELECT a.Idarticulo,a.Nombre,a.Descripcion,ua.Unidadmedida,a.Codigo,a.Imagen,a.Marca,
				IFNULL(a.Cantidad, 0) AS Cantidad,ai.Precio_venta
			FROM articulo a
            INNER JOIN unidadmedida ua ON a.Idunidadmedida = ua.Idunidadmedida
			LEFT JOIN articulo_ingreso ai ON ai.Idarticulo_ingreso = (SELECT MAX(ai2.Idarticulo_ingreso)FROM articulo_ingreso ai2 WHERE ai2.Idarticulo = a.Idarticulo)
			WHERE a.Estado = 1 LIMIT $start_from, $results_per_page";
				return ejecutarConsulta($sql);
			}


}
?>