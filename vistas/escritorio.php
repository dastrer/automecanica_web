<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ($_SESSION['Escritorio'] == 1) {

    require_once "../modelos/Consulta.php";
    $consulta = new Consultas();

    $rspta_vhoy = $consulta->ventahoy();
    $regvh = $rspta_vhoy->fetch_object();
    $ventahoy = $regvh->ventahoy;

    $rspta_ih = $consulta->ingresohoy();
    $regih = $rspta_ih->fetch_object();
    $ingresohoy = $regih->ingresohoy;

    $rspta_ca = $consulta->cambiohoy();
    $regca = $rspta_ca->fetch_object();
    $totalcambio = $regca->cambiohoy;

    $rspta_mo = $consulta->efectivohoy();
    $regmo = $rspta_mo->fetch_object();
    $totalmonto = $regmo->montohoy;

    $rspta_to = $consulta->total_venta_anio();
    $regto = $rspta_to->fetch_object();
    $totalVTO = $regto->total_venta_anio;


    //GRAFICOS
    //Datos para mostrar el gráfico de barras de las total
    $totalingre = $consulta->total_ingreso();
    $fechamesI = '';
    $totalI = '';
    while ($regcompra = $totalingre->fetch_object()) {
      $fechamesI .= '"' . $regcompra->fecha . '",';
      $totalI .= $regcompra->total_ingreso . ',';
    }


    //Datos para mostrar el gráfico de barras de las total
    $totalventa = $consulta->total_venta();
    $fechames = '';
    $totalV = '';
    while ($regventa = $totalventa->fetch_object()) {
      $fechames .= '"' . $regventa->fecha . '",';
      $totalV .= $regventa->total_venta . ',';
    }
    // Datos para mostrar el gráfico de barras de las total sevicio
    $totalservicio = $consulta->total_servicio();
    $fechames_S = '';
    $total_S = '';
    while ($regservicio = $totalservicio->fetch_object()) {
      $fechames_S .= '"' . $regservicio->fecha . '",';
      $total_S .= $regservicio->total_servicio . ',';
    }
    // Datos para mostrar el gráfico de donas de las categorias
    $rspta = $consulta->articulosPorCategoria();
    $categorias = '';
    $cantidades = '';

    while ($reg = $rspta->fetch_object()) {
      $categorias .= "'" . $reg->categoria . "',";
      $cantidades .= $reg->cantidad . ",";
    }

    ?>

    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
      <div class="pcoded-wrapper">
        <div class="pcoded-content">
          <div class="pcoded-inner-content">
            <div class="main-body">
              <div class="page-wrapper">
                <div class="page-header">
                  <div class="page-block">
                    <div class="row align-items-center">
                      <div class="col-md-12">
                        <div class="page-header-title">
                          <h5 class="m-b-10">Escritorio</h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- [ breadcrumb ] end -->
                <!-- [ Main Content ] start -->
                <div class="row">
                  <!-- product profit start -->
                  <div class="col-xl-3 col-md-6">
                    <div class="card prod-p-card bg-c-red">
                      <div class="card-body">
                        <div class="row align-items-center m-b-25">
                          <div class="col">
                            <h6 class="m-b-5 text-white">Ventas Hoy</h6>
                            <h5 class="m-b-0 text-white">Bs<?php echo $ventahoy; ?> </h5>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-money-bill-alt text-c-red f-18"></i>
                          </div>
                        </div>
                        <a href="venta.php">
                          <p class="m-b-0 text-white"> Mas informacion</p>
                        </a>
                      </div>
                    </div>
                  </div>

                  <div class="col-xl-3 col-md-6">
                    <div class="card prod-p-card bg-c-blue">
                      <div class="card-body">
                        <div class="row align-items-center m-b-25">
                          <div class="col">
                            <h6 class="m-b-5 text-white">Ingreso Hoy</h6>
                            <h5 class="m-b-0 text-white">Bs <?php echo $ingresohoy; ?></h5>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-store text-c-blue f-18"></i>
                          </div>
                        </div>
                        <a href="ingreso.php">
                          <p class="m-b-0 text-white"> Mas informacion</p>
                        </a>
                      </div>
                    </div>
                  </div>

                  <div class="col-xl-3 col-md-6">
                    <div class="card prod-p-card bg-c-green">
                      <div class="card-body">
                        <div class="row align-items-center m-b-25">
                          <div class="col">
                            <h6 class="m-b-5 text-white">Importe Hoy</h6>
                            <h5 class="m-b-0 text-white"> Bs <?php echo $totalmonto; ?></h5>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-dollar-sign text-c-green f-18"></i>
                          </div>
                        </div>
                        <a href="venta.php">
                          <p class="m-b-0 text-white"> Mas informacion</p>
                        </a>
                      </div>
                    </div>
                  </div>

                  <div class="col-xl-3 col-md-6">
                    <div class="card prod-p-card bg-c-yellow">
                      <div class="card-body">
                        <div class="row align-items-center m-b-25">
                          <div class="col">
                            <h6 class="m-b-5 text-white">Cambio Hoy</h6>
                            <h5 class="m-b-0 text-white">Bs <?php echo $totalcambio; ?></h5>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-coins text-c-yellow f-18"></i>
                          </div>
                        </div>
                        <a href="venta.php">
                          <p class="m-b-0 text-white"> Mas informacion</p>
                        </a>
                      </div>
                    </div>
                  </div>
                  <!-- [ bar-stacked Chart ] start -->
                  <div class="col-xl-6">
                    <div class="card">
                      <div class="card-header">
                        <h5>Ingresos</h5>
                      </div>
                      <div class="card-body">
                        <div id="morris-bar-stacked-chart" style="height:300px"></div>
                      </div>
                    </div>
                  </div>
                  <!-- [ bar-stacked Chart ] end -->

                  <div class="col-xl-6">
                    <div class="card">
                      <div class="card-header">
                        <h5>Categorias</h5>
                      </div>
                      <div class="card-body">
                        <div id="morris-donut-chart" style="height:300px"></div>
                      </div>
                    </div>
                  </div>
                  <!-- [ donut-Chart ] end -->
                  <!-- [ bar-stacked Chart ] start -->
                  <div class="col-xl-6">
                    <div class="card">
                      <div class="card-header">
                        <h5>Grafico Ventas</h5>
                      </div>
                      <div class="card-body">
                        <div id="morris-bar-stacked-chart2" style="height:300px"></div>
                      </div>
                    </div>
                  </div>
                  <!-- [ bar-stacked Chart ] end -->
                  <!-- [ bar-stacked Chart ] start -->
                  <div class="col-xl-6">
                    <div class="card">
                      <div class="card-header">
                        <h5>Grafico Servicios</h5>
                      </div>
                      <div class="card-body">
                        <div id="morris-bar-stacked-chart3" style="height:300px"></div>
                      </div>
                    </div>
                  </div>
                  <!-- [ bar-stacked Chart ] end -->
                </div>
                <!-- /.row -->



                <!-- [ Main Content ] end -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- [ Main Content ] end -->

    <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>

  <!-- ChartJS -->
  <script src="../assets/plugins/chart-morris/js/raphael.min.js"></script>
  <script src="../assets/plugins/chart-morris/js/morris.min.js"></script>
  <script type="text/javascript">

    'use strict';
    $(document).ready(function () {
      setTimeout(function () {
        //Bar chart 1
        // Convertimos PHP a arreglos JS
        var fechas = [<?php echo $fechamesI; ?>];
        var totales = [<?php echo $totalI; ?>];

        // Armamos los datos para Morris
        var datos = [];

        for (var i = 0; i < fechas.length; i++) {
          datos.push({
            y: fechas[i],
            a: totales[i]
          });
        }
        // Ahora sí, creamos el gráfico Morris
        Morris.Bar({
          element: 'morris-bar-stacked-chart',
          data: datos,
          xkey: 'y',
          ykeys: ['a'],
          labels: ['Total Ingreso'],
          barSizeRatio: 0.50,
          barGap: 3,
          resize: true,
          responsive: true,
          barColors: ["#f57c00"]
        });

        //Bar chart 2
        // Convertimos PHP a arreglos JS
        var fechasV = [<?php echo $fechames; ?>];
        var totalesV = [<?php echo $totalV; ?>];

        // Armamos los datos para Morris
        var datos = [];

        for (var i = 0; i < fechasV.length; i++) {
          datos.push({
            y: fechasV[i],
            a: totalesV[i]
          });
        }
        // Ahora sí, creamos el gráfico Morris
        Morris.Bar({
          element: 'morris-bar-stacked-chart2',
          data: datos,
          xkey: 'y',
          ykeys: ['a'],
          labels: ['Total Venta'],
          barSizeRatio: 0.50,
          barGap: 3,
          resize: true,
          responsive: true,
          barColors: ["#007bff"]
        });

        //fin bar chart

        //Bar chart 3
        // Convertimos PHP a arreglos JS
        var fechas_S = [<?php echo $fechames_S; ?>];
        var totales_S = [<?php echo $total_S; ?>];

        // Armamos los datos para Morris
        var datos = [];

        for (var i = 0; i < fechas_S.length; i++) {
          datos.push({
            y: fechas_S[i],
            a: totales_S[i]
          });
        }
        // Ahora sí, creamos el gráfico Morris
        Morris.Bar({
          element: 'morris-bar-stacked-chart3',
          data: datos,
          xkey: 'y',
          ykeys: ['a'],
          labels: ['Total Servicio'],
          barSizeRatio: 0.50,
          barGap: 3,
          resize: true,
          responsive: true,
          barColors: ["#ff0000"] 
        });

        //fin bar chart


        // [ Donut-chart ] Start
        // Convertimos PHP a arreglos JS
        var categorias = [<?php echo $categorias; ?>];
        var cantidades = [<?php echo $cantidades; ?>];

        // Armamos los datos para el Donut
        var datosDonut = [];

        for (var i = 0; i < categorias.length; i++) {
          datosDonut.push({
            label: categorias[i],
            value: cantidades[i]
          });
        }

        // Ahora sí, creamos el Morris Donut
        var graph = Morris.Donut({
          element: 'morris-donut-chart',
          data: datosDonut,
          colors: [
            '#3949AB',
            '#463699',
            '#e52d27',
            '#f57c00',
          ],
          resize: true,
          formatter: function (x) {
            return "Cantidad: " + x;
          }
        });


      }, 700);
      // [ Donut-chart ] end
    });


  </script>



<?php

}
ob_end_flush();
?>