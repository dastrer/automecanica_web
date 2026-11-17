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
require_once "../modelos/Pago.php";
// 	Idservicio Fecha Monto Cambio Acuenta Saldo Metodo_pago
$pago=new Pago();
$Idpago=isset($_POST["Idpago"])? limpiarCadena($_POST["Idpago"]):"";
$Idservicio=isset($_POST["Idservicio2"])? limpiarCadena($_POST["Idservicio2"]):"";
$Fecha=isset($_POST["FechaP"])? limpiarCadena($_POST["FechaP"]):"";
$Monto=isset($_POST["MontoM"])? limpiarCadena($_POST["MontoM"]):"";
$Cambio=isset($_POST["CambioM"])? limpiarCadena($_POST["CambioM"]):""; 
$Acuenta=isset($_POST["AcuentaM"])? limpiarCadena($_POST["AcuentaM"]):""; 
$Saldo=isset($_POST["Nuevo_saldo"])? limpiarCadena($_POST["Nuevo_saldo"]):""; 
$Metodo_pago=isset($_POST["Metodo_pago2"])? limpiarCadena($_POST["Metodo_pago2"]):""; 

switch ($_GET["op"]){
	case 'guardaryeditar':

		if (empty($Idpago)){
				$rspta=$pago->insertar($Idservicio,$Fecha,$Monto,$Cambio,$Acuenta,$Saldo,$Metodo_pago);
				echo $rspta ? "Pago  registrado" : "Pago no se pudo registrar";
		
		}
		else {
	
		}
	break;

	case 'mostrarPago':
		$rspta=$pago->mostrarPago($Idservicio);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
    break;

	case 'listarDetallePago':
      //Recibimos el idingreso
      $id=$_GET['id'];
  
      $rspta = $pago->listarDetallePago($id);
      
      echo '<thead style="text-align: center;">  
                                     <tr>     
                                     <th>Fecha Pago</th>
                                     <th>Importe</th>
                                     <th>Cambio</th>
                                    <th>Acuenta</th>
                                    <th>Saldo</th>
                                      </tr>
                                  </thead>';
      while ($reg = $rspta->fetch_object())
          {
            echo '<tr class="filas">
                <td style="text-align: center;">' . $reg->FechaPago . '</td>
                <td style="text-align: center;">' . $reg->Monto . '</td>
                <td style="text-align: center;">' . $reg->Cambio . '</td>
                 <td style="text-align: center;">' . $reg->Acuenta . '</td>
                <td style="text-align: center;">' . $reg->Saldo . '</td>
            </tr>';
          }
      echo '<tfoot>
            </tfoot>';
    break;

case 'actualizarEstadoReparado':
  	require_once "../modelos/Servicio.php";
		$servicio = new Servicio();
    $rspta = $servicio->actualizarEstadoReparado($Idservicio);
    echo $rspta ? "Estado actualizado" : "Error al actualizar";
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