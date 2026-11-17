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
  require('REPORTESL.php'); 
  date_default_timezone_set('America/La_Paz');


//Inlcuímos a la clase PDF_MC_Table
  $pdf=new PDF_MC_Table();
  $pdf->AliasNbPages();
  $pdf->AddPage("L", "Letter");
  $pdf->SetAutoPageBreak(true, 20);
  $pdf->SetTopMargin(15);
  $pdf->SetLeftMargin(10);
  $pdf->SetRightMargin(10);
  $pdf->SetX(10); // Posición horizontal
  $pdf->SetFont('Arial','B',10); // Fuente negrita
  $pdf->SetFillColor(222, 20, 45);
  $pdf->SetTextColor(255,255,255); // Texto en color negro
  $pdf->Cell(260, 6, utf8_decode('LISTA DE VENTAS'), 1, 1, 'C', 1); // Esta celda cubre todo el ancho (190 unidades)
  
  $pdf->SetX(10);
  $pdf->SetTextColor(0, 0, 0); // Texto en color negro
  $pdf->SetFillColor(205, 213, 192);
  $pdf->SetFont('Arial','B',10); // Reduzco el tamaño de la fuente
  $pdf->Cell(10,  7, utf8_decode('N°'),1,0,'C',1);
  $pdf->Cell(30,  7, utf8_decode('FECHA'),1,0,'C',1);
  $pdf->Cell(35,  7, utf8_decode('CLIENTE'),1,0,'C',1);
  $pdf->Cell(35,  7, utf8_decode('DOCUMENTO'),1,0,'C',1);
  $pdf->Cell(35,  7, utf8_decode('COMPROBANTE'),1,0,'C',1);
  $pdf->Cell(30,  7, utf8_decode('USUARIO'),1,0,'C',1);
  $pdf->SetFont('Arial','B',7); 
  $pdf->Cell(25,  7, utf8_decode('METODO DE PAGO'),1,0,'C',1);
  $pdf->SetFont('Arial','B',10); // Fuente negrita
  $pdf->Cell(20,  7, utf8_decode('MONTO'),1,0,'C',1);
  $pdf->Cell(20,  7, utf8_decode('CAMBIO'),1,0,'C',1);
  $pdf->Cell(20,  7, utf8_decode('TOTAL'),1,1,'C',1);

   
  
  require_once "../modelos/Venta.php";
  $Venta = new Venta();
  
  // Ejecutar la consulta
  $rspta = $Venta->listar();
//Table with rows and columns
  $pdf->SetWidths(array(10,30,35,35,35,30,25,20,20,20,20));
  $pdf->SetTextColor(0,0,0);
  
  // Generar filas dinámicamente con los datos de la consulta
  $index = 1; // Contador para el número de fila
  while($reg = $rspta->fetch_object()) { 
    
       // Insertar los datos de cada fila
      $Fecha = $reg->Fecha;
      $Cliente = $reg->Nombres;
      $Documento = $reg->Tipo_documento . " " . $reg->Num_documento;
      $Comprobante = $reg->Tipo_comprobante . " No: " . $reg->Num_comprobante;
      $Usuario =$reg->Usuario;
      $Tipo =$reg->Tipo;
      $Monto =$reg->Monto;
      $Cambio =$reg->Cambio;
      $Total =$reg->Total;
      $pdf->SetFont('Arial','',10);
      $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C')); 

      $pdf->Row(array($index,$Fecha,utf8_decode($Cliente),utf8_decode($Documento),
      utf8_decode($Comprobante),utf8_decode($Usuario),utf8_decode($Tipo),$Monto,$Cambio,$Total));  
      $index++; // Incrementar el contador de filas
  }
  $pdf->Ln(0.5);
  
  // Mostrar el documento PDF
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