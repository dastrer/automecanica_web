<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ($_SESSION['Compra'] == 1) {
    date_default_timezone_set('America/La_Paz');
    $fecha_actual = date("Y-m-d");
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
                          Ingreso
                          <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)">
                            <i class="fa fa-plus-circle"></i> Agregar
                          </button>
                          <a href="../reportes/rptingreso.php" target="_blank"><button class="btn btn-info"><i
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
                                <th class="centered">Proveedor</th>
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
                                <th class="centered">Proveedor</th>
                                <th class="centered">Usuario</th>
                                <th class="centered">Total</th>
                                <th class="centered">Estado</th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                      <!-- /.card-body -->

                      <div class="card-body" id="formularioregistros" style="display:none;">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Proveedor:</label>
                              <input type="hidden" name="Idingreso" id="Idingreso">
                              <select name="Idproveedor" id="Idproveedor" class="form-control selectpicker"
                                data-live-search="true" required>
                              </select>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Fecha(*):</label>
                              <input type="date" class="form-control" name="Fecha" id="Fecha"
                                value="<?php echo $fecha_actual; ?>">
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Tipo Comprobante:</label>
                              <select class="form-control selectpicker" name="Tipocomprobante" id="Tipocomprobante"
                                required>
                                <option value="Boleta">Boleta</option>
                                <option value="Factura">Factura</option>
                                <option value="Recibo">Recibo</option>
                              </select>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Numero Comprobante:</label>
                              <input type="text" class="form-control" name="Numero" id="Numero"
                                placeholder="Ingrese el Numero" required>
                            </div>


                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <a data-toggle="modal" href="#myModal">
                                <button id="btnAgregarArt" type="button" class="btn btn-primary"> <span
                                    class="feather icon-plus"></span> Agregar Ariticulo</button>
                              </a>
                            </div>

                            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                              <div class="table-responsive">
                                <table id="detalles" class="table table-bordered table-striped">
                                  <thead>
                                    <tr>
                                      <th>Opciones</th>
                                      <th>Articulo</th>
                                      <th>Cantidad</th>
                                      <th>Precio compra</th>
                                      <th>Precio venta</th>
                                      <th>Subtotal</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                  </tbody>
                                  <tfoot>
                                    <tr>
                                      <th>TOTAL </th>
                                      <th>
                                        <h5 id="total">Bs/. 0.00</h5><input type="hidden" name="Preciototal"
                                          id="Preciototal">
                                      </th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>

                                    </tr>
                                  </tfoot>
                                </table>
                              </div>
                            </div>

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
      <div class="modal-dialog modal-xl">
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
                  <th>Cantidad</th>
                  <th>Categoria</th>
                  <th>Precio Compra</th>
                  <th>Precio Venta</th>
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
                  <th>Cantidad</th>
                  <th>Categoria</th>
                  <th>Precio Compra</th>
                  <th>Precio Venta</th>
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
  <script type="text/javascript" src="scripts/ingreso.js"></script>
<?php
}
ob_end_flush();
?>