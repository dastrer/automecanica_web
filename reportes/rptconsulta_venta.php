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
if ($_SESSION['ConsultaV']==1)
{
 
  require('REPORTESA.php');
  date_default_timezone_set('America/La_Paz');
//Inlcuímos a la clase PDF_MC_Table

  $pdf=new PDF_MC_Table();
  $pdf->AliasNbPages();
  $pdf->AddPage("L", "Legal"); 
  $pdf->SetAutoPageBreak(true, 20);
  $pdf->SetTopMargin(15);
  $pdf->SetLeftMargin(10);
  $pdf->SetRightMargin(10);
  $pdf->SetX(10); // Posición horizontal
  $pdf->SetFont('Arial','B',10); // Fuente negrita
  $pdf->SetFillColor(222, 20, 45);
  $pdf->SetTextColor(255,255,255); // Texto en color negro
  $pdf->Cell(335, 8, utf8_decode('REPORTE DE VENTA ARTICULO'), 1, 1, 'C', 1); 
  $pdf->SetX(10);
  $pdf->SetFillColor(205, 213, 192);
  $pdf->SetFont('Arial','B',10); // Reduzco el tamaño de la fuente
  $pdf->SetTextColor(0, 0, 0); // Blanco en RGB    
  $pdf->Cell(10, 7, utf8_decode('N°'),1,0,'C',1);
  $pdf->Cell(30, 7, utf8_decode('FECHA'),1,0,'C',1);
  $pdf->Cell(50, 7, utf8_decode('T.PAGO'),1,0,'C',1);
  $pdf->Cell(40, 7, utf8_decode('COMPROBANTE'),1,0,'C',1);
  $pdf->Cell(60, 7, utf8_decode('ARTICULO'),1,0,'C',1);
  $pdf->Cell(25, 7, utf8_decode('CANTIDAD'),1,0,'C',1);
  $pdf->Cell(30, 7, utf8_decode('P.COMPRA'),1,0,'C',1);
  $pdf->Cell(30, 7, utf8_decode('UTILIDAD'),1,0,'C',1);
  $pdf->Cell(30, 7, utf8_decode('PRECIO'),1,0,'C',1);
  $pdf->Cell(30, 7, utf8_decode('TOTAL'),1,1,'C',1);
   
  
  require_once "../modelos/Consulta_venta.php";
  $venta = new Consulta_venta();
  
  // Ejecutar la consulta
  $rspta = $venta->listar($_REQUEST["fecha_inicio"],$_REQUEST["fecha_fin"],$_REQUEST["Idarticulo"]);
//Table with rows and columns 
  $pdf->SetWidths(array(10,30,50,40,60,25,30,30,30,30));
  $pdf->SetTextColor(0,0,0);
  // Generar filas dinámicamente con los datos de la consulta
  $index = 1; // Contador para el númer de fila
  $Total_subtotal = 0;
  $Cantidad_total = 0;
  while($reg = $rspta->fetch_object()) {
       // Insertar los datos de cada fila
      $Fecha = $reg->Fecha;
      $Tipo =$reg->Tipo;
      $Comprobante = $reg->Tipo_comprobante . " No: " . $reg->Num_comprobante;
      $Articulo = $reg->Articulo;
      $Cantidad = $reg->Cantidad_venta;
      $Utilidad =$reg->Utilidad;
      $Precio_compra =$reg->Precio_compra;
      $Precio = $reg->Precio_venta;
      $Subtotal =$reg->Subtotal;
      $Total_subtotal += round($Subtotal, 2);
      $Cantidad_total += $Cantidad;

      $pdf->SetFont('Arial','',10);
      $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C')); 
      $pdf->Row(array(
      $index,
      utf8_decode($Fecha),
      utf8_decode($Tipo),
      utf8_decode($Comprobante),
      utf8_decode($Articulo),
      utf8_decode($Cantidad),
      $Precio_compra,
      $Utilidad,
      utf8_decode($Precio),
      $Subtotal
    ));  
      $index++; // Incrementar el contador de filas
  }
  $pdf->SetX(10); // Posiciona en X para la siguiente fila
  $pdf->SetTextColor(0, 0, 0); 
  $pdf->SetFillColor(205, 213, 192);
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(167.5,  6, "Cantidad: " . $Cantidad_total, 1, 0, 'L',1);
  $pdf->Cell(167.5, 6, "Total Bs/. " . number_format($Total_subtotal, 2, '.', ''), 1, 1, 'L',1);
  $pdf->Ln(0.5);
   
  // Mostrar el documento PDF
  $pdf->Output('AUTONIC.pdf', 'I');
  
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