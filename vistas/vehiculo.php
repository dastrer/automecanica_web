<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ($_SESSION['Vehículos'] == 1) {
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
                <div class="row" id="infoCards">
                  <div class="col-xl-3 col-md-6">
                    <div class="card prod-p-card bg-c-purple">
                      <div class="card-body">
                        <div class="row align-items-center m-b-10">
                          <div class="col">
                            <h6 class="m-b-5 text-white">Vehiculos mes</h6>
                            <h5 class="m-b-0 text-white" id="total"></h5>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-car text-c-purple f-18"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-3 col-md-6">
                    <div class="card prod-p-card bg-c-green">
                      <div class="card-body">
                        <div class="row align-items-center m-b-10">
                          <div class="col">
                            <h6 class="m-b-5 text-white">Activos</h6>
                            <h5 class="m-b-0 text-white" id="totala"></h5>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-thumbs-up text-c-green f-18"></i>
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
                            <h6 class="m-b-5 text-white">Inactivos</h6>
                            <h5 class="m-b-0 text-white" id="totali"></h5>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-thumbs-down text-c-red f-18"></i>
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
                            <h6 class="m-b-5 text-white">Con servicio</h6>
                            <h5 class="m-b-0 text-white" id="totalp"></h5>
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
                <!-- /.row -->

                <!-- =========================================================== -->
                <div class="row">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-header">
                        <h4 class="box-title">
                          Vehiculos
                          <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)">
                            <i class="fa fa-plus-circle"></i> Agregar
                          </button>
                          <a href="../reportes/rptvehiculo.php" target="_blank"><button class="btn btn-info"><i
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
                                <th class="centered">Vehiculo</th>
                                <th class="centered">Placa</th>
                                <th class="centered">Imagen </th>
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
                                <th class="centered">Vehiculo</th>
                                <th class="centered">Placa</th>
                                <th class="centered">Imagen </th>
                                <th class="centered">Estado</th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                      <!-- /.card-body  -->
                      <div class="card-body" id="formularioregistros" style="display:none;">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="row">
                            <div class="col-12">
                              <div class="form-row">
                                <!-- Campo Cliente -->
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                  <label>
                                    <div class="icheck-primary d-inline">
                                      <input type="checkbox" id="NuevoCliente" onclick="Nuevocliente()">
                                      <label for="NuevoCliente"> Nuevo Cliente</label>
                                    </div>
                                  </label>
                                  <input type="hidden" name="Idvehiculo" id="Idvehiculo">
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

                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                  <label>Documento:</label>
                                  <select class="form-control selectpicker" name="Tipo_documento" id="Tipo_documento"
                                    maxlength="20" required>
                                    <option value="CEDULA">Cedula</option>
                                    <option value="NIT">Nit</option>
                                  </select>
                                </div>
                                
                                 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                  <label>Tel/Celular:</label>
                                  <input type="number" class="form-control" name="Telefono" id="Telefono"
                                    placeholder="Ingrese Telefono" oninput="validarLongitud(this, 8,8)" required>
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                  <label>Direccion:</label>
                                  <input type="text" class="form-control" name="Direccion" id="Direccion" maxlength="100"
                                    placeholder="Ingrese Direccion">
                                </div>


                                <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                  <label>Color del vehículo:</label>
                                  <div class="color-picker-container">
                                    <div class="color-option" style="background-color: #000;" data-color="Negro">Negro</div>
                                    <div class="color-option" style="background-color: #fff; color: #000;"
                                      data-color="Blanco">Blanco</div>
                                    <div class="color-option" style="background-color: #f00;" data-color="Rojo">Rojo</div>
                                    <div class="color-option" style="background-color: #00f;" data-color="Azul">Azul</div>
                                    <div class="color-option" style="background-color: #ff0; color: #000;"
                                      data-color="Amarillo">Amarillo</div>
                                    <div class="color-option" style="background-color:rgb(32, 224, 238);"
                                      data-color="Celeste">Celeste
                                    </div>
                                    <div class="color-option" style="background-color: #008000;" data-color="Verde">Verde
                                    </div>
                                    <div class="color-option" style="background-color: #ffa500; color: #000;"
                                      data-color="Naranja">Naranja</div>
                                    <div class="color-option" style="background-color: #800080;" data-color="Morado">Morado
                                    </div>
                                    <div class="color-option other-option" style="background-color: #ccc; color: #000;"
                                      data-color="Otro">Otros...</div>
                                  </div>
                                </div>
                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                  <div id="customColorContainer">
                                    <label for="customColorInput">Escribe el color del vehiculo:</label>
                                    <input type="text" id="customColorInput" class="form-control"
                                      placeholder="Ej. Turquesa">
                                    <input type="hidden" id="selectedColor" name="Color">
                                  </div>

                                </div>

                                <!-- Campo Fecha -->
                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                  <label>Fecha y Hora:</label>
                                  <input type="text" class="form-control" name="Fecha" id="Fecha"
                                    value="<?php echo $fecha_actual; ?>" readonly>
                                </div>

                                <!-- Campo Placa -->
                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                  <label>Placa:</label>
                                  <input type="text" class="form-control" name="Placa" id="Placa"
                                    placeholder="Numero de placa" maxlength="10" required>
                                </div>

                                <!-- Campo Obervaciones -->


                                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                  <label>Tipo de Vehiculo:</label>
                                  <select name="Tipo" id="Tipo" class="form-control selectpicker" data-live-search="true"
                                    required>
                                    <option value="Taxi">Taxi</option>
                                    <option value="Combi">Combi</option>
                                    <option value="Camioneta">Camioneta</option>
                                    <option value="Furgoneta">Furgoneta</option>
                                    <option value="SUV">SUV</option>
                                    <option value="Jeep">Jeep</option>
                                    <option value="Pick-Up">Pick-Up</option>
                                    <option value="Camión pequeño">Camión pequeño</option>
                                    <option value="Minibús">Minibús</option>
                                    <option value="Bus urbano">Bus urbano</option>
                                    <option value="Vehículo 4x4">Vehículo 4x4</option>
                                    <option value="Peta">Peta</option>
                                    <option value="Camion">Camion</option>
                                    <option value="Otro">Otro</option>
                                  </select>
                                </div>


                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                  <label for="Observaciones">Observaciones:</label>
                                  <input class="form-control" name="Observaciones" id="Observaciones"
                                    placeholder="Observaciones del Vehículo" maxlength="100" required>
                                </div>



                                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                  <label>Marca:</label>
                                  <input type="text" class="form-control" name="Marca" id="Marca"
                                    placeholder="Marca del vehiculo" maxlength="10" required>
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                  <label for="Imagen">Imagen:</label>
                                  <div class="input-group">
                                    <div class="custom-file">
                                      <input type="file" class="custom-file-input" name="Imagen" id="Imagen"
                                        accept="image/x-png,image/gif,image/jpeg">
                                      <label class="custom-file-label" for="Imagen">Selecione Archivo</label>
                                      <input type="hidden" name="Imagenactual" id="Imagenactual">
                                    </div>
                                  </div>
                                  <img src="" width="150px" height="120px" id="Imagenmuestra">
                                </div>






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


    <?php
  } else {
    require 'noacceso.php';
  }
  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/vehiculo.js"></script>
  <?php
}
ob_end_flush();
?>