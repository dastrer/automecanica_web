<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1) 
  session_start();

if (!isset($_SESSION["nombre"]))
{
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
}
else
{ 
if ($_SESSION['ConsultaS']==1)
{
 
//Inlcuímos a la clase PDF_MC_Table
require('REPORTESL.php');
date_default_timezone_set('America/La_Paz');
 
//Instanciamos la clase para generar el documento pdf
$pdf=new PDF_MC_Table();
$pdf->AliasNbPages();
$pdf->AddPage("L", "Letter");
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetTopMargin(15);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);

$pdf->SetFont('Arial','B',10); // Fuente negrita
$pdf->SetFillColor(222, 20, 45);
$pdf->SetTextColor(255,255,255); // Texto en color negro
$pdf->Cell(260, 6, utf8_decode('REPORTE DE SERVICIOS'), 1, 1, 'C', 1); // Esta celda cubre todo el ancho (190 unidades)

$pdf->SetX(10);
$pdf->SetTextColor(0, 0, 0); // Texto en color negro
$pdf->SetFillColor(205, 213, 192);
$pdf->Cell(10,  7, utf8_decode('N°'),1,0,'C',1);
$pdf->Cell(30,  7, utf8_decode('FECHA'),1,0,'C',1);
$pdf->Cell(35,  7, utf8_decode('CLIENTE'),1,0,'C',1);
$pdf->Cell(35,  7, utf8_decode('COMPROBANTE'),1,0,'C',1);
$pdf->Cell(35,  7, utf8_decode('VEHICULO'),1,0,'C',1);
$pdf->Cell(30,  7, utf8_decode('USUARIO'),1,0,'C',1);
$pdf->SetFont('Arial','B',7); 
$pdf->Cell(25,  7, utf8_decode('METODO PAGO'),1,0,'C',1);
$pdf->SetFont('Arial','B',10); 
$pdf->Cell(20,  7, utf8_decode('MONTO'),1,0,'C',1);
$pdf->Cell(20,  7, utf8_decode('CAMBIO'),1,0,'C',1);
$pdf->Cell(20,  7, utf8_decode('TOTAL'),1,1,'C',1);

//Table with rows and columns
$pdf->SetWidths(array(10,30,35,35,35,30,25,20,20,20,20));
$pdf->SetTextColor(0,0,0);
//Agregamos la primera página al documento pdf
require_once "../modelos/Consulta_servicio.php";
$control = new Consulta_servicio();
$detalles = $control->listar($_REQUEST["fecha_inicio"], $_REQUEST["fecha_fin"],$_REQUEST["Idvehiculo"]);
$index = 1; // Contador para el número de fila
$Total = 0;
$Total_deposito=0;
$Total_efectivo=0;
$Total_monto=0;
$Total_cambio=0;
while($reg= $detalles->fetch_object())
{  
  $Fecha = $reg->Fecha;
  $Cliente = $reg->Cliente;
  $Comprobante = $reg->Tipo_comprobante . " No: " . $reg->Num_comprobante;
  $Vehiculo =$reg->Placa . " " . $reg->Marca;
  $Usuario =$reg->Usuario;
  $Tipo =$reg->Metodo_pago;
  $Monto =$reg->Monto;
  $Cambio =$reg->Cambio;
  $TotalVenta =$reg->Total;


// Clasificar y sumar por tipo de pago
if ($Tipo == 'Efectivo') {
    $Total_efectivo += $TotalVenta;
} elseif ($Tipo == 'Deposito') {
    $Total_deposito += $TotalVenta;
}
  $pdf->SetFont('Arial','',10);
  $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C')); 

  $pdf->Row(array($index,$Fecha,utf8_decode($Cliente),utf8_decode($Comprobante),
  utf8_decode($Vehiculo),utf8_decode($Usuario),utf8_decode($Tipo),$Monto,$Cambio,$TotalVenta));  
  $index++; // Incrementar el contador de filas
  $Total = round($Total_efectivo+$Total_deposito,2);
  $Total_monto += $Monto;
  $Total_cambio += $Cambio;
}

$pdf->SetFillColor(205, 213, 192);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(40, 8, "TOTAL Bs/. " . $Total, 1, 0, 'L',1);
$pdf->Cell(70, 8, "EFECTIVO Bs/. " . $Total_efectivo, 1, 0, 'L',1);
$pdf->Cell(65, 8, "DEPOSITO Bs/. " . $Total_deposito, 1, 0, 'L',1);
$pdf->Cell(45, 8, "MONTO Bs/. " . $Total_monto, 1, 0, 'L',1);
$pdf->Cell(40, 8, "CAMBIO Bs/. " . $Total_cambio, 1, 0, 'L',1);
//Mostramos el documento pdf
$pdf->Output();

?>
<?php
}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush();
?>