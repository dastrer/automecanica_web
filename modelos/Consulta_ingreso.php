<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Consulta_ingreso
{
	//Implementamos nuestro constructor
	public function __construct() 
	{
  
	}
 	//CONTROL DE SERVICIOS 
	 public function listar($fecha_inicio,$fecha_fin) 
	 {
		
		 $sql="SELECT i.*,p.Nombres as Usuario,p1.Nombres AS Proveedor,pr.Nombre_empresa
		FROM ingreso i 
		JOIN usuario u ON i.Idusuario=u.Idusuario 
        JOIN persona p on u.Idpersona=p.Idpersona
		JOIN proveedor pr ON i.Idproveedor=pr.Idproveedor
        JOIN persona p1 on pr.Idpersona=p1.Idpersona
		WHERE i.Estado='Aceptado' AND DATE(i.Fecha)>='$fecha_inicio' AND DATE(i.Fecha)<='$fecha_fin'
        ORDER BY i.Idingreso desc";
			 return ejecutarConsulta($sql);
		 
	 } 



}

?>