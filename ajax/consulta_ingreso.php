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
if ($_SESSION['ConsultaC']==1)
{
require_once "../modelos/Consulta_ingreso.php";

$consulta=new Consulta_ingreso();

switch ($_GET["op"]){



	case 'listar':

        $fecha_inicio=$_REQUEST["fecha_inicio"];
        $fecha_fin=$_REQUEST["fecha_fin"];

        $rspta=$consulta->listar($fecha_inicio,$fecha_fin);
        //vamos a declarar un array
        //donde $data alamacenaraa todos los registros que quiero listar
        $data= Array();
        //va a recorrer todos los registros uno a uno de forma independiente con $reg
        while($reg=$rspta->fetch_object())
        { 
			$data[]=array(


				"0"=>'<a target="_blank" href="../reportes/exIngreso.php?id='.$reg->Idingreso.'"> <button class="btn btn-primary btn-sm"><i class="fas fa-file-pdf"></i></button></a>',                                                         
 			  "1"=>$reg->Fecha,
 				"2"=>$reg->Tipocomprobante.'  '."Nro: ".$reg->Numero,
 				"3"=>$reg->Proveedor." - ".$reg->Nombre_empresa,
 				"4"=>$reg->Usuario,
 				"5"=>$reg->Preciototal, 
 				);
 
        }
        $results= array
        (
            "sEcho"=>1, //informacion para el data tables
            "iTotalRecords"=>count($data),//enviamaos el total de registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos total registros a visualizar
            "aaData"=>$data);

        //devolvemos los resultados mediante json
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