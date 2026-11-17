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
if ($_SESSION['Almacen'] == 1 || $_GET['op'] == 'listarconsulta')
{	 

require_once "..\modelos\Articulo.php";

$articulo =new Articulo();

$Nombre=isset($_POST["Nombre"])? limpiarCadena($_POST["Nombre"]):"";
$Descripcion=isset($_POST["Descripcion"])? limpiarCadena($_POST["Descripcion"]):"";
$Marca=isset($_POST["Marca"])? limpiarCadena($_POST["Marca"]):"";
$Idunidadmedida=isset($_POST["Idunidadmedida"])? limpiarCadena($_POST["Idunidadmedida"]):"";
$Codigo=isset($_POST["Codigo"])? limpiarCadena($_POST["Codigo"]):"";
$Cantidad=isset($_POST["Cantidad"])?limpiarCadena($_POST["Cantidad"]):"";
$Idarticulo=isset($_POST["Idarticulo"])? limpiarCadena($_POST["Idarticulo"]):""; 
$Idcategoria=isset($_POST["Idcategoria"])? limpiarCadena($_POST["Idcategoria"]):""; 
$Imagen=isset($_POST["Imagen"])? limpiarCadena($_POST["Imagen"]):""; 


//segun la peticion(operacion)que realizen mediwnte el metodo GET
//devolvera un valor mediante la tecnolocia ajax, para eso utilizaremos swtch
switch($_GET["op"])
{
    case 'guardaryeditar':
        		// Verifica si se ha subido una nueva imagen
		if (!file_exists($_FILES['Imagen']['tmp_name']) || !is_uploaded_file($_FILES['Imagen']['tmp_name']))
		{
			$Imagen=$_POST["Imagenactual"];
		}
		else 
		{
			$ext = explode(".", $_FILES["Imagen"]["name"]);
			if ($_FILES['Imagen']['type'] == "image/jpg" || $_FILES['Imagen']['type'] == "image/jpeg" || $_FILES['Imagen']['type'] == "image/png")
			{
				$Imagen = round(microtime(true)) . '.' . end($ext);
				move_uploaded_file($_FILES["Imagen"]["tmp_name"], "../files/articulo/" . $Imagen); 
			}
		}
        //empty esta para verificar si la variable esta vacia
        if(empty($Idarticulo))
        {
            $rspta=$articulo->verificarDuplicidad($Codigo);

			if($rspta->num_rows > 0) {
				echo "Esta Articulo ya fue Registrado";
			} else {
            $rspta=$articulo->insertar($Nombre,$Descripcion,$Marca,$Idunidadmedida,$Cantidad,$Codigo,$Idcategoria,$Imagen);
            echo $rspta ? "Articulo  registrado" : "Articulo  no se pudo registrar";
            }
        }
        else
        {
            $rspta=$articulo->editar($Idarticulo,$Nombre,$Descripcion,$Marca,$Idunidadmedida,$Cantidad,$Codigo,$Idcategoria,$Imagen);
            echo $rspta ? "Articulo  actualizado" : "Articulo no se pudo actualizar";
        }
        break;

        case 'desactivar':
        
        $rspta=$articulo->desactivar($Idarticulo);
        echo $rspta ? "Articulo desactivado" : "Articulo no se pudo desactivar";
      
        break;
 
        case 'activar':
        
            $rspta=$articulo->activar($Idarticulo);
            echo $rspta ? "Articulo activado" : "Articulo no se pudo activar";
        break;
        
        case 'mostrar': 
            $rspta=$articulo->mostrar($Idarticulo);
            //codificar el resultado utilizado json
            echo json_encode($rspta);
           
        break;
 
        case 'listar':

        $Idcategoria2=$_REQUEST["Idcategoria2"];
        $Stock=$_REQUEST["Stock"];
     
        $rspta=$articulo->listar($Idcategoria2,$Stock);
        //vamos a declarar un array
        //donde $data alamacenaraa todos los registros que quiero listar
        $data= Array();
        //va a recorrer todos los registros uno a uno de forma independiente con $reg
        while($reg=$rspta->fetch_object())
        {
            $data[]=array( 
            "0" => ($reg->Estado) ? 
            '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->Idarticulo . ')"><i class="feather icon-edit-2"></i>
            </button>' .
            ' <button class="btn btn-danger btn-sm" onclick="desactivar(' . $reg->Idarticulo . ')"><i class="feather icon-x"></i>
            </button>' :
            '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->Idarticulo . ')"><i class="feather icon-edit-2"></i></button>' .
            ' <button class="btn btn-primary btn-sm" onclick="activar(' . $reg->Idarticulo . ')"><i class="feather icon-check"></i></button>',
            
            "1"=>$reg->Nombre,
            "2"=>$reg->Marca,
            "3"=>$reg->Codigo,
            "4"=>'<span class="badge badge-warning fs-6 px-3 py-2">'.$reg->Categoria.'</span>',
            "5"=>$reg->Cantidad." ".$reg->Unidadmedida, 
			"6"=>"<img src='../files/articulo/".$reg->Imagen."' height='50px' width='50px' loading='lazy' alt='Artículo'>",
            "7"=>($reg->Estado)?'<span class="badge badge-success">Activado</span>':
            '<span class="badge badge-danger">Desactivado</span>');

        }
        $results= array
        (
            "sEcho"=>1, //informacion para el data tables
            "iTotalRecords"=>count($data),//enviamaos el total de registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos total registros a visualizar
            "aaData"=>$data);

        //devolvemos los resultados mediante json
        echo json_encode($results);
        break;

        case "selectCategoria":
            require_once "../modelos/Categoria.php";
            $categoria = new Categoria();
    
            $rspta = $categoria->selectCategoria();
    
            while ($reg = $rspta->fetch_object())
                    {
                        echo '<option value=' . $reg->Idcategoria . '>' . $reg->Nombre . '</option>';
                    }
        break;

        case "selectCategoriamostrar":
            require_once "../modelos/Categoria.php";
            $categoria = new Categoria();
    
            $rspta = $categoria->selectCategoriamostrar();
    
            while ($reg = $rspta->fetch_object())
                    {
                        echo '<option value=' . $reg->Idcategoria . '>' . $reg->Nombre . '</option>';
                    }
        break;

        case "selectUnidad":
            require_once "../modelos/Unidad.php";
            $unidad = new Unidad();
    
            $rspta = $unidad->selectUnidad();
    
            while ($reg = $rspta->fetch_object())
                    {
                        echo '<option value=' . $reg->Idunidadmedida . '>' . $reg->Descripcion . '</option>';
                    }
        break;


        case 'listarconsulta':
	
            $rsptac = $articulo->contararticulo();
            $regc=$rsptac->fetch_object();
            $total=$regc->total;
          
            $rsptaa = $articulo->contararticulo2();
            $rega=$rsptaa->fetch_object();
            $totalsb=$rega->totalsb;
          
            $rsptad = $articulo->contararticulo3();
            $regd=$rsptad->fetch_object();
            $totalstk=$regd->totalstk;
          
            $rsptat = $articulo->contararticulo4();
            $regt=$rsptat->fetch_object();
            $totald=$regt->totald;
          
            echo json_encode([
              'total' => $total,
              'totalsb' => $totalsb,
              'totalstk' => $totalstk,
              'totald' => $totald,
          ]);
    
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