<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ($_SESSION['Servicio'] == 1) {
    date_default_timezone_set('America/La_Paz');
    $fecha_actual = date("Y-m-d H:i:s");
    ?>
    <!--Contenido-->
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
                          Cotizaciones
                          <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)">
                            <i class="fa fa-plus-circle"></i> Agregar
                          </button>
                          <a href="../reportes/rptcotizacion.php" target="_blank"><button class="btn btn-info"><i
                                class="fa fa-clipboard"></i> Reporte</button></a>

                        </h4>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body" id="listadoregistros">
                        <div class="table-responsive">
                          <table id="tbllistado" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th class="centered">Opciones</th>
                                <th class="centered">Fecha</th>
                                <th class="centered">Comprobante</th>
                                <th class="centered">Vehiculo</th>
                                <th class="centered">Cliente</th>
                                <th class="centered">Usuario</th>
                                <th class="centered">Total</th>
                                <th class="centered">Estado</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th class="centered">Opciones</th>
                                <th class="centered">Fecha</th>
                                <th class="centered">Comprobante</th>
                                <th class="centered">Vehiculo</th>
                                <th class="centered">Cliente</th>
                                <th class="centered">Usuario</th>
                                <th class="centered">Total</th>
                                <th class="centered">Estado</th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                      <!-- /.card-body  -->

                      <div class="card-body" id="formularioregistros" style="display:none;">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-row">
                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                              <label>Vehiculo:</label>
                              <input type="hidden" name="Idcotizacion" id="Idcotizacion">
                              <select name="Idvehiculo" id="Idvehiculo" class="form-control selectpicker"
                                data-live-search="true" required>
                              </select>
                            </div>

                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                              <label>Marca:</label>
                              <input type="text" class="form-control" name="Marca" id="Marca" readonly>
                            </div>

                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                              <label>Cliente:</label>
                              <input type="text" class="form-control" name="Nombres" id="Nombres" readonly>
                            </div>

                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                              <label>Fecha(*):</label>
                              <input type="text" class="form-control" name="Fecha" id="Fecha"
                                value="<?php echo $fecha_actual; ?>" readonly>
                            </div>

                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                              <label>Tipo Comprobante:</label>
                              <input type="text" class="form-control" name="Tipo_comprobante" id="Tipo_comprobante"
                                value="Proforma" readonly>
                            </div>

                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                              <label>Numero Comprobante:</label>
                              <input type="text" class="form-control" name="Numero" id="Numero"
                                placeholder="Generacion automatica" readonly>
                            </div>

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <label for="Descripcion">Descripcion:</label>
                              <textarea class="form-control" name="Descripcion" id="Descripcion" rows="2"
                                placeholder="Descripcion del servicio" maxlength="200" required></textarea>
                            </div>

                            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                              <h5>Diagnóstico de vehiculo</h5>
                              <div class="table-responsive">
                                <table id="detalles_diagnostico" class="table table-bordered table-striped">
                                  <thead>
                                    <tr>
                                      <th>No.</th>
                                      <th>Descripción</th>
                                      <th>Bien</th>
                                      <th>Mal</th>
                                      <th>Precio</th>
                                      <th>Observaciones</th>
                                    </tr>
                                  </thead>
                                  <tbody></tbody>
                                  <tfoot>
                                    <tr>
                                      <th>TOTAL</th>
                                      <th>
                                        <h5 id="TotalCD">Bs/. 0.00</h5><input type="hidden" name="TotalC" id="TotalC">
                                      </th>
                                      <th colspan="4"></th>
                                    </tr>
                                  </tfoot>
                                </table>

                              </div>
                            </div>

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <div class="icheck-primary d-inline">
                                <input type="checkbox" id="Agregar_venta" onclick="Agregar_vent(true)">
                                <label for="Agregar_venta">
                                  <h4 class="d-inline">¿Agregar artículos de venta a la cotización?</h4>
                                </label>
                              </div>
                            </div>

                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <a data-toggle="modal" href="#myModal">

                                <button id="btnAgregarArt" class="btn btn-primary" style="display:none;"><span
                                        class="fa fa-plus"></span> Agregar
                                  Artículo</button>
                              </a>
                            </div>

                            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                              <div class="table-responsive">
                                <table id="detalles" class="table table-bordered table-striped">
                                  <thead>
                                    <tr>
                                      <th style="width: 80px;">Opciones</th>
                                      <th>Articulo</th>
                                      <th>Stock</th>
                                      <th>U/M</th>
                                      <th>Cantidad</th>
                                      <th>Precio</th>
                                      <th>Descuento</th>
                                      <th>Subtotal</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                  </tbody>
                                  <tfoot>
                                    <tr>
                                      <th>TOTAL </th>
                                      <th>
                                        <h5 id="totalCV">Bs/. 0.00</h5><input type="hidden" name="Preciototal"
                                          id="Preciototal">
                                      </th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>

                                    </tr>
                                  </tfoot>
                                </table>
                              </div>
                            </div>

                            <!-- Panel 2 -->
                            <div class="col-lg-12 col-md-12 col-sm-12ss col-xs-12">
                              <div class="card prod-p-card bg-c-blue">
                                <div class="card-header">
                                  <i class="fas fa-money-bill-alt text-c-blue f-18"></i>
                                  <h5 class="m-b-5 text-white"> PRECIO TOTAL DE LA COTIZACION</h5>
                                </div>
                                <div class="card-body">
                                  <div class="form-row">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                      <label class="m-b-5 text-white">Servicio</label>
                                      <input type="number" class="form-control" name="Monto_servicio" id="Monto_servicio"
                                        placeholder="Monto total del Diagnostico" readonly>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                      <label class="m-b-5 text-white">Ventas</label>
                                      <input type="number" class="form-control" name="Monto_venta" id="Monto_venta"
                                        placeholder="Monto total de la venta" readonly>
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                      <h3 id="totales4" class="m-b-5 text-white">TOTAL Bs/. 0.00</h3><input type="hidden"
                                        name="Total" id="Total">
                                    </div>
                                  </div> <!-- Fin row -->
                                </div>
                              </div> <!-- Fin gradient -->
                            </div> <!-- Fin de la fila -->

                            <div class="form-group col-12">
                              <button class="btn btn-primary" type="submit" id="btnGuardar">
                                <i class="fa fa-save"></i> Guardar
                              </button>
                              <button class="btn btn-danger" onclick="cancelarform()" type="button">
                                <i class="fa fa-arrow-circle-left"></i> Cancelar
                              </button>
                            </div>
                          </div>
                        </form>
                      </div>
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

    <!--moodal-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Seleccione Articulo</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body table-responsive">
            <table id="tblarticulos" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Opciones</th>
                  <th>Nombre</th>
                  <th>Codigo</th>
                  <th>Categoria</th>
                  <th>Stock</th>
                  <th>Precio</th>
                  <th>Imagen</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                <tr>
                  <th>Opciones</th>
                  <th>Nombre</th>
                  <th>Codigo</th>
                  <th>Categoria</th>
                  <th>Stock</th>
                  <th>Precio</th>
                  <th>Imagen</th>
                </tr>
              </tfoot>

            </table>
          </div>

          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <?php
  } else {
    require 'noacceso.php';
  }
  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/cotizacion.js"></script>
  <?php
}
ob_end_flush();
?>