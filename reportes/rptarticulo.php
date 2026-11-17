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
if ($_SESSION['Almacen']==1)
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
  $pdf->Cell(260, 6, utf8_decode('LISTA DE ARTICULOS'), 1, 1, 'C', 1); // Esta celda cubre todo el ancho (190 unidades)
  
  $pdf->SetX(10);
  $pdf->SetTextColor(0, 0, 0); // Texto en color negro
  $pdf->SetFillColor(205, 213, 192);
  $pdf->SetFont('Arial','B',10); // Reduzco el tamaño de la fuente
  $pdf->Cell(35, 10, utf8_decode('CODIGO'),1,0,'C',1);
  $pdf->Cell(50, 10, utf8_decode('NOMBRE'),1,0,'C',1);
  $pdf->Cell(40, 10, utf8_decode('CATEGORIA'),1,0,'C',1);
  $pdf->Cell(35, 10, utf8_decode('U/M'),1,0,'C',1);
  $pdf->Cell(40, 10, utf8_decode('STOCK'),1,0,'C',1);
  $pdf->Cell(60, 10, utf8_decode('MARCA'),1,1,'C',1);

    
  
  require_once "../modelos/Articulo.php";
  $articulo = new Articulo();
  // Ejecutar la consulta
  $rspta = $articulo->listar($_REQUEST["Idcategoria2"],$_REQUEST["Stock"]); 
//Table with rows and columns
  $pdf->SetWidths(array(35,50,40,35,40,60));
  $pdf->SetTextColor(0,0,0);
  
  // Generar filas dinámicamente con los datos de la consulta

  while($reg = $rspta->fetch_object()) {
    
       // Insertar los datos de cada fila
      $Codigo = $reg->Codigo;
      $Nombre = $reg->Nombre;
      $Categoria = $reg->Categoria;
      $Marca = $reg->Marca;
      $Cantidad =$reg->Cantidad;
      $Unidad = $reg->Unidadmedida;
      $pdf->SetFont('Arial','',10);
      $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C')); 
      $pdf->Row(array($Codigo,
      utf8_decode($Nombre),
      utf8_decode($Categoria),
      utf8_decode($Unidad),
      utf8_decode($Cantidad),
      utf8_decode($Marca)));  

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