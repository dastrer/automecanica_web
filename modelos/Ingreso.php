<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Ingreso
{
	//Implementamos nuestro constructor
	public function __construct() 
	{
 
	}   
	//Implementamos un método para insertar registros
	public function insertar($Fecha,$Tipocomprobante,$Numero,$Preciototal,$Idproveedor,$Idusuario,$Precio_compra,$Precio_venta,$Cantidad_ingreso,$Idarticulo)
	{
		
		$sql="INSERT INTO ingreso(Fecha,Tipocomprobante,Numero,Preciototal,Idproveedor,Idusuario,Estado)
		VALUES ('$Fecha','$Tipocomprobante','$Numero','$Preciototal','$Idproveedor','$Idusuario','Aceptado')";	
		$Idingresonew = ejecutarConsulta_retornarID($sql);
				
		$num_elementos = 0;
		$sw = true;
	
		while ($num_elementos < count($Idarticulo)) {
				//INSERTANDO EN LA TABLA DETALLE INGRESO
				$sql_detalle="INSERT INTO articulo_ingreso(Precio_compra,Precio_venta,Cantidad_ingreso,Idarticulo,Idingreso) 
				VALUES ('$Precio_compra[$num_elementos]','$Precio_venta[$num_elementos]','$Cantidad_ingreso[$num_elementos]','$Idarticulo[$num_elementos]','$Idingresonew')";
				ejecutarConsulta($sql_detalle) or $sw = false;
				$num_elementos=$num_elementos + 1;
		}	
			
		return $sw;
		} 
	
   
	//Implementamos un método para anular
	public function anular($Idingreso)
	{
		$consultaIngreso = "SELECT pa.Idarticulo, pa.Cantidad_ingreso 
							FROM articulo_ingreso pa
							WHERE pa.Idingreso = '$Idingreso'";
		$resultados = ejecutarConsulta($consultaIngreso);
	
		$datosIngreso = array();
		while ($row = $resultados->fetch_assoc()) {
			$datosIngreso[] = $row;
		}
	
		$sqlAnular = "UPDATE ingreso SET Estado = 'Anulado' WHERE Idingreso = '$Idingreso'";
		ejecutarConsulta($sqlAnular);
		foreach ($datosIngreso as $ingreso) {
			$Idarticulo = $ingreso['Idarticulo'];
			$Cantidad_ingreso = $ingreso['Cantidad_ingreso'];
	
			$sqlRestaurarStock = "UPDATE articulo 
								  SET Cantidad = Cantidad - $Cantidad_ingreso 
								  WHERE Idarticulo = '$Idarticulo'";
			ejecutarConsulta($sqlRestaurarStock);
		}
	
		return true;
	}
	

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($Idingreso)
	{
		$sql="SELECT * FROM ingreso i
		WHERE i.Idingreso='$Idingreso'";
		return ejecutarConsultaSimpleFila($sql);
	}
 
	public function listarDetalle($Idingreso)
	{
		$sql="SELECT pa.Idingreso,pa.Idarticulo,a.Nombre,pa.Precio_compra,pa.Precio_venta,pa.Cantidad_ingreso 
		FROM articulo_ingreso pa
        INNER JOIN articulo a ON pa.Idarticulo=a.Idarticulo
		WHERE pa.Idingreso='$Idingreso'";
		return ejecutarConsulta($sql);
	} 

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT i.*,p.Nombres as Usuario,p1.Nombres AS Proveedor
		FROM ingreso i 
		JOIN usuario u ON i.Idusuario=u.Idusuario 
        JOIN persona p on u.Idpersona=p.Idpersona
		JOIN proveedor pr ON i.Idproveedor=pr.Idproveedor
        JOIN persona p1 on pr.Idpersona=p1.Idpersona
        ORDER BY i.Idingreso desc";
		return ejecutarConsulta($sql);		
	}
	
	public function ingresoCabecera($Idingreso)
	{
		$sql="SELECT i.*,p.Nombres,p1.Nombres as Usuario,p.Num_documento,p.Tipo_documento,
		p.Telefono,pr.Nombre_empresa,p.Direccion
		FROM ingreso i
		JOIN proveedor pr ON i.Idproveedor=pr.Idproveedor
        JOIN persona p on pr.Idpersona=p.Idpersona
		JOIN usuario u ON i.Idusuario=u.Idusuario
        JOIN persona p1 on u.Idpersona=p1.Idpersona
		WHERE i.Idingreso='$Idingreso'"; 
		return ejecutarConsulta($sql);
	}

	public function ingresoDetalle($Idingreso)
	{
		$sql="SELECT ai.Idingreso,ai.Idarticulo,a.Codigo,a.Nombre,ai.Cantidad_ingreso,ai.Precio_compra,ai.Precio_venta,
        ROUND(ai.Precio_compra*ai.Cantidad_ingreso,0) AS Subtotal 
		FROM articulo_ingreso ai
        INNER JOIN articulo a ON ai.Idarticulo=a.Idarticulo
		WHERE ai.Idingreso='$Idingreso'";
		return ejecutarConsulta($sql);
	} 
}

?>