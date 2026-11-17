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


require_once "../modelos/Item_diagnostico.php";
 
$item=new Item_diagnostico();

$Iditem_diagnostico=isset($_POST["Iditem_diagnostico"])? limpiarCadena($_POST["Iditem_diagnostico"]):"";
$Nombre=isset($_POST["Nombre"])? limpiarCadena($_POST["Nombre"]):"";
$Descripcion=isset($_POST["Descripcion"])? limpiarCadena($_POST["Descripcion"]):"";
$Precio=isset($_POST["Precio"])? limpiarCadena($_POST["Precio"]):"";

//segun la paeticion (operacion) que realize mediante el metodo GEST
//devolvera un valor mediante la tecnologia AJAX, para eso utilizaremos SWITCH
switch ($_GET["op"]){
	case 'guardaryeditar':

//empty -> esta vacia, $respta -> variable repuesta
		if (empty($Iditem_diagnostico)){
			$rspta=$item->verificarDuplicidad($Nombre);

			if($rspta->num_rows > 0) {
				echo "Este Item ya fue Registrado";
			} else {
				$rspta=$item->insertar($Nombre,$Descripcion,$Precio);
				echo $rspta ? "Item registrado" : "Item no se pudo registrar";
			}
	
		}
		else {
			$rspta=$item->editar($Iditem_diagnostico,$Nombre,$Descripcion,$Precio);
			echo $rspta ? "Item actualizado" : "Item no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$item->desactivar($Iditem_diagnostico);
 		echo $rspta ? "Item Desactivado" : "Item no se puede desactivar";
 		
	break;

	case 'activar':
		$rspta=$item->activar($Iditem_diagnostico);
 		echo $rspta ? "Item activado" : "Item no se puede activar";

	break;

	case 'mostrar':
		$rspta=$item->mostrar($Iditem_diagnostico);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$item->listar();
 		//Vamos a declarar un array
		//donde $data almacenara todos los registros que voy a listar
 		$data= Array();
		//va a recogrer todos los registros uno a uno en forma independiente con $reg
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
				"0" => ($reg->Estado) ?
				'<button class="btn btn-warning btn-sm" data-toggle="tooltip" onclick="mostrar(' . $reg->Iditem_diagnostico . ')"><i class="feather icon-edit-2"></i>
				</button>' .
				' <button class="btn btn-danger btn-sm" onclick="desactivar(' . $reg->Iditem_diagnostico . ')"><i class="feather icon-x"></i></button>' :
				'<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->Iditem_diagnostico . ')"><i class="feather icon-edit-2"></i></button>' .
				' <button class="btn btn-primary btn-sm" onclick="activar(' . $reg->Iditem_diagnostico . ')"><i class="feather icon-check"></i></button>',
			

 				"1"=>$reg->Nombre,
 				"2"=>$reg->Descripcion,
 				"3"=>$reg->Precio,
 				"4"=>($reg->Estado)?'<span class="badge badge-success">Activado</span>':
 				'<span class="badge badge-danger">Desactivado</span>'
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