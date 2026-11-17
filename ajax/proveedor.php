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
if ($_SESSION['Compra']==1) 
{	

require_once "../modelos/Proveedor.php"; 

$proveedor=new Proveedor();  

$Idproveedor=isset($_POST["Idproveedor"])? limpiarCadena($_POST["Idproveedor"]):"";
$Tipopersona=isset($_POST["Tipopersona"])? limpiarCadena($_POST["Tipopersona"]):"";
$Nombres=isset($_POST["Nombres"])? limpiarCadena($_POST["Nombres"]):"";
$Tipo_documento=isset($_POST["Tipo_documento"])? limpiarCadena($_POST["Tipo_documento"]):"";
$Num_documento=isset($_POST["Num_documento"])? limpiarCadena($_POST["Num_documento"]):"";
$Direccion=isset($_POST["Direccion"])? limpiarCadena($_POST["Direccion"]):"";
$Telefono=isset($_POST["Telefono"])? limpiarCadena($_POST["Telefono"]):"";
$Nombre_empresa=isset($_POST["Nombre_empresa"])? limpiarCadena($_POST["Nombre_empresa"]):"";

 
switch ($_GET["op"]){
	case 'guardaryeditar':
	
		if (empty($Idproveedor)) {
			$rspta=$proveedor->verificarDuplicidad($Num_documento);
			if($rspta->num_rows > 0) {
				echo "Esta Cedula ya fue Registrada";
			} 
			else { 
			$rspta = $proveedor->insertar($Tipopersona,$Nombres,$Num_documento,$Tipo_documento,$Telefono ,$Direccion,$Nombre_empresa);
			echo $rspta ? "Proveedor registrado" : "No se pudieron registrar todos los datos del Proveedor";
			}
		} 
		else {
			$rspta = $proveedor->editar($Idproveedor,$Tipopersona,$Nombres,$Num_documento,$Tipo_documento,$Telefono ,$Direccion,$Nombre_empresa);
			echo $rspta ? "Proveedor actualizado" : "Proveedor no se pudo actualizar"; 
		}
		break;
	

		case 'desactivar':
			
					$rspta=$proveedor->desactivar($Idproveedor);
					 echo $rspta ? "Proveedor Desactivado" : "Proveedor no se puede desactivar";
				
		break;
	

		case 'activar':
			
					$rspta=$proveedor->activar($Idproveedor);
					 echo $rspta ? "Proveedor activado" : "Proveedor no se puede activar";
					
		break;

	case 'mostrar':
		$rspta=$proveedor->mostrar($Idproveedor);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	
	
	case 'listar':
		
		$rspta=$proveedor->listar();
		 //Vamos a declarar un array
		 $data= Array();

		 while ($reg=$rspta->fetch_object()){
			 $data[]=array(
				    "0"=>(($reg->Estado)?
					'<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->Idproveedor . ')"><i class="feather icon-edit-2"></i>
					</button>' .
					' <button class="btn btn-danger btn-sm" onclick="desactivar(' . $reg->Idproveedor . ')">
					<i class="feather icon-x"></i></button>' :
					'<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->Idproveedor . ')"><i class="feather icon-edit-2"></i></button>' .
					' <button class="btn btn-primary btn-sm" onclick="activar(' . $reg->Idproveedor . ')"><i class="feather icon-check"></i></button>'),
					 "1"=>$reg->Nombres,
					 "2"=>$reg->Num_documento."-".$reg->Tipo_documento,
					 "3" =>'<a class="btn btn-sm btn-success" href="https://wa.me/+591' . $reg->Telefono . '" target="_blank" role="button">' .
					 '<i class="fab fa-whatsapp"></i> ' . $reg->Telefono .'</a>',
					 "4"=>$reg->Nombre_empresa,
					 "5"=>($reg->Estado)?'<span class="badge badge-success">Activado</span>':
					 '<span class="badge badge-danger">Desactivado</span>'
				 );
		 }
		 $results = array(
			 "sEcho"=>1, //Información para el datatables
			 "iTotalRecords"=>count($data), //enviamos el total registros al datatable
			 "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
			 "aaData"=>$data);
		 echo json_encode($results);
break;



	}
}
else
{
  require 'noacceso.php';
}
}
ob_end_flush();

?>