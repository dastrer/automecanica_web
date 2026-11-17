//variable total
var tabla;
//Función que se ejecuta al inicio
//da referencia a otras funciones que vamos a implementar
function init(){
    
    $.post("../ajax/consulta_servicio.php?op=selectVehiculo", function(r){
        $("#Idvehiculo").html(r);
        $('#Idvehiculo').selectpicker('refresh');
    
    }); 
 
}

//Función Listar1 
function listartodo()
{ 
    var fecha_inicio = "Todos";
	var fecha_fin = "Todos";
    var Idvehiculo = "Todos";
	tabla=$('#tbllistado').DataTable(
	{   
		"responsive": true,
		"autoWidth": false,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"processing": true,//Activamos el procesamiento del datatables
		"ajax":
				{
                    url: '../ajax/consulta_servicio.php?op=listar',
                    data:{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,Idvehiculo:Idvehiculo},
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
        "drawCallback": function(settings) {
            var api = this.api();
          

            var totalMonto = api.column(5).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalmonto').html('Bs/.'+totalMonto.toFixed(2));

            var totalCambio = api.column(6).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalcambio').html('Bs/.'+totalCambio.toFixed(2));

            var totalServicio = api.column(7).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalServicio').html('Bs/.'+totalServicio.toFixed(2));

    
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
    var Idvehiculo = $("#Idvehiculo").val();
	tabla=$('#tbllistado').dataTable(
	{   
		"responsive": true,
		"autoWidth": false,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"processing": true,//Activamos el procesamiento del datatables
		"ajax":
				{
                   
                    url: '../ajax/consulta_servicio.php?op=listar',
                    data:{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,Idvehiculo:Idvehiculo},
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
        "drawCallback": function(settings) {
            var api = this.api();
        
            var totalMonto = api.column(5).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalmonto').html('Bs/.'+totalMonto.toFixed(2));

            var totalCambio = api.column(6).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalcambio').html('Bs/.'+totalCambio.toFixed(2));

            var totalVenta = api.column(7).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalServicio').html('Bs/.'+totalVenta.toFixed(2));

    
        },
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}


//Función Listar 3
function listartV()
{ 
    var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();
    var Idvehiculo = "Todos";
	tabla=$('#tbllistado').dataTable(
	{   
		"responsive": true,
		"autoWidth": false,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"processing": true,//Activamos el procesamiento del datatables
		"ajax":
				{
                   
                    url: '../ajax/consulta_servicio.php?op=listar',
                    data:{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,Idvehiculo:Idvehiculo},
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
        "drawCallback": function(settings) {
            var api = this.api();
          

            var totalMonto = api.column(5).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalmonto').html('Bs/.'+totalMonto.toFixed(2));

            var totalCambio = api.column(6).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalcambio').html('Bs/.'+totalCambio.toFixed(2));

            var totalServicio = api.column(7).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalServicio').html('Bs/.'+totalServicio.toFixed(2));

    
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
    var Idvehiculo = $("#Idvehiculo").val();
	tabla=$('#tbllistado').DataTable(
	{   
		"responsive": true,
		"autoWidth": false,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"processing": true,//Activamos el procesamiento del datatables
		"ajax":
				{
                    url: '../ajax/consulta_servicio.php?op=listar',
                    data:{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,Idvehiculo:Idvehiculo},
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
        "drawCallback": function(settings) {
            var api = this.api();
          

            var totalMonto = api.column(5).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalmonto').html('Bs/.'+totalMonto.toFixed(2));

            var totalCambio = api.column(6).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalcambio').html('Bs/.'+totalCambio.toFixed(2));

            var totalServicio = api.column(7).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalServicio').html('Bs/.'+totalServicio.toFixed(2));

    
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
function vehiculo() 
{
	var checkBoxtp = document.getElementById("TodosVehiculos");
	var text = document.getElementById("Idvehiculo");

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
	var checkBoxtV = document.getElementById("TodosVehiculos");
	if(checkBoxtF.checked == true && checkBoxtV.checked == true)
	{
		listartodo();
	}
    else if(checkBoxtF.checked == true)
	{
		listartF();
	}
    else if(checkBoxtV.checked == true)
    {
        listartV();
    }
    else
    {
        listar();
    }
	}


    function generarPDF() {
        var checkBoxtF = document.getElementById("Todasfechas");
        var checkBoxtV = document.getElementById("TodosVehiculos");
        if(checkBoxtF.checked == true && checkBoxtV.checked == true)
        {
            PDF();
        }
        else if(checkBoxtF.checked == true)
        {
            PDF2();
        }
        else if(checkBoxtV.checked == true)
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
        var Idvehiculo = "Todos";
    
        // Abre una nueva ventana con el PDF después de un breve retraso
        setTimeout(function() {
            window.open('../reportes/rptconsulta_servicio.php?fecha_inicio=' + fecha_inicio + '&fecha_fin=' + fecha_fin + '&Idvehiculo=' + Idvehiculo, '_blank');
        }, 1);
    }

    function PDF2() {

        var fecha_inicio = "Todos";
        var fecha_fin = "Todos";
        var Idvehiculo = $("#Idvehiculo").val();
    
        // Abre una nueva ventana con el PDF después de un breve retraso
        setTimeout(function() {
            window.open('../reportes/rptconsulta_servicio.php?fecha_inicio=' + fecha_inicio + '&fecha_fin=' + fecha_fin + '&Idvehiculo=' + Idvehiculo, '_blank');

        }, 1);
    }

    
    function PDF3() {

    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var Idvehiculo = "Todos";
        // Abre una nueva ventana con el PDF después de un breve retraso
        setTimeout(function() {
            window.open('../reportes/rptconsulta_servicio.php?fecha_inicio=' + fecha_inicio + '&fecha_fin=' + fecha_fin + '&Idvehiculo=' + Idvehiculo, '_blank');

        }, 1);
    }

    function PDF4() {

        var fecha_inicio = $("#fecha_inicio").val();
        var fecha_fin = $("#fecha_fin").val();
        var Idvehiculo = $("#Idvehiculo").val();
            // Abre una nueva ventana con el PDF después de un breve retrasoconsulta_venta_gral
            setTimeout(function() {
            window.open('../reportes/rptconsulta_servicio.php?fecha_inicio=' + fecha_inicio + '&fecha_fin=' + fecha_fin + '&Idvehiculo=' + Idvehiculo, '_blank');

            }, 1);
    }




    

init();