//variable total
var tabla;
//Función que se ejecuta al inicio
//da referencia a otras funciones que vamos a implementar
function init(){
     
     //Cargamos los items al select cliente  
     listar();
     $("#fecha_inicio").change(listar);
     $("#fecha_fin").change(listar);

}


function listar()
{   var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
	tabla=$('#tbllistado').DataTable(
	{   
		"responsive": true,
		"autoWidth": false,
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"processing": true,//Activamos el procesamiento del datatables
        "ajax":
        {
            url: '../ajax/consulta_ingreso.php?op=listar',
            data:{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin},
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
          
            var totalRep = api.column(5).data().reduce(function (a, b) {
                return Number(a) + Number(b);
            }, 0);
            $('#totalingreso').html('Bs/.'+totalRep.toFixed(2));
    
        },
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	});
}



 function generarPDF() {
        var fecha_inicio = $("#fecha_inicio").val();
        var fecha_fin = $("#fecha_fin").val();
    
        // Abre una nueva ventana con el PDF después de un breve retraso
        setTimeout(function() {
            window.open('../reportes/rptconsulta_ingreso.php?fecha_inicio=' + fecha_inicio + '&fecha_fin=' + fecha_fin, '_blank');
        }, 1);
    }
    

init();