//variable total
var tabla;
//Función que se ejecuta al inicio
//da referencia a otras funciones que vamos a implementar
function init(){
    $.post("../ajax/consulta_venta.php?op=selectArticulo", function(r){
        $("#Idarticulo").html(r);
        $('#Idarticulo').selectpicker('refresh');
    
    }); 

}  
//Función Listar1 
function listartodo()
{ 
    var fecha_inicio = "Todos";
	var fecha_fin = "Todos";
    var Idarticulo = "Todos";
	tabla=$('#tbllistado').DataTable(
	{   
		"responsive": true,
		"autoWidth": false,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"processing": true,//Activamos el procesamiento del datatables
		"ajax":
				{
                    url: '../ajax/consulta_servicio_art.php?op=listar',
                    data:{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,Idarticulo:Idarticulo},
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
        "drawCallback": function() {
            var api = this.api();
        
            var totalc = api.column(4).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#cantidad').html(totalc.toFixed(0));

            var utilidad = api.column(8).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#total').html("Bs/."+utilidad.toFixed(2));
            var utilidad = api.column(9).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalcosto').html("Bs/."+utilidad.toFixed(2));
            var utilidad = api.column(10).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalutilidad').html("Bs/."+utilidad.toFixed(2));
   
        }, 
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	});
}

//Función Listar 2
function listartF()
{ 
    var fecha_inicio = "Todos";
	var fecha_fin = "Todos";
    var Idarticulo = $("#Idarticulo").val();
	tabla=$('#tbllistado').dataTable(
	{   
		"responsive": true,
		"autoWidth": false,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"processing": true,//Activamos el procesamiento del datatables
		"ajax":
				{
                   
                    url: '../ajax/consulta_servicio_art.php?op=listar',
                    data:{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,Idarticulo:Idarticulo},
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
        "drawCallback": function() {
            var api = this.api();
        
            var totalc = api.column(4).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#cantidad').html(totalc.toFixed(0));

            var utilidad = api.column(8).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#total').html("Bs/."+utilidad.toFixed(2));
            var utilidad = api.column(9).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalcosto').html("Bs/."+utilidad.toFixed(2));
            var utilidad = api.column(10).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalutilidad').html("Bs/."+utilidad.toFixed(2));
   
        }, 
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}


//Función Listar 3
function listartP()
{ 
    var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();
    var Idarticulo = "Todos";
	tabla=$('#tbllistado').dataTable(
	{   
		"responsive": true,
		"autoWidth": false,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"processing": true,//Activamos el procesamiento del datatables
		"ajax":
				{
                   
                    url: '../ajax/consulta_servicio_art.php?op=listar',
                    data:{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,Idarticulo:Idarticulo},
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
        "drawCallback": function() {
            var api = this.api();
        
            var totalc = api.column(4).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#cantidad').html(totalc.toFixed(0));

            var utilidad = api.column(8).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#total').html("Bs/."+utilidad.toFixed(2));
            var utilidad = api.column(9).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalcosto').html("Bs/."+utilidad.toFixed(2));
            var utilidad = api.column(10).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalutilidad').html("Bs/."+utilidad.toFixed(2));
   
        }, 
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}


//Función Listar 8
function listar()
{ 
    var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();
    var Idarticulo = $("#Idarticulo").val();
	tabla=$('#tbllistado').DataTable(
	{   
		"responsive": true,
		"autoWidth": false,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"processing": true,//Activamos el procesamiento del datatables
		"ajax":
				{
                    url: '../ajax/consulta_servicio_art.php?op=listar',
                    data:{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,Idarticulo:Idarticulo},
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
       "drawCallback": function() {
            var api = this.api();
        
            var totalc = api.column(4).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#cantidad').html(totalc.toFixed(0));

            var utilidad = api.column(8).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#total').html("Bs/."+utilidad.toFixed(2));
            var utilidad = api.column(9).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalcosto').html("Bs/."+utilidad.toFixed(2));
            var utilidad = api.column(10).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalutilidad').html("Bs/."+utilidad.toFixed(2));
   
        }, 
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	});
}

function fechas() {
	var checkBox = document.getElementById("Todasfechas");
	var text = document.getElementById("fecha_inicio");
	var text1 = document.getElementById("fecha_fin");

	if (checkBox.checked == true){
  
	  text.disabled = true;
	  text1.disabled = true;
	} else {
	  text.disabled = false;
	  text1.disabled = false;
	}
	
  }


//check box para bloquear las celdas
function articulos() 
{
	var checkBoxtp = document.getElementById("TodosArticulos");
	var text = document.getElementById("Idarticulo");

	if (checkBoxtp.checked == true){
  
	  text.disabled = true;
      $(text).selectpicker('refresh');
	} else {
	  text.disabled = false;
      $(text).selectpicker('refresh');
	}
}



  function Buscar() {
	var checkBoxtF = document.getElementById("Todasfechas");
	var checkBoxtP = document.getElementById("TodosArticulos");
	if(checkBoxtF.checked == true && checkBoxtP.checked == true)
	{
		listartodo();
	}
    else if(checkBoxtF.checked == true)
	{
		listartF();
	}
    else if(checkBoxtP.checked == true)
    {
        listartP();
    }
    else
    {
        listar();
    }
	}


    function generarPDF() {
        var checkBoxtF = document.getElementById("Todasfechas");
        var checkBoxtP = document.getElementById("TodosArticulos");
        if(checkBoxtF.checked == true && checkBoxtP.checked == true)
        {
            PDF();
        }
        else if(checkBoxtF.checked == true)
        {
            PDF2();
        }
        else if(checkBoxtP.checked == true)
        {
            PDF3();
        }
        else
        {
            PDF4();
        }
        }



    function PDF() {

        var fecha_inicio = "Todos";
        var fecha_fin = "Todos";
        var Idarticulo = "Todos";
    
        // Abre una nueva ventana con el PDF después de un breve retraso
        setTimeout(function() {
            window.open('../reportes/rptconsulta_servicio_art.php?fecha_inicio=' + fecha_inicio + '&fecha_fin=' + fecha_fin + '&Idarticulo=' + Idarticulo, '_blank');
        }, 1);
    }

    function PDF2() {

        var fecha_inicio = "Todos";
        var fecha_fin = "Todos";
        var Idarticulo = $("#Idarticulo").val();
    
        // Abre una nueva ventana con el PDF después de un breve retraso
        setTimeout(function() {
            window.open('../reportes/rptconsulta_servicio_art.php?fecha_inicio=' + fecha_inicio + '&fecha_fin=' + fecha_fin + '&Idarticulo=' + Idarticulo, '_blank');

        }, 1);
    }

    
    function PDF3() {

    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var Idarticulo = "Todos";
        // Abre una nueva ventana con el PDF después de un breve retraso
        setTimeout(function() {
            window.open('../reportes/rptconsulta_servicio_art.php?fecha_inicio=' + fecha_inicio + '&fecha_fin=' + fecha_fin + '&Idarticulo=' + Idarticulo, '_blank');

        }, 1);
    }

    function PDF4() {

        var fecha_inicio = $("#fecha_inicio").val();
        var fecha_fin = $("#fecha_fin").val();
        var Idarticulo = $("#Idarticulo").val();
            // Abre una nueva ventana con el PDF después de un breve retraso
            setTimeout(function() {
            window.open('../reportes/rptconsulta_servicio_art.php?fecha_inicio=' + fecha_inicio + '&fecha_fin=' + fecha_fin + '&Idarticulo=' + Idarticulo, '_blank');

            }, 1);
    }

    
    

init();