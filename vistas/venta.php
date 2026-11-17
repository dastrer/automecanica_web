<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ($_SESSION['Venta'] == 1) {
    date_default_timezone_set('America/La_Paz');
    $fecha_actual = date("Y-m-d");
    $hora_actual = date("H:i:s");

    ?>
    <!--Contenido-->
    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
      <div class="pcoded-wrapper">
        <div class="pcoded-content">
          <div class="pcoded-inner-content">
            <div class="main-body">
              <div class="page-wrapper">
                <div class="row" id="infoCards">
                  <div class="col-xl-3 col-md-6">
                    <div class="card prod-p-card bg-c-yellow">
                      <div class="card-body">
                        <div class="row align-items-center m-b-10">
                          <div class="col">
                            <h6 class="m-b-5 text-white">Venta Hoy</h6>
                            <h5 class="m-b-0 text-white" id="total"></h5>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-money-bill-alt text-c-yellow f-18"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-3 col-md-6">
                    <div class="card prod-p-card bg-c-blue">
                      <div class="card-body">
                        <div class="row align-items-center m-b-10">
                          <div class="col">
                            <h6 class="m-b-5 text-white">Total</h6>
                            <h5 class="m-b-0 text-white" id="totalr">Bs</h5>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-coins text-c-blue f-18"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.col -->
                  <div class="col-xl-3 col-md-6">
                    <div class="card prod-p-card bg-c-green">
                      <div class="card-body">
                        <div class="row align-items-center m-b-10">
                          <div class="col">
                            <h6 class="m-b-5 text-white">Ventas mes</h6>
                            <h5 class="m-b-0 text-white" id="totalp">Bs</h5>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-dollar-sign text-c-green f-18"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.col -->
                  <div class="col-xl-3 col-md-6">
                    <div class="card prod-p-card bg-c-red">
                      <div class="card-body">
                        <div class="row align-items-center m-b-10">
                          <div class="col">
                            <h6 class="m-b-5 text-white">Anulados</h6>
                            <h5 class="m-b-0 text-white" id="totala"></h5>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-tags text-c-red f-18"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->

                <!-- =========================================================== -->
                <div class="row">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-header">
                        <h4 class="box-title">
                          Ventas
                          <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)">
                            <i class="fa fa-plus-circle"></i> Agregar
                          </button>
                          <a href="../reportes/rptventa.php" target="_blank"><button class="btn btn-info"><i
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
                                <th class="centered">Cliente</th>
                                <th class="centered">Documento</th>
                                <th class="centered">Comprobante</th>
                                <th class="centered">Usuario</th>
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
                                <th class="centered">Total </th>
                                <th class="centered">Estado</th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                      <!-- /.card-body -->
                      <div class="card-body" id="formularioregistros" style="display:none;">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="row">
                            <!-- Fila para paneles 1 y 2 -->
                            <!-- Panel 1 -->
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                              <div class="form-row">
                                <!-- Campo Cliente -->
                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                  <label>
                                    <div class="icheck-primary d-inline">
                                      <input type="checkbox" id="NuevoCliente" onclick="Nuevocliente()">
                                      <label for="NuevoCliente"> Nuevo Cliente</label>
                                    </div>
                                  </label>
                                  <input type="hidden" name="Idventa" id="Idventa">
                                  <!-- Contenedor del select -->
                                  <div id="selectContainer">
                                    <select name="Idcliente" id="Idcliente" class="form-control selectpicker"
                                      data-live-search="true">
                                    </select>
                                  </div>
                                  <!-- Contenedor del input, inicialmente oculto -->
                                  <div id="inputContainer" style="display: none;">
                                    <input type="hidden" name="Tipopersona" id="Tipopersona" value="Cliente">
                                    <input type="text" name="Nombres" id="Nombres" class="form-control" maxlength="50"
                                      placeholder="Ingrese nuevo cliente"
                                      oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')" required>
                                  </div>
                                </div>

                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Numero Documento:</label>
                                  <input type="number" class="form-control" name="Num_documento" id="Num_documento"
                                    placeholder="Documento" oninput="validarLongitud(this, 7,12)" required>
                                </div>

                                <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                  <label>Documento:</label>
                                  <select class="form-control selectpicker" name="Tipo_documento" id="Tipo_documento"
                                    maxlength="20" required>
                                    <option value="CEDULA">Cedula</option>
                                    <option value="NIT">Nit</option>
                                  </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Tel/Celular:</label>
                                  <input type="number" class="form-control" name="Telefono" id="Telefono"
                                    placeholder="Ingrese Telefono" oninput="validarLongitud(this, 8,8)" required>
                                </div>

                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Comprobante:</label>
                                  <div class="icheck-primary d-inline">
                                    <input type="radio" id="Tipo_comprobante_recibo" name="Tipo_comprobante" value="Recibo"
                                      onchange="generarNumero(this.value)" required>
                                    <label for="Tipo_comprobante_recibo" >Recibo</label>
                                  </div>
                                  <div class="icheck-primary d-inline">
                                    <input type="radio" id="Tipo_comprobante_ticket" name="Tipo_comprobante" value="Ticket"
                                      onchange="generarNumero(this.value)" required>
                                    <label for="Tipo_comprobante_ticket">Ticket</label>
                                  </div>
                                </div>

                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Numero:</label>
                                  <input type="text" class="form-control" id="Num_comprobante" name="Num_comprobante"
                                    placeholder="Autogenerado" readonly>
                                </div>

                                <!-- Campo Fecha -->
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Fecha(*):</label>
                                  <input type="date" class="form-control" name="Fecha" id="Fecha"
                                    value="<?php echo $fecha_actual; ?>" readonly>
                                </div>
                                <!-- Campo Hora -->
                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Hora:</label>
                                  <input type="text" class="form-control" name="Hora" id="Hora"
                                    value="<?php echo $hora_actual; ?>" required="">
                                </div>

                                <!-- Botón Agregar Producto -->
                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                  <a data-toggle="modal" href="#myModal">
                                    <button id="btnAgregarArt" type="button" class="btn btn-primary"><span
                                        class="fa fa-plus"></span> Agregar Articulo</button>
                                  </a>
                                </div>

                                <!-- Tabla de detalles -->
                                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                  <div class="table-responsive">
                                    <table id="detalles" class="table table-bordered table-striped">
                                      <thead>
                                        <tr>
                                          <th style="width: 80px;">Opciones</th>
                                          <th>Articulo</th>
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

                                        </tr>
                                      </tfoot>
                                    </table>
                                  </div>
                                </div>
                                <br>
                                <!-- Botones de acción -->
                                <div class="form-group col-12">
                                  <button class="btn btn-primary" type="submit" id="btnGuardar">
                                    <i class="fa fa-save"></i> Guardar
                                  </button>
                                  <button class="btn btn-danger" onclick="cancelarform()" type="button">
                                    <i class="fa fa-arrow-circle-left"></i> Cancelar
                                  </button>
                                </div>
                              </div>
                            </div>

                            <!-- Panel 2 -->
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                              <div class="card prod-p-card bg-c-red">
                                <div class="card-header">
                                  <i class="fas fa-money-bill-alt text-c-red f-18"></i>
                                  <h5 class="m-b-5 text-white"> Pagos</h5>
                                </div>
                                <div class="card-body">
                                  <div class="form-row">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                      <div class="icheck-primary d-inline">
                                        <input type="radio" id="tipopago_efectivo" name="Tipo" value="Efectivo" required>
                                        <label for="tipopago_efectivo" style="margin-right: 20px;"
                                          class="m-b-5 text-white">Efectivo</label>
                                      </div>
                                      <div class="icheck-primary d-inline">
                                        <input type="radio" id="tipopago_deposito" name="Tipo" value="Deposito" required>
                                        <label for="tipopago_deposito" class="m-b-5 text-white">Deposito</label>
                                      </div>
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                      <label class="m-b-5 text-white">Monto</label>
                                      <input type="number" class="form-control" name="Monto" id="Monto"
                                        placeholder="Importe" oninput="calcularCambio()" required>
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                      <label class="m-b-5 text-white">Cambio</label>
                                      <input type="number" class="form-control" name="Cambio" id="Cambio"
                                        placeholder="Cambio" readonly>
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                      <h5 id="totales3" class="m-b-5 text-white">TOTAL Bs/. 0.00</h5><input type="hidden"
                                        name="Total" id="Total">
                                    </div>
                                  </div> <!-- Fin row -->
                                </div>
                              </div> <!-- Fin gradient -->
                            </div> <!-- Fin de la fila -->
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
  <script type="text/javascript" src="scripts/venta.js"></script>
  <?php
}
ob_end_flush();
?>