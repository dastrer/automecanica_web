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
if ($_SESSION['Almacen']==1) 
{	

//incluye solo una vez el modelo llamado Unidad.php
require_once "../modelos/Unidad.php";
 
//creamos un objeto llamado unidad  Idunidadmedida	Unidadmedida	Descripcion	Estado
$unidad=new Unidad();

$Idunidadmedida=isset($_POST["Idunidadmedida"])? limpiarCadena($_POST["Idunidadmedida"]):"";
$Unidadmedida=isset($_POST["Unidadmedida"])? limpiarCadena($_POST["Unidadmedida"]):"";
$Descripcion=isset($_POST["Descripcion"])? limpiarCadena($_POST["Descripcion"]):"";

//segun la paeticion (operacion) que realize mediante el metodo GEST
//devolvera un valor mediante la tecnologia AJAX, para eso utilizaremos SWITCH
switch ($_GET["op"]){
	case 'guardaryeditar':

//empty -> esta vacia, $respta -> variable repuesta
		if (empty($Idunidadmedida)){
			$rspta=$unidad->verificarDuplicidad($Unidadmedida);

			if($rspta->num_rows > 0) {
				echo "Esta Unidad ya fue Registrada";
			} else {
				$rspta=$unidad->insertar($Unidadmedida,$Descripcion);
				echo $rspta ? "Unidad de medida registrada" : "Unidad de medida no se pudo registrar";
			}
	
		}
		else {
			$rspta=$unidad->editar($Idunidadmedida,$Unidadmedida,$Descripcion);
			echo $rspta ? "Unidad de medida registrada actualizada" : "Unidad de medida  no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$unidad->desactivar($Idunidadmedida);
 		echo $rspta ? "Unidad de medida  Desactivada" : "Unidad de medida  no se puede desactivar";	
	break;

	case 'activar':
		$rspta=$unidad->activar($Idunidadmedida);
 		echo $rspta ? "Unidad de medida  activada" : "Unidad de medida  no se puede activar";
	break;

	case 'mostrar':
		$rspta=$unidad->mostrar($Idunidadmedida);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$unidad->listar();
 		//Vamos a declarar un array
		//donde $data almacenara todos los registros que voy a listar
 		$data= Array();
		//va a recogrer todos los registros uno a uno en forma independiente con $reg
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
						"0" => ($reg->Estado) ?
							'<button class="btn btn-warning btn-sm" data-toggle="tooltip" onclick="mostrar(' . $reg->Idunidadmedida . ')"><i class="feather icon-edit-2"></i>
				            </button>' .
							' <button class="btn btn-danger btn-sm" onclick="desactivar(' . $reg->Idunidadmedida . ')"><i class="feather icon-x"></i></button>' :
							'<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->Idunidadmedida . ')"><i class="feather icon-edit-2"></i></button>' .
							' <button class="btn btn-primary btn-sm" onclick="activar(' . $reg->Idunidadmedida . ')"><i class="feather icon-check"></i></button>',

						"1" => $reg->Unidadmedida,
						"2" => $reg->Descripcion,
						"3" => ($reg->Estado) ? '<span class="badge badge-success">Activado</span>' :
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