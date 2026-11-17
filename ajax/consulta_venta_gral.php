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
if ($_SESSION['ConsultaV']==1)
{
require_once "../modelos/Consulta_venta_gral.php";

$consulta=new Consulta_venta_gral();


switch ($_GET["op"]){



	case 'listar':

		$fecha_inicio=$_REQUEST["fecha_inicio"];
		$fecha_fin=$_REQUEST["fecha_fin"];
		$Idcliente=$_REQUEST["Idcliente"]; 

        $rspta=$consulta->listar($fecha_inicio,$fecha_fin,$Idcliente);
        //vamos a declarar un array
        //donde $data alamacenaraa todos los registros que quiero listar
        $data= Array();
        //va a recorrer todos los registros uno a uno de forma independiente con $reg
        while($reg=$rspta->fetch_object())
        { 
          if($reg->Tipo_comprobante=='Recibo'){
				
            $url='../reportes/exReciboV.php?id=';
          } 
          else{
            $url='../reportes/exTicket.php?id=';
          }
    
          $data[] = array(
            "0" =>  '<a target="_blank" href="'.$url.$reg->Idventa.'"> <button class="btn btn-info btn-sm"><i class="fas fa-file-pdf"></i></button></a>',
            "1" => $reg->Fecha,
            "2" => $reg->Nombres,
            "3" => $reg->Tipo_documento . " " . $reg->Num_documento, 
            "4" => $reg->Tipo_comprobante . " No: " . $reg->Num_comprobante,
            "5" => $reg->Usuario,
            "6" => $reg->Tipo,
            "7" => $reg->Monto,
            "8" => $reg->Cambio,
            "9" => $reg->Total,
            "10"=>($reg->Estado=='Aceptado')?'<span class="badge badge-success">Aceptado</span>':
            '<span class="badge badge-danger">Anulado</span>'
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

        case "selectCliente":
          require_once "../modelos/Cliente.php";
          $cliente = new Cliente();
          $rspta = $cliente->selectCliente(); 
      
          while ($reg = $rspta->fetch_object())
          {
            echo '<option value=' . $reg->Idpersona . '>' . $reg->Nombres .'</option>';
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