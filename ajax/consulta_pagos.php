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
if ($_SESSION['ConsultaS']==1)
{
require_once "../modelos/Consulta_pagos.php";

$consulta=new Consulta_pago();


switch ($_GET["op"]){



	case 'listar':

		$fecha_inicio=$_REQUEST["fecha_inicio"];
		$fecha_fin=$_REQUEST["fecha_fin"];
		$Idservicio=$_REQUEST["Idservicio"]; 

        $rspta=$consulta->listar($fecha_inicio,$fecha_fin,$Idservicio);
        //vamos a declarar un array
        //donde $data alamacenaraa todos los registros que quiero listar
        $data= Array();
        //va a recorrer todos los registros uno a uno de forma independiente con $reg
        while($reg=$rspta->fetch_object())
        { 
    
          $data[] = array(
            "0" => $reg->FechaPago,
            "1" => $reg->Tipo_comprobante . " No: " . $reg->Num_comprobante,
            "2" => $reg->Acuenta,   
            "3" => $reg->Monto,     
            "4" => $reg->Cambio,
            "5" => $reg->Saldo,
            "6" => $reg->Metodo_pago,
            "7" =>  ($reg->Estado == 'En proceso') ? '<span class="badge badge-primary fs-6 px-3 py-2"">En proceso</span>' :
            (($reg->Estado == 'Reparado') ? '<span class="badge badge-success fs-6 px-3 py-2"">Reparado</span>' :
            '<span class="badge badge-danger fs-6 px-3 py-2"">Anulado</span>')
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

        case "selectServicio":
          require_once "../modelos/Servicio.php";
          $Servicio = new Servicio();
          $rspta = $Servicio->selectServicio();
          while ($reg = $rspta->fetch_object())
          {
            echo '<option value=' . $reg->Idservicio . '>' . $reg->Tipo_comprobante ." N°".$reg->Num_comprobante." - ".$reg->Vehiculo.'</option>';
          }
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