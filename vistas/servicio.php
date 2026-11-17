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
                          Servicio de Reparacion
                          <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)">
                            <i class="fa fa-plus-circle"></i> Agregar
                          </button>

                          <button id="btnGenerarPDF" class="btn btn-info" onclick="generarPDF()"> <i
                              class="fa fa-clipboard"></i> Reporte</button>
                        </h4>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body" id="listadoregistros">
                        <div class="form-row">
                          <div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <label> Desde la Fecha: </label>
                            <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio"
                              value="<?php echo date("Y-m-d"); ?>">
                          </div>

                          <div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <label> Hasta la Fecha: </label>
                            <input type="date" class="form-control" name="fecha_fin" id="fecha_fin"
                              value="<?php echo date("Y-m-d"); ?>">
                          </div>

                          <div class="col-xl-3 col-md-6">
                            <div class="card prod-p-card bg-c-red">
                              <div class="card-body">
                                <div class="row align-items-center m-b-10">
                                  <div class="col">
                                    <h6 class="m-b-5 text-white">Reparados mes</h6>
                                    <h5 class="m-b-0 text-white" id="repMes"></h5>
                                  </div>
                                  <div class="col-auto">
                                    <i class="fas fa-car text-c-red f-18"></i>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- /.col -->
                          <div class="col-xl-3 col-md-6">
                            <div class="card prod-p-card bg-c-blue">
                              <div class="card-body">
                                <div class="row align-items-center m-b-10">
                                  <div class="col">
                                    <h6 class="m-b-5 text-white">En proceso Mes</h6>
                                    <h5 class="m-b-0 text-white" id="totalMes"></h5>
                                  </div>
                                  <div class="col-auto">
                                    <i class="fas fa-wrench text-c-blue f-18"></i>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- /.col -->
                        </div>
                        <div class="table-responsive">
                          <table id="tbllistado" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th class="centered" style="width: 90px;">Opciones</th>
                                <th class="centered">Fecha entrega</th>
                                <th class="centered">Comprobante</th>
                                <th class="centered">Vehiculo</th>
                                <th class="centered">Cliente</th>
                                <th class="centered">Total</th>
                                <th class="centered">Estado</th>
                                <th class="centered">Acuenta</th>
                                <th class="centered">Saldo</th>
                                <th class="centered">Metodo pago</th>
                                <th class="centered">Usuario</th>
                                <th class="centered">Fecha Registro</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                              <tr>
                                <th class="centered" style="width: 90px;">Opciones</th>
                                <th class="centered">Fecha entrega</th>
                                <th class="centered">Comprobante</th>
                                <th class="centered">Vehiculo</th>
                                <th class="centered">Cliente</th>
                                <th class="centered">Total</th>
                                <th class="centered">Estado</th>
                                <th class="centered">Acuenta</th>
                                <th class="centered">Saldo</th>
                                <th class="centered">Metodo pago</th>
                                <th class="centered">Usuario</th>
                                <th class="centered">Fecha Registro</th>
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
                              <input type="hidden" name="Idservicio" id="Idservicio">
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

                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label>Fecha(registro):</label>
                              <input type="text" class="form-control" name="Fecha" id="Fecha"
                                value="<?php echo $fecha_actual; ?>" readonly>
                            </div>

                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label>Tipo Comprobante:</label>
                              <input type="text" class="form-control" name="Tipo_comprobante" id="Tipo_comprobante"
                                value="Recibo" readonly>
                            </div>

                            <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                              <label>Numero Comprobante:</label>
                              <input type="text" class="form-control" name="Numero" id="Numero"
                                placeholder="Generacion automatica" readonly>
                            </div>

                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                <label class="custom-control-label" for="customSwitch1">Cotizaciones</label>
                              </div>
                              <select name="Idcotizacion" id="Idcotizacion" class="form-control selectpicker"
                                data-live-search="true" data-dropup-auto="false"  disabled>
                                <option value="">Seleccione una cotización</option>
                              </select>
                            </div>
                            <div class="form-group col-lg-1 col-md-1 col-sm-1 col-xs-12">
                              <button type="button" class="btn btn-primary" id="BtnCotizacion" onclick="mostrarCotizacion()"
                                style="margin-top: 25px; float: left;">
                                <i class="feather icon-rotate-cw"></i>
                              </button>
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                              <label>Fecha Y Hora (Entrega):</label>
                              <input type="datetime-local" class="form-control" name="Fecha_entrega" id="Fecha_entrega"
                                value="<?php echo date('Y-m-d\TH:i'); ?>">
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
                                  <h4 class="d-inline">¿Agregar artículos de venta al servicio?</h4>
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
                                      <th colspan="6"></th>
                                    </tr>
                                  </tfoot>
                                </table>
                              </div>
                            </div>

                            <!-- Panel 2 -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <div class="card prod-p-card bg-c-red">
                                <div class="card-header">
                                  <i class="fas fa-money-bill-alt text-c-red f-18"></i>
                                  <h5 class="m-b-5 text-white"> PRECIO TOTAL DEL SERVICIO</h5>
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
                                      <div class="icheck-primary d-inline">
                                        <input type="radio" id="tipopago_efectivo" name="Metodo_pago" value="Efectivo"
                                          required>
                                        <label for="tipopago_efectivo" style="margin-right: 20px;"
                                          class="m-b-5 text-white">Efectivo</label>
                                      </div>
                                      <div class="icheck-primary d-inline">
                                        <input type="radio" id="tipopago_deposito" name="Metodo_pago" value="Deposito"
                                          required>
                                        <label for="tipopago_deposito" class="m-b-5 text-white">Deposito</label>
                                      </div>
                                    </div>

                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                      <label class="m-b-5 text-white">Monto</label>
                                      <input type="number" class="form-control" name="Monto" id="Monto"
                                        placeholder="Importe" oninput="calcularCambio()" required>
                                    </div>

                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                      <label class="m-b-5 text-white">Cambio</label>
                                      <input type="number" class="form-control" name="Cambio" id="Cambio"
                                        placeholder="Cambio" readonly>
                                    </div>

                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                      <label class="m-b-5 text-white">A cuenta</label>
                                      <input type="number" class="form-control" name="Acuenta" id="Acuenta" val="0.00"
                                        placeholder="Importe acuenta" oninput="calcularCambio()" required>
                                      <small id="montoAdvertencia" class="form-text text-white" style="display: none;">
                                      </small>
                                    </div>

                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                      <label class="m-b-5 text-white">Saldo</label>
                                      <input type="number" class="form-control" name="Saldo" id="Saldo"
                                        placeholder="Saldo a cancelar" readonly>
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




    <!-- Modal para pagos-->
    <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Pago de Servicios</h5>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cancelarModal()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <!-- /.card-body  -->
          <div class="card-body" id="formulariomodal" style="display:none;">
            <form name="formulario2" id="formulario2" method="POST">
              <div class="form-row">
                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <label>Vehiculo</label>
                  <input type="hidden" name="Idpago" id="Idpago">
                  <input type="hidden" name="Idservicio2" id="Idservicio2">
                  <input type="text" class="form-control" name="Vehiculo" id="Vehiculo" readonly>
                </div>

                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                  <label>Comprobante</label><br>
                  <input type="text" class="form-control" id="Num_comprobante" name="Num_comprobante" readonly>
                </div>

                <div class="form-group col-lg-5 col-md-5 col-sm-5 col-xs-12 text-center">
                  <label>Descripcion</label>
                  <input type="text" class="form-control" id="DescripcionModal" name="DescripcionModal" readonly>
                </div>

                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <h5 id="Estado_actual" style="text-align: center;"></h5>
                </div>

                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="text-align: center;">
                  <label>Método de pago</label><br>
                  <div class="icheck-primary d-inline">
                    <input type="radio" id="tipopago_efectivo2" name="Metodo_pago2" value="Efectivo" required>
                    <label for="tipopago_efectivo2" style="margin-right: 20px;" class="m-b-5">Efectivo</label>
                  </div>
                  <div class="icheck-primary d-inline">
                    <input type="radio" id="tipopago_deposito2" name="Metodo_pago2" value="Deposito" required>
                    <label for="tipopago_deposito2" class="m-b-5">Deposito</label>
                  </div>
                </div>

                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                  <label>Fecha de entrega</label>
                  <input type="datetime-local" class="form-control" name="Fecha_entrega_modal" id="Fecha_entrega_modal"
                    readonly>
                </div>

                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                  <label>Fecha de pago</label>
                  <input type="text" class="form-control" name="FechaP" id="FechaP" value="<?php echo $fecha_actual; ?>"
                    readonly>
                </div>

                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" style="text-align: center;">
                  <label>Total</label>
                  <input type="number" class="form-control" name="TotalServicio" id="TotalServicio" readonly>
                </div>

                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" style="text-align: center;">
                  <label>Anterior Acuenta</label>
                  <input type="number" class="form-control" name="AnteriorAcuenta" id="AnteriorAcuenta" readonly>
                </div>

                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" style="text-align: center;">
                  <label>Saldo Actual</label>
                  <input type="number" class="form-control" name="Saldo_Actual" id="Saldo_Actual" readonly>
                </div>

                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12" style="text-align: center;">
                  <label>Acuenta</label>
                  <input type="number" class="form-control" name="AcuentaM" id="AcuentaM" min="0" step="0.01"
                    placeholder="Importe acuenta" oninput="calcular();" required>
                </div>

                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12" style="text-align: center;">
                  <label>Monto a cancelar</label>
                  <input type="number" class="form-control" name="MontoM" id="MontoM" min="0" step="0.01"
                    placeholder="Importe" oninput="calcularCambio2()" required>
                </div>

                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12" style="text-align: center;">
                  <label>Cambio</label>
                  <input type="number" class="form-control" name="CambioM" id="CambioM" readonly>
                </div>

                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12" style="text-align: center;">
                  <label>Nuevo Saldo</label>
                  <input type="Number" class="form-control" name="Nuevo_saldo" id="Nuevo_saldo" readonly>
                </div>
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                  <h5 style="text-align: center;">HISTORIAL DE PAGO</h5>
                  <div class="table-responsive">
                    <table id="detallesM" class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>Fecha pago</th>
                          <th>Importe</th>
                          <th>Cambio</th>
                          <th>Acuenta</th>
                          <th>Saldo</th>
                        </tr>
                      </thead>
                      <tfoot>
                      </tfoot>
                      <tbody>
                    </table>
                  </div>
                </div>

                <div class="form-group col-12" style="display: flex; justify-content: center; gap: 10px;">
                  <button class="btn btn-primary" type="submit" id="btnGuardarModal" style="width: 150px;">
                    <i class="fa fa-save"></i> Guardar
                  </button>
                  <button class="btn btn-success" type="button" id="btnActualizarEstado"
                    style="width: 250px; display: none;" onclick="actualizarEstadoReparado()">
                    <i class="fa fa-wrench"></i> Marcar como Reparado
                  </button>
                </div>

              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="cancelarModal()">Cancelar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- Fin modal -->

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
  <script type="text/javascript" src="scripts/servicio.js"></script>
  <?php
}
ob_end_flush();
?>