<?php
// INCLUIMOS INICIALMENTE LA CONEXION ALA BASE DE DATOS
require "..\config\Conexion.php";

 class Mensaje
 {
    //implemantamos nuevo constructor
    public function __construct()
    {
    
    } 
//Implementamos un método para insertar registros
public function insertar($Fecha,$Nombre, $Telefono, $Email, $Detalle)
{
   $sql = "INSERT INTO mensaje(Fecha, Nombre, Telefono, Email, Detalle, Estado)
   VALUES ('$Fecha','$Nombre', '$Telefono', '$Email', '$Detalle','1')";
    return ejecutarConsulta($sql);
   

}
    //Implementamos un metodo para desactivar categorias
    public function desactivar($Idmensaje)
    {
     $sql="UPDATE mensaje SET Estado='0' WHERE Idmensaje='$Idmensaje'";
    return ejecutarConsulta($sql);
    }

    //Implemantamos un metodo para activar categorias
    public function activar($Idmensaje)
    {
     $sql="UPDATE mensaje SET Estado='1' WHERE Idmensaje='$Idmensaje'";
     return ejecutarConsulta($sql);
    }


    // implementar un metodo para extraer registros
    public function listar()
    {
    $sql="SELECT * FROM mensaje";
    return ejecutarConsulta($sql);
    }
	// implementar un metodo para listar notificaciones
	// Este método devuelve las solicitudes pendientes para notificaciones
	public function listarnotificacion()
	{
	$sql="SELECT * FROM mensaje WHERE Estado='1'order by Idmensaje DESC LIMIT 6";
	return ejecutarConsulta($sql);
	}

    
   }
?>
