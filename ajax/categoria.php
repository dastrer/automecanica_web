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

//incluye solo una vez el modelo llamado Categoria.php
require_once "../modelos/Categoria.php";
 
//creamos un objeto llamado categoria
$categoria=new Categoria();

$Idcategoria=isset($_POST["Idcategoria"])? limpiarCadena($_POST["Idcategoria"]):"";
$Nombre=isset($_POST["Nombre"])? limpiarCadena($_POST["Nombre"]):"";
$Descripcion=isset($_POST["Descripcion"])? limpiarCadena($_POST["Descripcion"]):"";

//segun la paeticion (operacion) que realize mediante el metodo GEST
//devolvera un valor mediante la tecnologia AJAX, para eso utilizaremos SWITCH
switch ($_GET["op"]){
	case 'guardaryeditar':

//empty -> esta vacia, $respta -> variable repuesta
		if (empty($Idcategoria)){
			$rspta=$categoria->verificarDuplicidad($Nombre);

			if($rspta->num_rows > 0) {
				echo "Esta Categoria ya fue Registrada";
			} else {
				$rspta=$categoria->insertar($Nombre,$Descripcion);
				echo $rspta ? "Categoría registrada" : "Categoría no se pudo registrar";
			}
	
		}
		else {
			$rspta=$categoria->editar($Idcategoria,$Nombre,$Descripcion);
			echo $rspta ? "Categoría actualizada" : "Categoría no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$categoria->desactivar($Idcategoria);
 		echo $rspta ? "Categoría Desactivada" : "Categoría no se puede desactivar";
 		
	break;

	case 'activar':
		$rspta=$categoria->activar($Idcategoria);
 		echo $rspta ? "Categoría activada" : "Categoría no se puede activar";

	break;

	case 'mostrar':
		$rspta=$categoria->mostrar($Idcategoria);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$categoria->listar();
 		//Vamos a declarar un array
		//donde $data almacenara todos los registros que voy a listar
 		$data= Array();
		//va a recogrer todos los registros uno a uno en forma independiente con $reg
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
				"0" => ($reg->Estado) ?
				'<button class="btn btn-warning btn-sm" data-toggle="tooltip" onclick="mostrar(' . $reg->Idcategoria . ')"><i class="feather icon-edit-2"></i>
				</button>' .
				' <button class="btn btn-danger btn-sm" onclick="desactivar(' . $reg->Idcategoria . ')"><i class="feather icon-x"></i></button>' :
				'<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->Idcategoria . ')"><i class="feather icon-edit-2"></i></button>' .
				' <button class="btn btn-primary btn-sm" onclick="activar(' . $reg->Idcategoria . ')"><i class="feather icon-check"></i></button>',
			

 				"1"=>$reg->Nombre,
 				"2"=>$reg->Descripcion,
 				"3"=>($reg->Estado)?'<span class="badge badge-success">Activado</span>':
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