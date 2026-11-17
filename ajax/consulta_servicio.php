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
require_once "../modelos/Consulta_servicio.php";

$consulta=new Consulta_servicio();


switch ($_GET["op"]){



	case 'listar':

		$fecha_inicio=$_REQUEST["fecha_inicio"];
		$fecha_fin=$_REQUEST["fecha_fin"];
		$Idvehiculo=$_REQUEST["Idvehiculo"]; 

        $rspta=$consulta->listar($fecha_inicio,$fecha_fin,$Idvehiculo);
        //vamos a declarar un array
        //donde $data alamacenaraa todos los registros que quiero listar
        $data= Array();
        //va a recorrer todos los registros uno a uno de forma independiente con $reg
        while($reg=$rspta->fetch_object())
        { 
    
          $data[] = array(
				    "0"=>'<a target="_blank" href="../reportes/exRecibo.php?id='.$reg->Idservicio.'"> <button class="btn btn-primary btn-sm"><i class="fas fa-file-pdf"></i></button></a>',                                                         
            "1" => $reg->Fecha,
            "2" => $reg->Tipo_comprobante . " No: " . $reg->Num_comprobante,
            "3" => $reg->Placa,
            "4" => $reg->Cliente,
            "5" => $reg->Monto,     
            "6" => $reg->Cambio,
            "7" => $reg->Total,
            "8" =>  ($reg->Estado == 'En proceso') ? '<span class="badge badge-primary fs-6 px-3 py-2"">En proceso</span>' :
              (($reg->Estado == 'Reparado') ? '<span class="badge badge-success fs-6 px-3 py-2"">Reparado</span>' :
              '<span class="badge badge-danger fs-6 px-3 py-2"">Anulado</span>'),
            "9" => $reg->Metodo_pago,
            "10" => $reg->Usuario,
            "11" => $reg->Fecha_entrega,
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

        case "selectVehiculo":
          require_once "../modelos/Vehiculo.php";
          $vehiculo = new Vehiculo();
          $rspta = $vehiculo->selectVehiculo();

          while ($reg = $rspta->fetch_object())
          {
            echo '<option value=' . $reg->Idvehiculo . '>' . $reg->Placa ." - ".$reg->Marca.'</option>';
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