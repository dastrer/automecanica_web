<?php 
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
} 
if (!isset($_SESSION["nombre"]))
{  
  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
}
else 
{
//Validamos el acceso solo al usuario logueado y autorizado.
if ($_SESSION['Servicio']==1)
{
//incluye solo una vez el modelo llamado Categoria.php
require_once "../modelos/Mensaje.php";
$mensaje=new Mensaje();
$Idmensaje=isset($_POST["Idmensaje"])? limpiarCadena($_POST["Idmensaje"]):"";
//segun la paeticion (operacion) que realize mediante el metodo GEST
//devolvera un valor mediante la tecnologia AJAX, para eso utilizaremos SWITCH
switch ($_GET["op"]){

	case 'desactivar':
		$rspta=$mensaje->desactivar($Idmensaje);
 		echo $rspta ? "Mensaje Respondido" : "Mensaje no se pudo Responder";
 		
	break;

	case 'activar':
		$rspta=$mensaje->activar($Idmensaje);
 		echo $rspta ? "Mensaje Pendiente" : "Mensaje no se pudo poner en Pendiente";
	break;





	case 'listar':
		$rspta=$mensaje->listar();
 		//Vamos a declarar un array
		//donde $data almacenara todos los registros que voy a listar
 		$data= Array();
		//va a recogrer todos los registros uno a uno en forma independiente con $reg
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
				"0"=>($reg->Estado) ? 
				'<button class="btn btn-success btn-sm" onclick="mensaje('.$reg->Telefono.')"><i class="fab fa-whatsapp"></i></button> '.
				'<button class="btn btn-primary btn-sm" onclick="desactivar('.$reg->Idmensaje.')"><i class="fa fa-envelope"></i></button> ' : 
				'<button class="btn btn-danger btn-sm" onclick="activar('.$reg->Idmensaje.')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->Fecha,
				"2"=>$reg->Nombre,
				"3"=>$reg->Email,  
				"4"=>$reg->Telefono,
 				"5"=>$reg->Detalle,
 				"6"=>($reg->Estado)?'<span class="badge badge-warning">Pendiente</span>':
 				'<span class="badge badge-success">Respondida</span>'
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
		//devolvemos los resultados mediante JSON
 		echo json_encode($results);

	break;

}


//Fin de las validaciones de acceso
}
else
{
  require 'noacceso.php';
}
}
ob_end_flush();
?>