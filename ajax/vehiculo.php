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
if ($_SESSION['Vehículos'] == 1)
{
 	  
require_once "../modelos/Vehiculo.php";
$vehiculo=new Vehiculo();

$Idvehiculo=isset($_POST["Idvehiculo"])? limpiarCadena($_POST["Idvehiculo"]):"";
$Idcliente=isset($_POST["Idcliente"])? limpiarCadena($_POST["Idcliente"]):"";
$Fecha=isset($_POST["Fecha"])? limpiarCadena($_POST["Fecha"]):"";
$Placa=isset($_POST["Placa"])? limpiarCadena($_POST["Placa"]):"";
$Marca=isset($_POST["Marca"])? limpiarCadena($_POST["Marca"]):"";
$Color=isset($_POST["Color"])? limpiarCadena($_POST["Color"]):"";
$Observaciones=isset($_POST["Observaciones"])? limpiarCadena($_POST["Observaciones"]):"";
$Tipo=isset($_POST["Tipo"])? limpiarCadena($_POST["Tipo"]):"";
$Imagen=isset($_POST["Imagen"])? limpiarCadena($_POST["Imagen"]):"";
//Para Nuevo cliente
$Tipopersona=isset($_POST["Tipopersona"])? limpiarCadena($_POST["Tipopersona"]):"";
$Nombres=isset($_POST["Nombres"])? limpiarCadena($_POST["Nombres"]):"";
$Tipo_documento=isset($_POST["Tipo_documento"])? limpiarCadena($_POST["Tipo_documento"]):"";
$Num_documento=isset($_POST["Num_documento"])? limpiarCadena($_POST["Num_documento"]):"";
$Telefono=isset($_POST["Telefono"])? limpiarCadena($_POST["Telefono"]):"";
$Direccion=isset($_POST["Direccion"])? limpiarCadena($_POST["Direccion"]):"";

switch ($_GET["op"]){ 
	case 'guardaryeditar':
		// Verifica si se ha subido una nueva imagen
		if (!file_exists($_FILES['Imagen']['tmp_name']) || !is_uploaded_file($_FILES['Imagen']['tmp_name']))
		{
			$Imagen=$_POST["Imagenactual"];
		}
		else 
		{
			$ext = explode(".", $_FILES["Imagen"]["name"]);
			if ($_FILES['Imagen']['type'] == "image/jpg" || $_FILES['Imagen']['type'] == "image/jpeg" || $_FILES['Imagen']['type'] == "image/png")
			{
				$Imagen = round(microtime(true)) . '.' . end($ext);
				move_uploaded_file($_FILES["Imagen"]["tmp_name"], "../files/vehiculo/" . $Imagen); 
			}
		}
		if (empty($Idvehiculo)){
			// Verifica si ya existe Placa
				$rsptaPlaca = $vehiculo->verificarDuplicidadPlaca($Placa);
				if ($rsptaPlaca->num_rows > 0) {
					echo "Esta placa ya fue registrada.";
				} else 
				{
				$rspta=$vehiculo->insertar($Fecha,$Placa,$Marca,$Color,$Observaciones,$Tipo,$Imagen,$Idcliente);
				echo $rspta ? "Vehiculo registrado" : "No se pudieron registrar todos los datos del vehiculo";
				} 
			}
		else {
			$rspta=$vehiculo->editar($Idvehiculo,$Fecha,$Placa,$Marca,$Color,$Observaciones,$Tipo,$Imagen,$Idcliente);
			echo $rspta ? "Vehiculo actualizado" : "Vehiculo  no se pudo actualizar";
		}
	break;

	case 'guardaryeditarNuevoCliente':
		
				// Verifica si se ha subido una nueva imagen
				if (!file_exists($_FILES['Imagen']['tmp_name']) || !is_uploaded_file($_FILES['Imagen']['tmp_name']))
				{
					$Imagen=$_POST["Imagenactual"];
				}
				else 
				{
					$ext = explode(".", $_FILES["Imagen"]["name"]);
					if ($_FILES['Imagen']['type'] == "image/jpg" || $_FILES['Imagen']['type'] == "image/jpeg" || $_FILES['Imagen']['type'] == "image/png")
					{
						$Imagen = round(microtime(true)) . '.' . end($ext);
						move_uploaded_file($_FILES["Imagen"]["tmp_name"], "../files/vehiculo/" . $Imagen); 
					}
				}
			    if (empty($Idvehiculo)) {
					// Verifica si ya existe Num_documento
					$rsptaDoc = $vehiculo->verificarDuplicidad($Num_documento);

					if ($rsptaDoc->num_rows > 0) {
						echo "Este número de documento ya fue registrado.";
					} else {
						// Verifica si ya existe Placa
						$rsptaPlaca = $vehiculo->verificarDuplicidadPlaca($Placa);

						if ($rsptaPlaca->num_rows > 0) {
							echo "Esta placa ya fue registrada.";
						} else {
							// Inserta si todo está bien
							$rspta = $vehiculo->insertarNuevo($Tipopersona, $Nombres, $Num_documento, $Tipo_documento, $Telefono,$Direccion,$Fecha, $Placa, $Marca, $Color, $Observaciones, $Tipo, $Imagen);
							echo $rspta ? "Vehículo registrado" : "No se pudieron registrar todos los datos del vehículo.";
						}
					}
				}

				else 
				{
				$rspta=$vehiculo->editarNuevo($Tipopersona,$Nombres,$Num_documento,$Tipo_documento,$Telefono,$Direccion,$Idvehiculo,$Fecha,$Placa,$Marca,$Color,$Observaciones,$Tipo,$Imagen);
			    echo $rspta ? "Vehiculo actualizado" : "Vehiculo  no se pudo actualizar";

				}
	        
	break;

	case 'desactivar':
		$rspta=$vehiculo->desactivar($Idvehiculo);
 		echo $rspta ? "Vehiculo   Desactivado" : "Vehiculo  no se puede desactivar";	
	break;

	case 'activar':
		$rspta=$vehiculo->activar(Idvehiculo: $Idvehiculo);
 		echo $rspta ? "Vehiculo   activado" : "Vehiculo  no se puede activar";
	break;


	case 'mostrar':
		$rspta=$vehiculo->mostrar($Idvehiculo);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break; 

	case 'listar':
		$rspta = $vehiculo->listar();
		// Vamos a declarar un array
		$data = Array();
		while ($reg = $rspta->fetch_object()) {
			$data[] = array(
				
				"0" => ($reg->Estado) ? 
				'<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->Idvehiculo . ')"><i class="feather icon-edit-2"></i>
				</button>' .
				' <button class="btn btn-danger btn-sm" onclick="desactivar(' . $reg->Idvehiculo . ')"><i class="feather icon-x"></i>
				</button>' :
				'<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->Idvehiculo . ')"><i class="feather icon-edit-2"></i></button>' .
				' <button class="btn btn-primary btn-sm" onclick="activar(' . $reg->Idvehiculo . ')"><i class="feather icon-check"></i></button>',
		
				"1" => $reg->Fecha,
				"2" => $reg->Nombres,
				"3" => $reg->Tipo_documento . " - " . $reg->Num_documento,
				"4" => $reg->Marca,
				"5" => $reg->Placa,
				"6" => "<img src='../files/vehiculo/" . $reg->Imagen . "' height='50px' width='50px' >",
				"7" => ($reg->Estado)
					? '<span class="badge badge-success">Activado</span>'
					: '<span class="badge badge-danger">Desactivado</span>'
			);
		}

		$results = array(
			"sEcho" => 1, // Información para el datatables
			"iTotalRecords" => count($data), // enviamos el total registros al datatable
			"iTotalDisplayRecords" => count($data), // enviamos el total registros a visualizar
			"aaData" => $data
		);
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


	
	case 'listarconsulta':
	
		$rspta_mes = $vehiculo->vehiculos_mes();
		$regm=$rspta_mes->fetch_object();
		$total=$regm->vehiculos_mes;
	  

		$rspta_a = $vehiculo->vehiculos_activos();
		$rega=$rspta_a->fetch_object();
		$totala= $rega->activos;

  
		$rspta_i = $vehiculo->vehiculos_inactivos();
		$regi=$rspta_i->fetch_object();
		$totali=$regi->inactivos;


		$rspta_pe = $vehiculo->vehiculos_con_servicios_pendientes();
		$regp=$rspta_pe->fetch_object();
		$totalp=$regp->pendientes;


		echo json_encode([
		  'total' => $total,
		  'totala' => $totala,
		  'totali' => $totali,
		  'totalp' => $totalp,


	  ]);

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