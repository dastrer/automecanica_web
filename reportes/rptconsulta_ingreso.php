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
if ($_SESSION['ConsultaC']==1)
{
 
//Inlcuímos a la clase PDF_MC_Table
require('REPORTES.php');
date_default_timezone_set('America/La_Paz');
 
//Instanciamos la clase para generar el documento pdf
$pdf=new PDF_MC_Table();
  $pdf->AliasNbPages();
  $pdf->AddPage("P", "Letter");
  $pdf->SetAutoPageBreak(true, 20);
  $pdf->SetTopMargin(15);
  $pdf->SetLeftMargin(10);
  $pdf->SetRightMargin(10);

//Agregamos la primera página al documento pdf
require_once "../modelos/Consulta_ingreso.php";
$control = new Consulta_ingreso();
$detalles = $control->listar($_REQUEST["fecha_inicio"], $_REQUEST["fecha_fin"]);


$pdf->SetFont('Arial','B',10); // Fuente negrita
$pdf->SetFillColor(222, 20, 45);
$pdf->SetTextColor(255,255,255); // Texto en color negro
$pdf->Cell(195, 6, utf8_decode('REPORTE DE INGRESO'), 1, 1, 'C', 1); // Esta celda cubre todo el ancho (190 unidades)

$pdf->SetX(10);
$pdf->SetTextColor(0, 0, 0); // Texto en color negro
$pdf->SetFillColor(205, 213, 192);
$pdf->Cell(30, 10, utf8_decode('FECHAS'),1,0,'C',1);
$pdf->Cell(45, 10, utf8_decode('COMPROBANTE'),1,0,'C',1);
$pdf->Cell(45, 10, utf8_decode('PROVEEDOR'),1,0,'C',1);
$pdf->Cell(45, 10, utf8_decode('USUARIO'),1,0,'C',1);
$pdf->Cell(30, 10, utf8_decode('TOTAL'),1,1,'C',1);
$rspta = $detalles;

//Table with rows and columns
$pdf->SetWidths(array(30,45,45,45,30));
$pdf->SetTextColor(0,0,0);

$Total = 0;
while($reg= $rspta->fetch_object())
{  
    $Fecha = $reg->Fecha;
    $Comprobante = $reg->Tipocomprobante.'  '."Nro: ".$reg->Numero;
    $Proveedor = $reg->Nombre_empresa;
    $Usuario = $reg->Usuario;
    $Precio =$reg->Preciototal;

    $Total += $Precio;

 	$pdf->SetFont('Arial','',10);
   $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C')); 
  $pdf->Row(array($Fecha,
  utf8_decode($Comprobante),
  utf8_decode($Proveedor),
  utf8_decode($Usuario),
  $Precio));
}

$pdf->SetFillColor(205, 213, 192);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(195, 8, "TOTAL Bs : " . $Total, 1, 0, 'L',1);
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