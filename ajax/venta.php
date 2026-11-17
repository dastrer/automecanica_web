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
if ($_SESSION['Venta'] == 1 || $_GET['op'] == 'listarconsulta')
{
 	  
require_once "../modelos/Venta.php";
// 	Precio_compra	Precio_venta	Descuento	Cantidad_venta	Idarticulo	Idventa
$venta=new Venta();

$Idventa=isset($_POST["Idventa"])? limpiarCadena($_POST["Idventa"]):"";
$Idcliente=isset($_POST["Idcliente"])? limpiarCadena($_POST["Idcliente"]):"";
$Idusuario=$_SESSION["Idusuario"];
$Fecha=isset($_POST["Fecha"])? limpiarCadena($_POST["Fecha"]):"";
$Hora=isset($_POST["Hora"])? limpiarCadena($_POST["Hora"]):"";
$Tipo_comprobante=isset($_POST["Tipo_comprobante"])? limpiarCadena($_POST["Tipo_comprobante"]):"";
$Num_comprobante=isset($_POST["Num_comprobante"])? limpiarCadena($_POST["Num_comprobante"]):"";
$Tipo=isset($_POST["Tipo"])? limpiarCadena($_POST["Tipo"]):"";
$Monto=isset($_POST["Monto"])? limpiarCadena($_POST["Monto"]):"";
$Cambio=isset($_POST["Cambio"])? limpiarCadena($_POST["Cambio"]):"";
$Total=isset($_POST["Total"])? limpiarCadena($_POST["Total"]):"";
//Para Nuevo cliente
$Tipopersona=isset($_POST["Tipopersona"])? limpiarCadena($_POST["Tipopersona"]):"";
$Nombres=isset($_POST["Nombres"])? limpiarCadena($_POST["Nombres"]):"";
$Tipo_documento=isset($_POST["Tipo_documento"])? limpiarCadena($_POST["Tipo_documento"]):"";
$Num_documento=isset($_POST["Num_documento"])? limpiarCadena($_POST["Num_documento"]):"";
$Telefono=isset($_POST["Telefono"])? limpiarCadena($_POST["Telefono"]):"";

switch ($_GET["op"]){ 
	case 'guardaryeditar':
		if (empty($Idventa)){
			$rspta=$venta->insertar($Fecha,$Hora,$Tipo_comprobante,$Num_comprobante,$Tipo,$Monto,$Cambio,$Total,$Idusuario,$Idcliente,$_POST["Precio_compra"],$_POST["Precio_venta"],$_POST["Descuento"],$_POST["Cantidad_venta"],$_POST["Idarticulo"]);
			if ($rspta) {
				if ($Tipo_comprobante == 'Recibo') {
					echo json_encode(array('redirect' => '../reportes/exReciboV.php?id=' . $rspta));
				} else {
					echo json_encode(array('redirect' => '../reportes/exTicket.php?id=' . $rspta));
				}
			} else {
				echo json_encode(array('error' => 'Venta no se pudo registrar'));
			}
	    //echo $rspta ? "Venta registrada" : "No se pudieron registrar todos los datos de la Venta";
		} 
		else {
		}
	break;
 
	case 'guardaryeditarNuevoCliente':
		if (empty($Idventa)){
			$rspta=$venta->verificarDuplicidad($Num_documento);
			if($rspta->num_rows > 0) {
				echo "Esta Cedula ya fue Registrada";
			} 
			else 
			{ 
				$rspta=$venta->insertarNuevo($Tipopersona,$Nombres,$Num_documento,$Tipo_documento,$Telefono,$Fecha,$Hora,$Tipo_comprobante,$Num_comprobante,$Tipo,$Monto,$Cambio,$Total,$Idusuario,$_POST["Precio_compra"],$_POST["Precio_venta"],$_POST["Descuento"],$_POST["Cantidad_venta"],$_POST["Idarticulo"]);
				if ($rspta) {
					if ($Tipo_comprobante == 'Recibo') {
						echo json_encode(array('redirect' => '../reportes/exReciboV.php?id=' . $rspta));
					} else {
						echo json_encode(array('redirect' => '../reportes/exTicket.php?id=' . $rspta));
					}
				} else {
					echo json_encode(array('error' => 'Venta no se pudo registrar'));
				}
		    }
		//echo $rspta ? "Venta registrada" : "No se pudieron registrar todos los datos de la Venta";
		} 
		else {
		}
	break;

	case 'anular':
		$rspta=$venta->anular($Idventa);
 		echo $rspta ? "Venta anulada" : "Venta no se puede anular";
	break;

	case 'mostrar':
		$rspta=$venta->mostrar($Idventa);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listarDetalle':
		//Recibimos el idingreso
		$id=$_GET['id'];

		$rspta = $venta->listarDetalle($id);
		
		echo '<thead style="background-color:#007bff;color:white; font-weight: bold; text-align: center;">  
                                   <tr>
								  <th>Opciones</th>
                                              <th>Articulo</th>
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
					<td>'.$reg->Unidadmedida.'</td>
					<td>'.$reg->Cantidad_venta.'</td>
					<td>'.$reg->Precio_venta.'</td>
					<td>'.$reg->Descuento.'</td>
					<td>'.$reg->Subtotal.'</td>
					</tr>';
					$total=$total+($reg->Cantidad_venta*$reg->Precio_venta-$reg->Descuento);
					$total = round($total, 2);
				};
	break;
	case 'listar':
		$contador = 0;
		$rspta = $venta->listar();
		// Vamos a declarar un array
		$data = Array();
	
		while ($reg = $rspta->fetch_object()) {

			if($reg->Tipo_comprobante=='Recibo'){
				
				$url='../reportes/exReciboV.php?id=';
			} 
			else{
				$url='../reportes/exTicket.php?id=';
			}

			$data[] = array(
				"0" => (($reg->Estado == 'Aceptado') ? 
					'<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->Idventa . ')"><i class="feather icon-eye"></i></button>' .
					' <button class="btn btn-danger btn-sm" onclick="anular(' . $reg->Idventa . ')"><i class="feather icon-trash-2"></i></button>' :
					'<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->Idventa . ')"><i class="feather icon-eye"></i></button>'
				) .
                '<button class="btn btn-info btn-sm" onclick="abrirPDF(\''.$url.$reg->Idventa.'\')">
					<i class="feather icon-file"></i>
				</button>',
				"1" => $reg->Fecha,
				"2" => $reg->Nombres,
				"3" => $reg->Tipo_documento . " " . $reg->Num_documento, 
				"4" => $reg->Tipo_comprobante . " No: " . $reg->Num_comprobante,
				"5" => $reg->Usuario,
				"6" => $reg->Total,
			    "7"=>($reg->Estado=='Aceptado')?'<span class="badge badge-success">Aceptado</span>':
				'<span class="badge badge-danger">Anulado</span>'
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

	
	case 'listarconsulta':
	
		$rspta_hoy = $venta->ventahoy();
		$regh=$rspta_hoy->fetch_object();
		$total=$regh->ventahoy;
	  

		$rspta_re = $venta->realizados();
		$regr=$rspta_re->fetch_object();
		$totalr="Bs:  ".$regr->vendido;

  
		$rspta_pe = $venta->ventas_mes();
		$regp=$rspta_pe->fetch_object();
		$totalp="Bs:  ".$regp->ventas_mes;


		$rspta_anu = $venta->anulados();
		$rega=$rspta_anu->fetch_object();
		$totala=$rega->anulados;


		echo json_encode([
		  'total' => $total,
		  'totalr' => $totalr,
		  'totalp' => $totalp,
		  'totala' => $totala,

	  ]);

	break; 
	case 'generarNumero':
		$rspta=$venta->generarNumero($_POST['Tipo_comprobante']);
		$row = $rspta->fetch_assoc();
		echo json_encode($row);
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