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
if ($_SESSION['Servicio'] == 1)
	
{
require_once "../modelos/Servicio.php";

$servicio=new Servicio();
$Idservicio=isset($_POST["Idservicio"])? limpiarCadena($_POST["Idservicio"]):"";
$Idvehiculo=isset($_POST["Idvehiculo"])? limpiarCadena($_POST["Idvehiculo"]):"";
$Idusuario=$_SESSION["Idusuario"];
$Fecha=isset($_POST["Fecha"])? limpiarCadena($_POST["Fecha"]):"";
$Fecha_entrega=isset($_POST["Fecha_entrega"])? limpiarCadena($_POST["Fecha_entrega"]):"";
$Tipo_comprobante=isset($_POST["Tipo_comprobante"])? limpiarCadena($_POST["Tipo_comprobante"]):"";
$Descripcion=isset($_POST["Descripcion"])? limpiarCadena($_POST["Descripcion"]):"";
$Total=isset($_POST["Total"])? limpiarCadena($_POST["Total"]):""; 
 //tabla pagos   	Idservicio	Fecha	$Monto,$Cambio,$Acuenta,$Saldo,$Metodo_pago
$Monto=isset($_POST["Monto"])? limpiarCadena($_POST["Monto"]):"";
$Cambio=isset($_POST["Cambio"])? limpiarCadena($_POST["Cambio"]):""; 
$Acuenta=isset($_POST["Acuenta"])? limpiarCadena($_POST["Acuenta"]):""; 
$Saldo=isset($_POST["Saldo"])? limpiarCadena($_POST["Saldo"]):""; 
$Metodo_pago=isset($_POST["Metodo_pago"])? limpiarCadena($_POST["Metodo_pago"]):""; 

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($Idservicio)){
			$rspta=$servicio->insertar($Fecha,$Fecha_entrega,$Tipo_comprobante,$Descripcion,$Total,$Idusuario,$Idvehiculo,$Monto,$Cambio,$Acuenta,$Saldo,$Metodo_pago,$_POST["Iditem_diagnostico"],$_POST["Estado"],$_POST["Precio"],$_POST["Observaciones"]);
			if ($rspta) {
				if ($Tipo_comprobante == 'Recibo') {
					echo json_encode(array('redirect' => '../reportes/exRecibo.php?id=' . $rspta));
				} 
			} else {
				echo json_encode(array('error' => 'Servicio no se pudo registrar'));
			}
		} 
		else {
		}
	break;

		case 'guardaryeditar2':
		if (empty($Idservicio)){
			$rspta=$servicio->insertar_articulo($Fecha,$Fecha_entrega,$Tipo_comprobante,$Descripcion,$Total,$Idusuario,$Idvehiculo,$Monto,$Cambio,$Acuenta,$Saldo,$Metodo_pago,$_POST["Iditem_diagnostico"],$_POST["Estado"],$_POST["Precio"],$_POST["Observaciones"],$_POST["Precio_compra"],$_POST["Precio_venta"],$_POST["Descuento"],$_POST["Cantidad_venta"],$_POST["Idarticulo"]
		);
			if ($rspta) {
				if ($Tipo_comprobante == 'Recibo') {
					echo json_encode(array('redirect' => '../reportes/exRecibo.php?id=' . $rspta));
				} 
			} else {
				echo json_encode(array('error' => 'Servicio no se pudo registrar'));
			}
		} 
		else {
		}
	break;

	

	case 'anular':
		$rspta=$servicio->anular($Idservicio);
 		echo $rspta ? "Servicio anulado" : "Servicio no se puede anular";
	break;

	case 'mostrar':
		$rspta=$servicio->mostrar($Idservicio);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listarDetalleVenta':
		//Recibimos el idingreso
		$id=$_GET['id'];
		$rspta = $servicio->listarDetalle($id);
		
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
					<td>'.$reg->Cantidad_venta.'</td>
					<td>'.$reg->Precio_venta.'</td>
					<td>'.$reg->Descuento.'</td>
					<td>'.$reg->Subtotal.'</td>
					</tr>';
					$total=$total+($reg->Cantidad_venta*$reg->Precio_venta-$reg->Descuento);
					$total = round($total, 2);
				}echo '<tfoot>
					<tr>
						<th>TOTAL </th>
						<th><h5 id="totalCV">Bs/.'.$total.'</h5><input type="hidden" name="Preciototal" id="Preciototal"></th> 
                        <th colspan="6"></th>
					</tr>
						</tfoot>';
	break;
	
	case 'listar':
		$fecha_inicio=$_REQUEST["fecha_inicio"];
        $fecha_fin=$_REQUEST["fecha_fin"];

		$rspta = $servicio->listar($fecha_inicio,$fecha_fin);
		// Vamos a declarar un array
		$data = Array();
	
		while ($reg = $rspta->fetch_object()) {
			// Lógica para calcular la clase según la fecha de entrega
			$fecha_entrega = date('Y-m-d', strtotime($reg->Fecha_entrega));
			$hoy = date('Y-m-d');
			if ($fecha_entrega === $hoy) {
				$clase = 'badge-danger';  // Fecha de entrega es hoy
			} elseif ($fecha_entrega > $hoy) {
				$clase = 'badge-warning'; // Fecha de entrega es futura
			} else {
				$clase = 'badge-success'; // Fecha de entrega ya pasó
			}
			$fecha_html = '<span class="badge ' . $clase . ' fs-6 px-3 py-2">' . $reg->Fecha_entrega . '</span>';

			$data[] = array(

				"0"=>(($reg->Estado=='En proceso')?
				'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->Idservicio.')"><i class="feather icon-eye"></i></button>'.
				' <button class="btn btn-danger btn-sm" onclick="anular('.$reg->Idservicio.')"><i class="feather icon-trash-2"></i></button>':
				'<button class="btn btn-small btn-warning btn-sm" onclick="mostrar('.$reg->Idservicio.')"><i class="feather icon-eye"></i></button>').
				'<button class="btn btn-info btn-sm" onclick="abrirPDF(\'../reportes/exRecibo.php?id='.$reg->Idservicio.'\')">
					<i class="feather icon-file"></i>
				</button>'.
				'<a data-toggle="modal" href="#myModal3">
						<button class="btn btn-sm ' . ($reg->Estado == 'En proceso' ? 'btn-success' : 'btn-primary') . '" onclick="modalPago(' . $reg->Idservicio . ')">
							<i class="' . ($reg->Estado == 'En proceso' ? 'fas fa-money-check-alt' : 'fas fa-receipt') . '"></i>
						</button>
				</a>',
                "1" => $fecha_html,
				"2" => $reg->Tipo_comprobante . " No: " . $reg->Num_comprobante ,
				"3" => $reg->Placa,
				"4" => $reg->Cliente,
				"5" => $reg->Total,
				"6" =>  ($reg->Estado == 'En proceso') ? '<span class="badge badge-primary fs-6 px-3 py-2"">En proceso</span>' :
					(($reg->Estado == 'Reparado') ? '<span class="badge badge-success fs-6 px-3 py-2"">Reparado</span>' :
					'<span class="badge badge-danger fs-6 px-3 py-2"">Anulado</span>'),
				"7" => $reg->Acuenta,
				"8" => $reg->SaldoPago,
				"9" => $reg->Metodo_pago,
				"10" => $reg->Usuario,
				"11" => $reg->Fecha,

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
			echo '<option value=' . $reg->Idvehiculo . '>' . $reg->Placa ." - ".$reg->Tipo.'</option>';
		}
	break;
	
		case 'listarDiagnosticoServicio':
			$Idservicio = isset($_POST['Idservicio']) ? $_POST['Idservicio'] : 0;
			$rspta = $servicio->listarDiagnosticoServicio($Idservicio); 

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
			$diagnosticos = new Item_diagnostico();

			$rspta = $diagnosticos->listarDiagnostico(); // Devuelve los items base

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
				"6"=>"<img src='../files/articulo/".$reg->Imagen."' height='50px' width='50px'loading='lazy' alt='Artículo' >",
 				);
 		} 
		 
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);
	break;
  	
	case 'listarconsulta':
	
		$rspta_mes = $servicio->servicio_mes();
		$regm=$rspta_mes->fetch_object();
		$totalMes=$regm->servicio_mes;

		$rspta_rmes = $servicio->completado_mes();
		$regrm=$rspta_rmes->fetch_object();
		$repMes=$regrm->completado_mes;
	  
		echo json_encode([
		  'totalMes' => $totalMes,
		  'repMes' => $repMes,
	  ]);

	break; 

	case 'mostrarCotizacion':
		require_once "../modelos/Cotizacion.php";
		$cotizacion=new Cotizacion();
		$rspta = $cotizacion->mostrar($_POST['Idcotizacion']);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listarDetalleCotizacion':
	$id = $_GET['id'];
	require_once "../modelos/Cotizacion.php";
	$cotizacion = new Cotizacion();
	$rspta = $cotizacion->listarDetalle($id);

	echo '<thead style="text-align: center;">
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
	$cont = 1;

	while ($reg = $rspta->fetch_object()) {
		$subtotal = ($reg->Cantidad_cotizacion * $reg->Precio_cotizacion) - $reg->Descuento;
		echo '<tr class="filas" id="fila' . $cont . '">
				<td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarDetalle(' . $cont . ')"><i class="feather icon-trash-2"></i></button></td>
				<td>
					<input type="hidden" name="Idarticulo[]" value="' . $reg->Idarticulo . '">' . $reg->Nombre . '
				</td>
				<td>' . $reg->Cantidad . '</td>
				<td>' . $reg->Unidadmedida . '</td>
				<td>
					<input type="number" name="Cantidad_venta[]" id="Cantidad_venta' . $cont . '" value="' . $reg->Cantidad_cotizacion . '" min="1" max="' . $reg->Cantidad . '" oninput="modificarSubototales()">
				</td>
				<td>
					' . $reg->Precio_cotizacion . '
					<input type="hidden" name="Precio_venta[]" id="Precio_venta' . $cont . '" value="' . $reg->Precio_cotizacion . '">
					<input type="hidden" name="Precio_compra[]" id="Precio_compra' . $cont . '" value="' . $reg->Precio_compra . '">
				</td>
				<td>
					<input type="number" name="Descuento[]" value="' . $reg->Descuento . '" oninput="modificarSubototales()" min="0">
				</td>
				<td>
					<span name="subtotal" id="subtotal' . $cont . '">' . $subtotal . '</span>
				</td>
			  </tr>';
		$total += $subtotal;
		$total = round($total, 2);
		$cont++;
	}

	echo '<tfoot>
			<tr>
				<th>TOTAL</th>
				<th>
					<h5 id="totalCV">Bs/. ' . $total . '</h5>
					<input type="hidden" name="Preciototal" id="Preciototal" value="' . $total . '">
				</th>
                <th colspan="6"></th>

			</tr>
		  </tfoot>';
	break;




	case "selectCotizacion":
		require_once "../modelos/Cotizacion.php";
		$cotizacion = new Cotizacion();
		$rspta = $cotizacion->selectCotizacion();

		while ($reg = $rspta->fetch_object())
		{
			echo '<option value=' . $reg->Idcotizacion . '>' ." No: ".$reg->Num_comprobante." ".$reg->Placa." ".$reg->Nombres.'</option>';
		}
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