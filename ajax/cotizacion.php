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
require_once "../modelos/Cotizacion.php";

$cotizacion=new Cotizacion();
  
$Idcotizacion=isset($_POST["Idcotizacion"])? limpiarCadena($_POST["Idcotizacion"]):"";
$Idvehiculo=isset($_POST["Idvehiculo"])? limpiarCadena($_POST["Idvehiculo"]):"";
$Idusuario=$_SESSION["Idusuario"];
$Fecha=isset($_POST["Fecha"])? limpiarCadena($_POST["Fecha"]):"";
$Tipo_comprobante=isset($_POST["Tipo_comprobante"])? limpiarCadena($_POST["Tipo_comprobante"]):"";
$Descripcion=isset($_POST["Descripcion"])? limpiarCadena($_POST["Descripcion"]):"";
$Total=isset($_POST["Total"])? limpiarCadena($_POST["Total"]):""; 

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($Idcotizacion)){
			$rspta=$cotizacion->insertar($Fecha,$Tipo_comprobante,$Descripcion,$Total,$Idusuario,$Idvehiculo,$_POST["Iditem_diagnostico"],$_POST["Estado"],$_POST["Precio"],$_POST["Observaciones"]);
			if ($rspta) {
				if ($Tipo_comprobante == 'Proforma') {
					echo json_encode(array('redirect' => '../reportes/exCotizacion.php?id=' . $rspta));
				} 
			} else {
				echo json_encode(array('error' => 'Cotizacion no se pudo registrar'));
			}
		} 
		else {
		}
	break;

		case 'guardaryeditar2':
		if (empty($Idcotizacion)){
			$rspta=$cotizacion->insertar_articulo($Fecha,$Tipo_comprobante,$Descripcion,$Total,$Idusuario,$Idvehiculo,$_POST["Iditem_diagnostico"],$_POST["Estado"],$_POST["Precio"],$_POST["Observaciones"],$_POST["Precio_compra"],$_POST["Precio_cotizacion"],$_POST["Descuento"],$_POST["Cantidad_cotizacion"],$_POST["Idarticulo"]
		);
			if ($rspta) {
				if ($Tipo_comprobante == 'Proforma') {
					echo json_encode(array('redirect' => '../reportes/exCotizacion.php?id=' . $rspta));
				} 
			} else {
				echo json_encode(array('error' => 'Cotizacion no se pudo registrar'));
			}
		} 
		else {
		}
	break;

	

	case 'anular':
		$rspta=$cotizacion->anular($Idcotizacion);
 		echo $rspta ? "Cotizacion anulada" : "Cotizacion no se puede anular";
	break;

	case 'mostrar':
		$rspta=$cotizacion->mostrar($Idcotizacion);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listarDetalleVenta':
		//Recibimos el idingreso
		$id=$_GET['id'];
		$rspta = $cotizacion->listarDetalle($id);
		
		echo '<thead style="background-color:#007bff;color:white; font-weight: bold; text-align: center;">  
                                   <tr>
								  <th>Opciones</th>
                                              <th>Articulo</th>
                                              <th>Stock</th>
                                              <th>U/M</th>
                                              <th>Cantidad</th>
                                              <th>Precio</th>
                                              <th>Descuento</th>
                                              <th>Subtotal</th>
									</tr>
                                </thead>';
								$total = 0;
		while ($reg = $rspta->fetch_object())
				{ 
					echo '<tr class="filas">
					<td></td>
					<td>'.$reg->Nombre.'</td>
					<td>'.$reg->Cantidad.'</td>
					<td>'.$reg->Unidadmedida.'</td>
					<td>'.$reg->Cantidad_cotizacion.'</td>
					<td>'.$reg->Precio_cotizacion.'</td>
					<td>'.$reg->Descuento.'</td>
					<td>'.$reg->Subtotal.'</td>
					</tr>';
					$total=$total+($reg->Cantidad_cotizacion*$reg->Precio_cotizacion-$reg->Descuento);
					$total = round($total, 2);
				}echo '<tfoot>
					<tr>
						<th>TOTAL </th>
						<th><h5 id="totalCV">Bs/.'.$total.'</h5><input type="hidden" name="Preciototal" id="Preciototal"></th> 
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
                         <th></th>
					
					</tr>
						</tfoot>';
	break;
	
	case 'listar':
		$rspta = $cotizacion->listar();
		// Vamos a declarar un array
		$data = Array();
	
		while ($reg = $rspta->fetch_object()) {
			$data[] = array(

				"0"=>(($reg->Estado=='Aceptado')?'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->Idcotizacion.')"><i class="feather icon-eye"></i></button>'.
				' <button class="btn btn-danger btn-sm" onclick="anular('.$reg->Idcotizacion.')"><i class="feather icon-trash-2"></i></button>':
				'<button class="btn btn-small btn-warning btn-sm" onclick="mostrar('.$reg->Idcotizacion.')"><i class="feather icon-eye"></i></button>').
				'<button class="btn btn-info btn-sm" onclick="abrirPDF(\'../reportes/exCotizacion.php?id='.$reg->Idcotizacion.'\')">
					<i class="feather icon-file"></i>
				</button>', 
				"1" => $reg->Fecha,
				"2" => $reg->Tipo_comprobante . " No: " . $reg->Num_comprobante,
				"3" => $reg->Placa . " " . $reg->Marca,
				"4" => $reg->Cliente,
				"5" => $reg->Usuario,
				"6" => $reg->Total,
				"7" => ($reg->Estado == 'Aceptado') ? '<span class="badge badge-success">Aceptado</span>' : '<span class="badge badge-danger">Anulado</span>'
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
	
 

	case "selectVehiculo":
		require_once "../modelos/Vehiculo.php";
		$vehiculo = new Vehiculo();
		$rspta = $vehiculo->selectVehiculo();

		while ($reg = $rspta->fetch_object())
		{
			echo '<option value=' . $reg->Idvehiculo . '>' . $reg->Placa .'</option>';
		}
	break;
	
		case 'listarDiagnosticoCotizacion':
			$Idcotizacion = isset($_POST['Idcotizacion']) ? $_POST['Idcotizacion'] : 0;
			$rspta = $cotizacion->listarDiagnosticoCotizacion($Idcotizacion); 

			$data = array();
			while ($reg = $rspta->fetch_object()) {
				$data[] = array(
					"Iditem_diagnostico" => $reg->Iditem_diagnostico,
					"Nombre"             => $reg->Nombre,
					"Estado"             => $reg->Estado,
					"Precio"             => $reg->Precio,
					"Observaciones"      => $reg->Observaciones
				);
			}
			echo json_encode(array("data" => $data));
		break;


		case 'listarDiagnostico':
			require_once "../modelos/Item_diagnostico.php";
			$cotizacion = new Item_diagnostico();

			$rspta = $cotizacion->listarDiagnostico(); // Devuelve los items base

			$data = array();
			while ($reg = $rspta->fetch_object()) {
				$data[] = array(
					"Iditem_diagnostico" => $reg->Iditem_diagnostico,
					"Nombre" => $reg->Nombre,
					"Precio" => $reg->Precio
				);
			}

			echo json_encode(["data" => $data]);
		break;


		case 'listarArticulos': 
		require_once "../modelos/Articulo.php";
		$articulo=new Articulo();
		$rspta=$articulo->listarActivos();
 		//Vamos a declarar un array
 		$data= Array();
 		while ($reg=$rspta->fetch_object()){ 
 			$data[]=array(
 				"0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->Idarticulo.',\''.$reg->Nombre.'\',\''.$reg->Cantidad.'\',\''.$reg->Unidadmedida.'\',\''.$reg->Precio_venta.'\',\''.$reg->Precio_compra.'\')"><span class="feather icon-plus"></span></button>',		
 				"1"=>$reg->Nombre,
 				"2"=>$reg->Codigo, 
 				"3"=>$reg->Categoria, 
				"4"=>$reg->Cantidad." ".$reg->Unidadmedida,
				"5"=>$reg->Precio_venta,
				"6"=>"<img src='../files/articulo/".$reg->Imagen."' height='50px' width='50px' loading='lazy' alt='Artículo'>",
 				);
 		} 
		 
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);
	break;
	case 'mostrarVehiculo':
		require_once "../modelos/Vehiculo.php";
			$vehiculo = new Vehiculo();
		$rspta=$vehiculo->mostrar($Idvehiculo);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
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