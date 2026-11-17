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
require_once "../modelos/Consulta_servicio_art.php";

$consulta=new Consulta_servicio_art();
 
switch ($_GET["op"]){


	case 'listar':

		$fecha_inicio=$_REQUEST["fecha_inicio"];
		$fecha_fin=$_REQUEST["fecha_fin"]; 
		$Idarticulo=$_REQUEST["Idarticulo"]; 

        $rspta=$consulta->listar($fecha_inicio,$fecha_fin,$Idarticulo);
        //vamos a declarar un array
        //donde $data alamacenaraa todos los registros que quiero listar
        $data= Array();
        //va a recorrer todos los registros uno a uno de forma independiente con $reg
        while($reg=$rspta->fetch_object())
        {
			$data[]=array(
  
        "0"=>$reg->Fecha,
				"1"=>$reg->Placa,
				"2"=>$reg->Tipo_comprobante . " No: " . $reg->Num_comprobante,
 				"3"=>$reg->Articulo,
 				"4"=>$reg->Cantidad_venta, 
 				"5"=>$reg->Precio_compra,
 				"6"=>$reg->Precio_venta,
 				"7"=>$reg->Utilidad,
 				"8"=>$reg->Subtotal,  
 				"9"=>$reg->Costo_Total, 
 				"10"=>$reg->Utilidad_Total,
 				"11"=>$reg->Descuento
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
        case "selectArticulo":
          require_once "../modelos/Articulo.php";
          $articulo = new Articulo();
          $rspta = $articulo->selectArticulo();
      
          while ($reg = $rspta->fetch_object())
          {
            echo '<option value=' . $reg->Idarticulo . '>' . $reg->Nombre .'</option>';
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