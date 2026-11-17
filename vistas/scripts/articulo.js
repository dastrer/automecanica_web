// variable total
var tabla;
var tabla2;
var tabla3;
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });
// Funcion que se ejecuta al inicio
//da referencia a otras funciones que vamos a implementar
function init()
{   listar();
    mostrarform(false);
    updateInfoBoxes();
    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);
    })  
  
	    $.post("../ajax/articulo.php?op=selectCategoriamostrar", function(r){
		$("#Idcategoria2").html(r);
		$('#Idcategoria2').selectpicker('refresh');
		});

		$.post("../ajax/articulo.php?op=selectCategoria", function(r){
		$("#Idcategoria").html(r);
		$('#Idcategoria').selectpicker('refresh');
		});

		$.post("../ajax/articulo.php?op=selectUnidad", function(r){
		$("#Idunidadmedida").html(r);
		$('#Idunidadmedida').selectpicker('refresh');
		});


	$("#Imagenmuestra").hide();
	bsCustomFileInput.init();


}

function updateInfoBoxes() {
	$.ajax({
		url:  '../ajax/articulo.php?op=listarconsulta',
		method: 'GET',
		dataType: 'json',
		success: function(data) {
			$('#total').text(data.total);
			$('#totalsb').text(data.totalsb);
			$('#totalstk').text(data.totalstk);
			$('#totald').text(data.totald);
		}, 
		error: function(xhr, status, error) {
			console.error('Error fetching data:', error);
		}
	});
}
// Actualizar cada 2 segundos (2000 milisegundos)
setInterval(updateInfoBoxes, 1000);




//funtion limpiar
//los objetos que vamos aimplementar lo va dejar en vacio
function limpiar()
{
 
	$("#Nombre").val("");
	$("#Descripcion").val("");
	$("#Marca").val("");
	$("#Codigo").val("");
	$("#Cantidad").val("");
	$("#Imagen").val(''); 
	$('.custom-file-label[for="Imagen"]').text('Seleccione Archivo');
	$("#Imagenmuestra").attr("src", "");
    $("#Imagenactual").val("");
	$("#Imagenmuestra").hide();
	$("#print").hide();
	$("#Idarticulo").val("");

}

//Function mostrar formulario
//Function javascript para controlar que este activa el formulario
// y desactiva el listado y asi viseversa -> flag=bandera, hide=oculto
//show=visible
function mostrarform(flag)
{
    limpiar();
    if(flag)
    {
        $("#listadoregistros").hide(),
		$(".info-box").hide(); 
        $("#formularioregistros").show(); 
        $("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();

    }
    else
    {
        $("#listadoregistros").show(),
        $("#formularioregistros").hide();
        $("#btnagregar").show();
		$(".info-box").show(); 
    }
}

//fUNCION CANCELAR FORM PARA OCULTAR EL FORMULARIO
function cancelarform()
{
limpiar();
mostrarform(false);
}
// funcion listar-> peticion ajax al archivo categoria.php y le evie 
// un valor ala variable OP que la variable categoria.php lo esta resiviendo 
//mediente el metodo GET
//Función Listar 
function listar()
{   
    var Idcategoria2 = "Todos";
    var Stock = "Todos";
	tabla=$('#tbllistado').DataTable(
	{   
		"responsive": true,
		"autoWidth": false,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"processing": true,//Activamos el procesamiento del datatables
		"ajax":
				{
					url: '../ajax/articulo.php?op=listar',
					data:{Idcategoria2:Idcategoria2,Stock:Stock},
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"language": {
			"zeroRecords": "No se encontraron resultados",
			"lengthMenu": "Mostrar _MENU_ registros",
            "info": "Mostrando  _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
			"infoFiltered": "(filtrado de _MAX_ registros totales)",
			"sSearch": "Buscar:",
			"paginate": {
                "first": "Primero","last": "Último",
                "next": "Siguiente","previous": "Anterior"
            }
        },
		"destroy": true,
		"pageLength": 5, // Paginación
	    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
	});
}

function listarStockBajo()
{
	var Idcategoria2 = "Todos";
	var Stock = "Bajo";
	tabla2=$('#tbllistado').DataTable(
	{   
		"responsive": true,
		"autoWidth": false,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"processing": true,//Activamos el procesamiento del datatables
		"ajax":
				{
					url: '../ajax/articulo.php?op=listar',
					data:{Idcategoria2:Idcategoria2,Stock:Stock},
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"language": {
			"zeroRecords": "No se encontraron resultados",
			"lengthMenu": "Mostrar _MENU_ registros",
            "info": "Mostrando  _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
			"infoFiltered": "(filtrado de _MAX_ registros totales)",
			"sSearch": "Buscar:",
			"paginate": {
                "first": "Primero","last": "Último",
                "next": "Siguiente","previous": "Anterior"
            }
        },
		"destroy": true,
		"pageLength": 5, // Paginación
	    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
	});

}
function listarCategoria()
{
	var Idcategoria2 = $("#Idcategoria2").val();
	var Stock = "Todos";

	tabla3=$('#tbllistado').DataTable(
	{   
		"responsive": true,
		"autoWidth": false,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"processing": true,//Activamos el procesamiento del datatables
		"ajax":
				{
					url: '../ajax/articulo.php?op=listar',
					data:{Idcategoria2:Idcategoria2,Stock:Stock},
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"language": {
			"zeroRecords": "No se encontraron resultados",
			"lengthMenu": "Mostrar _MENU_ registros",
            "info": "Mostrando  _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
			"infoFiltered": "(filtrado de _MAX_ registros totales)",
			"sSearch": "Buscar:",
			"paginate": {
                "first": "Primero","last": "Último",
                "next": "Siguiente","previous": "Anterior"
            }
        },
		"destroy": true,
		"pageLength": 5, // Paginación
	    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
	});

}




// funcion para guardar y editar

function guardaryeditar(e) {
	e.preventDefault(); // No se activará la acción predeterminada del evento
  
	bootbox.confirm("¿Está Seguro de guardar el articulo?", function(result) {
	  if (result) {
		var formData = new FormData($("#formulario")[0]);
  
		$.ajax({
		  url: "../ajax/articulo.php?op=guardaryeditar",
		  type: "POST",
		  data: formData,
		  contentType: false,
		  processData: false,
   
		  success: function(datos) {
			Toast.fire({icon: 'success',title: datos,});
			mostrarform(false);
			tabla.ajax.reload();

		  }
		});
  
		limpiar();
	  }
	});
  }

//muestra datos para editar los registros
function mostrar(Idarticulo)
{
    //direccion del ajax para verificar las opciones o lo que ejecutara el boton
    $.post("../ajax/articulo.php?op=mostrar", {Idarticulo:Idarticulo },
    function(data,status)
    {
        data=JSON.parse(data);
        //mostramos el formulario
        mostrarform(true);
 
        //Jala los valores de la base de datos el data son los campos  y 
        //el # es nombre de cuadro de texto
        
        $("#Nombre").val(data.Nombre);
        $("#Descripcion").val(data.Descripcion);
        $("#Marca").val(data.Marca);
     	$("#Idunidadmedida").val(data.Idunidadmedida);
	    $('#Idunidadmedida').selectpicker('refresh');
        $("#Codigo").val(data.Codigo);
        $("#Cantidad").val(data.Cantidad);
        $("#Idcategoria").val(data.Idcategoria);
	    $('#Idcategoria').selectpicker('refresh');
        $("#Idarticulo").val(data.Idarticulo);
		$("#Imagenmuestra").show();
		$("#Imagenmuestra").attr("src","../files/articulo/"+data.Imagen);
        $("#Imagenactual").val(data.Imagen);
		generarbarcode();
    }
    )
}

//desactivar los activos mediante el ID
function desactivar(Idarticulo)
{
    bootbox.confirm("¿Esta Seguro de desactivar el articulo?", function(result){
        if(result)
        {
            $.post("../ajax/articulo.php?op=desactivar", {Idarticulo : Idarticulo},
            function(e){
			    Toast.fire({icon: 'success',title: e,});
                tabla.ajax.reload();
            });
        }
    })
}

//funcion para activar los actuvos mediante el ID
function activar(Idarticulo)
{
    bootbox.confirm("¿esta seguro que quiere activar el articulo?", 
    function(result)
    {
        if(result)
        {
            $.post("../ajax/articulo.php?op=activar",{Idarticulo : Idarticulo},
            function(e)
            {
			    Toast.fire({icon: 'success',title: e,});
                tabla.ajax.reload();
            }
            );
        }
    })
}


  //check box para bloquear las celdas
  function Function1() {
	var checkBox = document.getElementById("TodasCategoria");
	var checkBox1 = document.getElementById("Stockbajo");
	var text = document.getElementById("Idcategoria2");
	if (checkBox.checked == true) {
	  text.disabled = true;
      $(text).selectpicker('refresh');
	  checkBox1.disabled = true; 
	} else {
	  text.disabled = false;
      $(text).selectpicker('refresh');
	  checkBox1.disabled = false;
	}
  }
  
  function Function2() {
	var checkBox = document.getElementById("Stockbajo");
    var checkBox2 = document.getElementById("TodasCategoria");
	var text = document.getElementById("Idcategoria2");
	if (checkBox.checked == true){
	  text.disabled = true;
      $(text).selectpicker('refresh');
      checkBox2.disabled=true;
	
	} else {
	  text.disabled = false;
      $(text).selectpicker('refresh');
      checkBox2.disabled=false;
	}
  }

  function Buscar() {
	var checkBoxtc = document.getElementById("TodasCategoria");
	var checkBoxsb = document.getElementById("Stockbajo");
	

	if(checkBoxtc.checked == true)
	{
		listar();
	}else if(checkBoxsb.checked == true )
	{
		listarStockBajo();
	}else 
	{
		listarCategoria();
	}

  }

  function generarPDF() {
	var checkBoxtc = document.getElementById("TodasCategoria");
	var checkBoxsb = document.getElementById("Stockbajo");

	if(checkBoxtc.checked == true)
		{
			var Idcategoria2 = "Todos";
			var Stock = "Todos";
			// Abre una nueva ventana con el PDF después de un breve retraso
			setTimeout(function() {
				window.open('../reportes/rptarticulo.php?Idcategoria2=' + Idcategoria2 + '&Stock=' + Stock, '_blank');
			}, 1);

		}else if(checkBoxsb.checked == true )
		{
			var Idcategoria2 = "Todos";
	        var Stock = "Bajo";
			// Abre una nueva ventana con el PDF después de un breve retraso
			setTimeout(function() {
				window.open('../reportes/rptarticulo.php?Idcategoria2=' + Idcategoria2 + '&Stock=' + Stock, '_blank');
			}, 1);
			
		}else 
		{
			var Idcategoria2 = $("#Idcategoria2").val();
	        var Stock = "Todos";
		   // Abre una nueva ventana con el PDF después de un breve retraso
		   setTimeout(function() {
			window.open('../reportes/rptarticulo.php?Idcategoria2=' + Idcategoria2 + '&Stock=' + Stock, '_blank');
		   }, 1);
		}

	}
		
		//función para generar el código de barras
		function generarbarcode()
		{
			codigo=$("#Codigo").val();
			JsBarcode("#barcode", codigo);
			$("#print").show();
		}

		//Función para imprimir el Código de barras
		function imprimir()
		{
			$("#print").printArea();
		}
init();