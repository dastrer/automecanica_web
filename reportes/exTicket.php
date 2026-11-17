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
if ($_SESSION['Venta']==1)
{ 



require "../fpdf181/code128.php";
require "../phpqrcode/qrlib.php"; 
require_once "../modelos/Venta.php";
$venta = new Venta();
$rsptac = $venta->ventacabecera($_GET["id"]);
//Recorremos todos los  obtenidos
$regc = $rsptac->fetch_object();

	//Inicio QR
	$dir = 'Qr/';
	
	//Si no existe la carpeta la creamos
	if (!file_exists($dir))
  {
        mkdir($dir);
  }
        //Declaramos la ruta y nombre del archivo a generar
	$filename = $dir.'QR.png';
        //Parametros de Condiguración
	
	$tamaño = 10; //Tamaño de Pixel
	$level = 'L'; //Precisión Baja
	$framSize = 3; //Tamaño en blanco
	$contenido = " 
    DETALE DE VENTA
    =======================
    $regc->Tipo_comprobante N°   $regc->Num_comprobante 
    Fecha = $regc->Fecha
    Cliente = $regc->cliente
    Total Pedido= $regc->Total Bs
    ======================="; //Texto
        //Enviamos los parametros a la Función para generar código QR 
	QRcode::png($contenido, $filename, $level, $tamaño, $framSize); 
		//Fin QR

$pdf = new PDF_Code128('P','mm',array(80,258));
$pdf->SetMargins(4,10,4);
$pdf->AddPage();
# Encabezado y datos de la empresa #
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1",strtoupper('"TALLER MECANICO AUTONIC"')),0,'C',false);
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","El Alto Av. Bolivia Entrada Villa Adela Calle 16"),0,'C',false);
$pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Telefono: 74095226"),0,'C',false);
$pdf->Ln(1);
$pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","========================================"),0,0,'C');
$pdf->Ln(5);

$pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1", "Vendedor: " . $regc->usuario),0,'C',false);
$pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1", "Hora: " . $regc->Hora),0,'C',false);
$pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1", "Cliente: " . $regc->cliente),0,'C',false);
$pdf->SetFont('Arial','B',10);
$pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1"," $regc->Tipo_comprobante  No:  ".strtoupper("$regc->Num_comprobante")),0,'C',false);
$pdf->SetFont('Arial','',9);

$pdf->Ln(1);
$pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","========================================"),0,0,'C');
$pdf->Ln(5);


$pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1",$regc->Fecha),0,'C',false);


$pdf->Ln(1);
$pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","======================================"),0,0,'C');
$pdf->Ln(3);



$rspta = $venta->ventadetalle($_GET["id"]); 

$total = 0;

// Inicializamos la tabla fuera del bucle
$pdf->Cell(20, 5, iconv("UTF-8", "ISO-8859-1", "Cantidad"), 0, 0, 'C');
$pdf->Cell(19, 5, iconv("UTF-8", "ISO-8859-1", "Precio"), 0, 0, 'C');
$pdf->Cell(13, 5, iconv("UTF-8", "ISO-8859-1", "Descuento"), 0, 0, 'C');
$pdf->Cell(25, 5, iconv("UTF-8", "ISO-8859-1", "Total"), 0, 0, 'C');
$pdf->Ln(3);
$pdf->Cell(72, 5, iconv("UTF-8", "ISO-8859-1", "======================================"), 0, 0, 'C');
$pdf->Ln(4);

while ($reg = $rspta->fetch_object()) {

    $cantidad = $reg->Cantidad_venta;
    $nombre = $reg->Nombre;
    $precio = $reg->Precio_venta;
    $descuento = $reg->Descuento;
    $subtotal = $reg->Subtotal;

    // Sumamos al total en cada iteración
    $total = $total + $subtotal;

    // Agregamos los detalles de la tabla en cada iteración
    $pdf->MultiCell(0, 4, iconv("UTF-8", "ISO-8859-1", "$nombre"), 0, 'C', false);
    $pdf->Cell(20, 4, iconv("UTF-8", "ISO-8859-1", "$cantidad"), 0, 0, 'C');
    $pdf->Cell(19, 4, iconv("UTF-8", "ISO-8859-1", "$precio"), 0, 0, 'C');
    $pdf->Cell(13, 4, iconv("UTF-8", "ISO-8859-1", "$descuento"), 0, 0, 'C');
    $pdf->Cell(25, 4, iconv("UTF-8", "ISO-8859-1", "$subtotal"), 0, 0, 'C');
    $pdf->Ln(4);
}


 
    $pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","==================================="),0,0,'C');

    $pdf->Ln(5);

    // Espacio inicial (alineado a la izquierda)
    $pdf->Cell(10, 5, "", 0, 0, 'C');
    
    // Total a pagar (alineado a la izquierda)
    $pdf->Cell(22, 5, iconv("UTF-8", "ISO-8859-1", "TOTAL"), 0, 0, 'L');
    $pdf->Cell(32, 5, iconv("UTF-8", "ISO-8859-1", "$regc->Total Bs"), 0, 1, 'R'); // Salto de línea aquí
    
    
    $pdf->Ln(3);
    
    
    


    $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","*** Para poder realizar un reclamo o devolución debe de presentar el ticket ***"),0,'C',false);
    $pdf->Ln(3);
    $posicionY = $pdf->GetY();
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetY($posicionY);
    $imagenPath = $filename;




      $pdf->Image($imagenPath, ($pdf->GetPageWidth() - 30) / 2, $posicionY, 30, 0, 'PNG');

      $posicionY += 30; 

      $pdf->SetFont('Arial', '', 9);
      $pdf->SetY($posicionY);
      $pdf->Cell(0, 7, iconv("UTF-8", "ISO-8859-1", "¡Gracias por su compra!"), '', 0, 'C');
      $posicionY += 7;
      $pdf->SetY($posicionY);
      $pdf->SetFont('Arial', 'B', 9);
      $pdf->Cell(0, 7, iconv("UTF-8", "ISO-8859-1", "La Paz-El Alto"), '', 0, 'C');

      # Nombre del archivo PDF #
      $pdf->Output("I", $regc->Tipo_comprobante . " " . $regc->Num_comprobante . ".pdf", true);





}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush();
?>