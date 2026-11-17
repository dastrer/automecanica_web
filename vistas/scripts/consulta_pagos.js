//variable total
var tabla;
//Función que se ejecuta al inicio
//da referencia a otras funciones que vamos a implementar
function init(){
     
     listar();
  

    $.post("../ajax/consulta_pagos.php?op=selectServicio", function(r){
        $("#Idservicio").html(r);
        $('#Idservicio').selectpicker('refresh');
    
    }); 
 
}

//Función Listar1 
function listar()
{ 
    var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();
    var Idservicio = $("#Idservicio").val();
	tabla=$('#tbllistado').DataTable(
	{   
		"responsive": true,
		"autoWidth": false,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"processing": true,//Activamos el procesamiento del datatables
		"ajax":
				{
                    url: '../ajax/consulta_pagos.php?op=listar',
                    data:{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,Idservicio:Idservicio},
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
          

            var acuenta = api.column(2).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#acuenta').html('Bs/.'+acuenta.toFixed(2));

            var monto = api.column(3).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#monto').html('Bs/.'+monto.toFixed(2));

            var cambio = api.column(4).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#cambio').html('Bs/.'+cambio.toFixed(2));

    
        },
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
	});
}






  function Buscar() {

        listar();
    
	}


    function generarPDF() {

        var fecha_inicio = $("#fecha_inicio").val();
        var fecha_fin = $("#fecha_fin").val();
        var Idservicio = $("#Idservicio").val();
            // Abre una nueva ventana con el PDF después de un breve retrasoconsulta_venta_gral
            setTimeout(function() {
            window.open('../reportes/rptconsulta_pagos.php?fecha_inicio=' + fecha_inicio + '&fecha_fin=' + fecha_fin + '&Idservicio=' + Idservicio, '_blank');
            }, 1);
    }




    

init();