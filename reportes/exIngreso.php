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
//Incluímos el archivo Factura.php
require('COMPRA.php');
require "../phpqrcode/qrlib.php"; 


 
//Obtenemos los datos de la cabecera de la venta actual
require_once "../modelos/Ingreso.php";
$ingreso = new Ingreso();
$rsptac = $ingreso->ingresoCabecera($_GET["id"]);
//Recorremos todos los valores obtenidos
$regc = $rsptac->fetch_object();

//Establecemos los datos de la empresa
$logo = "../reportes/img/logo.png";
$ext_logo = "PNG";
$empresa = "TALLER MECANICO AUTONIC";
$direccion = "El Alto Av. Bolivia Entrada Villa Adela Calle Nro. 16";
$telefono = "62338818";
$email = "autonic@gmail.com";

	//Inicio QR
	$dir = 'Qr/';
	
	//Si no existe la carpeta la creamos
	if (!file_exists($dir))
  {
        mkdir($dir);
  }
        //Declaramos la ruta y nombre del archivo a generar
	$filename = $dir.'qr.jpg';
        //Parametros de Condiguración
	
	$tamaño = 10; //Tamaño de Pixel
	$level = 'L'; //Precisión Baja
	$framSize = 3; //Tamaño en blanco
	$contenido = " 
     DETALE DE INGRESO
    ====================
    Fecha = $regc->Fecha
    Usuario= $regc->Usuario
    Proveedor= $regc->Nombres
    Total= $regc->Preciototal Bs
    ===================="; //Texto
        //Enviamos los parametros a la Función para generar código QR 
	QRcode::png($contenido, $filename, $level, $tamaño, $framSize); 
		//Fin QR


//Establecemos la configuración de la factura
$pdf = new PDF_Invoice('P', 'mm', array(210, 250));
$pdf->AddPage();

//Enviamos los datos de la empresa al método addSociete de la clase Factura
$info_empresa = utf8_decode("Dirección: ") . utf8_decode($direccion) . "\n" .
                utf8_decode("Teléfono: ") . $telefono . "\n" .
                 "Email: " . $email . "\n" .
                 "Usuario: " .utf8_decode($regc->Usuario);
// Llamada a addSociete con la información organizada
$pdf->addSociete(utf8_decode($empresa), $info_empresa, $logo, $ext_logo);
                  $pdf->fact_dev( utf8_decode("$regc->Tipocomprobante"), " No: $regc->Numero" );
$pdf->temporaire( "" );
$pdf->addDate( $regc->Fecha);

$imagenPath = $filename;

// Ajustar las posiciones X e Y según sea necesario
$posicionX = 160; // Cambia este valor a la posición X deseada
$posicionY = 36; // Cambia este valor a la posición Y deseada

// Añadir la imagen en la posición X e Y especificada
$pdf->Image($imagenPath, $posicionX, $posicionY, 25, 0, 'PNG');


//Enviamos los datos del cliente al método addClientAdresse de la clase Factura
$pdf->addClientAdresse("Nombre: ".utf8_decode($regc->Nombres),"Domicilio: ".utf8_decode($regc->Direccion),utf8_decode($regc->Tipo_documento).": ".utf8_decode($regc->Num_documento),"Empresa: ".$regc->Nombre_empresa,"Telefono: ".$regc->Telefono);
$pdf->Ln(3);
 
  $pdf->SetFont('Arial','B',10); // Fuente negrita
  $pdf->SetFillColor(222, 20, 45);
  $pdf->SetTextColor(255,255,255); // Texto en color blanco
  $pdf->Cell(190, 6, utf8_decode('INGRESO DE ARTICULOS'), 1, 1, 'C', 1); 
  $pdf->SetTextColor(0, 0, 0); // Texto en color negro
  $pdf->SetFillColor(205, 213, 192);
  $pdf->SetFont('Arial','',10); 

//Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
$cols=array( "CODIGO"=>30,
             "DESCRIPCION"=>68,
             "CANTIDAD"=>22,
             "P.C."=>25,
             "P.V."=>20,
             "SUBTOTAL"=>25);
$pdf->addCols( $cols);

$cols=array( "CODIGO"=>"L",
             "DESCRIPCION"=>"L",
             "CANTIDAD"=>"C",
             "P.C."=>"C",
             "P.V." =>"C",
             "SUBTOTAL"=>"C");
$pdf->addLineFormat( $cols);
//Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
$y= 79;


//Obtenemos todos los detalles de la venta actual
$rsptad = $ingreso->ingresoDetalle($_GET["id"]);

while ($regd = $rsptad->fetch_object()) {
  $line = array(  
              "CODIGO"=> "$regd->Codigo",
              "DESCRIPCION"=> utf8_decode("$regd->Nombre"),
              "CANTIDAD"=> "$regd->Cantidad_ingreso",
              "P.C."=> "$regd->Precio_compra",
              "P.V." => "$regd->Precio_venta",
              "SUBTOTAL"=> "$regd->Subtotal");
            $size = $pdf->addLine( $y, $line );
            $y   += $size + 2;
}



//Mostramos el impuesto


//Convertimos el total en letras
require_once "Letras.php";
$V=new CifrasEnLetras(); 
$con_letra=strtoupper($V->convertirEurosEnLetras($regc->Preciototal));
$pdf->addCadreTVAs(utf8_decode($con_letra));

//Mostramos el impuesto
$pdf->addTVAs( 0, $regc->Preciototal,"Bs/ ");
$pdf->addCadreEurosFrancs("");
$pdf->Output('Recibo_de_Ingreso_'. $regc->Numero . '.pdf', 'I');


}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush();
?>