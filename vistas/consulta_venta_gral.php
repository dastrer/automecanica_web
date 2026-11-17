<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ($_SESSION['ConsultaV'] == 1) {
    date_default_timezone_set('America/La_Paz');
    $fecha_actual = date("Y-m-d");
    ?>

    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
      <div class="pcoded-wrapper">
        <div class="pcoded-content">
          <div class="pcoded-inner-content">
            <div class="main-body">
              <div class="page-wrapper">
                <div class="row">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-header">
                        <h4 class="box-title">
                          Consulta de Venta
                          <button id="btnGenerarPDF" class="btn btn-danger" onclick="generarPDF()"> <i
                              class="fas fa-file-pdf"></i> Reporte PDF</button>
                        </h4>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body" id="listadoregistros">
                        <div class="form-row">
                          <div class="col-xl-4 col-md-6">
                            <div class="card prod-p-card bg-c-blue">
                              <div class="card-body">
                                <div class="row align-items-center m-b-10">
                                  <div class="col">
                                    <h6 class="m-b-5 text-white">Total</h6>
                                    <h3 class="m-b-0 text-white" id="totalventa">Bs/.0.00</h3>
                                  </div>
                                  <div class="col-auto">
                                    <i class="fas fa-coins text-c-blue f-18"></i>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xl-4 col-md-6">
                            <div class="card prod-p-card bg-c-green">
                              <div class="card-body">
                                <div class="row align-items-center m-b-10">
                                  <div class="col">
                                    <h6 class="m-b-5 text-white">Total Efectivo</h6>
                                    <h3 class="m-b-0 text-white" id="totalmonto">Bs/.0.00</h3>
                                  </div>
                                  <div class="col-auto">
                                    <i class="fas fa-dollar-sign text-c-green f-18"></i>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-xl-4 col-md-6">
                            <div class="card prod-p-card bg-c-yellow">
                              <div class="card-body">
                                <div class="row align-items-center m-b-10">
                                  <div class="col">
                                    <h6 class="m-b-5 text-white">Total Cambios</h6>
                                    <h3 class="m-b-0 text-white" id="totalcambio">Bs/.0.00</h3>
                                  </div>
                                  <div class="col-auto">
                                    <i class="fas fa-dollar-sign text-c-yellow f-18"></i>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label> Desde la Fecha: </label>
                            <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio"
                              value="<?php echo date("Y-m-d"); ?>">
                            <div class="icheck-primary d-inline-block" style="margin-top: 10px;">
                              <input type="checkbox" id="Todasfechas" onclick="fechas()">
                              <label for="Todasfechas"> Todas las Fechas</label>
                            </div>
                          </div>

                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label> Hasta la Fecha: </label>
                            <input type="date" class="form-control" name="fecha_fin" id="fecha_fin"
                              value="<?php echo date("Y-m-d"); ?>">
                          </div>

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Clientes:</label>
                            <select name="Idcliente" id="Idcliente" class="form-control selectpicker"
                              data-live-search="true" required>
                            </select>
                            <div class="icheck-primary d-inline-block" style="margin-top: 10px;">
                              <input type="checkbox" id="TodosClientes" onclick="cliente()">
                              <label for="TodosClientes"> Todos los Clientes</label>
                            </div>
                            <button class="btn btn-primary" onclick="Buscar()" style="margin-top: 10px; float: right;"><span
                                class="fas fa-search"> </span> Buscar</button>
                          </div>
                        </div>
                        <div class="table-responsive">
                          <table id="tbllistado" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th class="centered">Opciones</th>
                                <th class="centered">Fecha</th>
                                <th class="centered">Cliente</th>
                                <th class="centered">Documento</th>
                                <th class="centered">Comprobante</th>
                                <th class="centered">Usuario</th>
                                <th class="centered">Pago</th>
                                <th class="centered">Monto</th>
                                <th class="centered">Cambio</th>
                                <th class="centered">Total </th>
                                <th class="centered">Estado</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th class="centered">Opciones</th>
                                <th class="centered">Fecha</th>
                                <th class="centered">Cliente</th>
                                <th class="centered">Documento</th>
                                <th class="centered">Comprobante</th>
                                <th class="centered">Usuario</th>
                                <th class="centered">Pago</th>
                                <th class="centered">Monto</th>
                                <th class="centered">Cambio</th>
                                <th class="centered">Total </th>
                                <th class="centered">Estado</th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                      <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
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
  <script type="text/javascript" src="scripts/consulta_venta_gral.js"></script>
<?php
}
ob_end_flush();
?>