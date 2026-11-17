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

require_once "../modelos/Ingreso.php";

$ingreso=new Ingreso();
$Idusuario=$_SESSION["Idusuario"];
$Tipocomprobante=isset($_POST["Tipocomprobante"])? limpiarCadena($_POST["Tipocomprobante"]):"";
$Numero=isset($_POST["Numero"])? limpiarCadena($_POST["Numero"]):"";
$Fecha=isset($_POST["Fecha"])? limpiarCadena($_POST["Fecha"]):"";
$Preciototal=isset($_POST["Preciototal"])? limpiarCadena($_POST["Preciototal"]):"";
$Idproveedor=isset($_POST["Idproveedor"])? limpiarCadena($_POST["Idproveedor"]):"";
$Idingreso=isset($_POST["Idingreso"])? limpiarCadena($_POST["Idingreso"]):"";


  
switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($Idingreso)){  	
			$rspta=$ingreso->insertar($Fecha,$Tipocomprobante,$Numero,$Preciototal,$Idproveedor,$Idusuario,$_POST["Precio_compra"],$_POST["Precio_venta"],$_POST["Cantidad_ingreso"],$_POST["Idarticulo"]);
			echo $rspta ? "Ingreso registrado Correctamente" : "No se pudieron registrar todos los datos del Ingreso";
		}
		else {
		}
	break;

	case 'anular':
		$rspta=$ingreso->anular($Idingreso);
 		echo $rspta ? "Ingreso anulado" : "Ingreso no se puede anular";
	break;

	case 'mostrar':
		$rspta=$ingreso->mostrar($Idingreso);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listarDetalle':
		//Recibimos el idingreso
		$id=$_GET['id'];

		$rspta = $ingreso->listarDetalle($id);
		
		echo '<thead style="background-color:#007bff;color:white;   font-weight: bold; text-align: center;">  
                                   <tr>
								    <th>Opciones</th>
									<th>Articulo</th>
									<th>Cantidad</th>
									<th>Precio compra</th>
									<th>Precio venta</th>
									<th>Subtotal</th>
									</tr>
                                </thead>'; 

		while ($reg = $rspta->fetch_object())
				{
					echo '<tr class="filas">
					<td></td>
					<td>'.$reg->Nombre.'</td>
					<td>'.$reg->Cantidad_ingreso.'</td>
					<td>'.$reg->Precio_compra.'</td>
					<td>'.$reg->Precio_venta.'</td>
					<td>'.$reg->Precio_compra*$reg->Cantidad_ingreso.'</td>
					</tr>';
					$total=$total+($reg->Cantidad_ingreso*$reg->Precio_compra);
					$total = round($total, 2);
				}
		echo '<tfoot>
		                        <tr>
                                    <th>TOTAL </th>
                                    <th><h5 id="total">Bs/.'.$total.'</h5><input type="hidden" name="Preciototal" id="Preciototal"></th> 
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                
                                </tr>
									</tfoot>';
	break;
	case 'listar':
		$rspta=$ingreso->listar();
 		//Vamos a declarar un array
 		$data= Array(); 

 		while ($reg=$rspta->fetch_object()){
	
 			$data[]=array(

 
				"0"=>(($reg->Estado=='Aceptado')?'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->Idingreso.')"><i class="feather icon-eye"></i></button>'.
				' <button class="btn btn-danger btn-sm" onclick="anular('.$reg->Idingreso.')"><i class="feather icon-trash-2"></i></button>':
				'<button class="btn btn-small btn-warning btn-sm" onclick="mostrar('.$reg->Idingreso.')"><i class="feather icon-eye"></i></button>').
				'<button class="btn btn-info btn-sm" onclick="abrirPDF(\'../reportes/exIngreso.php?id='.$reg->Idingreso.'\')">
					<i class="feather icon-file"></i>
				</button>',                                                        
 				"1"=>$reg->Fecha,
 				"2"=>$reg->Tipocomprobante.'  '."Nro: ".$reg->Numero,
 				"3"=>$reg->Proveedor,
 				"4"=>$reg->Usuario,
 				"5"=>$reg->Preciototal, 
 				"6"=>($reg->Estado=='Aceptado')?'<span class="badge badge-success">Aceptado</span>':
 				'<span class="badge badge-danger">Anulado</span>'
 				);
 		}

 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;

	case 'listarArticulos':
		require_once "../modelos/Articulo.php";
		$articulo=new Articulo();
		$rspta=$articulo->listarActivos();
 		//Vamos a declarar un array
 		$data= Array();
 		while ($reg=$rspta->fetch_object()){ 
 			$data[]=array(
 				"0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->Idarticulo.',\''.$reg->Nombre.'\',\''.$reg->Precio_venta.'\')"><span class="feather icon-plus"></span></button>',
 				"1"=>$reg->Nombre,
 				"2"=>$reg->Codigo, 
 				"3"=>$reg->Cantidad." ".$reg->Unidadmedida,
				"4"=>$reg->Categoria,
				"5"=>$reg->Precio_compra,
				"6"=>$reg->Precio_venta,
				"7"=>"<img src='../files/articulo/".$reg->Imagen."' height='50px' width='50px' loading='lazy' alt='Artículo'>",
 				);
 		} 
		 
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);
	break;


	case 'selectProveedor':
		require_once "../modelos/Proveedor.php";
		$proveedor = new Proveedor();

		$rspta = $proveedor->listarP();

		while ($reg = $rspta->fetch_object())
				{
				echo '<option value=' . $reg->Idproveedor . '>' . $reg->Nombres ." - ".$reg->Nombre_empresa .'</option>';
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