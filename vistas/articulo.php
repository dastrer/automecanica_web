<?php
//activamos el bufferr
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location:login.html");
} else {
  require 'header.php';

  if ($_SESSION['Almacen'] == 1) {


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
                          Articulos y Repuestos
                          <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)">
                            <i class="fa fa-plus-circle"></i> Agregar
                          </button>
                          <button id="btnGenerarPDF" class="btn btn-info" onclick="generarPDF()"> <i
                              class="fas fa-clipboard"></i> Reporte</button>
                        </h4>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body" id="listadoregistros">
                        <div class="form-row">
                          <div class="col-md-3 col-sm-6 col-12">

                            <div class="card prod-p-card bg-c-green">
                              <div class="card-body p-2">
                                <div class="row align-items-center m-b-5">
                                  <div class="col">
                                    <h6 class="m-b-1 text-white">Categorias</h6>
                                    <h3 class="m-b-0 text-white" id="total"></h3>
                                  </div>
                                  <div class="col-auto">
                                    <i class="fas fa-box text-c-green f-18"></i>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="card prod-p-card bg-c-blue">
                              <div class="card-body p-2">
                                <div class="row align-items-center m-b-5">
                                  <div class="col">
                                    <h6 class="m-b-1 text-white">Stock</h6>
                                    <h3 class="m-b-0 text-white" id="totalstk"></h3>
                                  </div>
                                  <div class="col-auto">
                                    <i class="fas fa-table text-c-blue f-18"></i>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- /.col -->
                          <div class="col-md-3 col-sm-6 col-12">

                            <div class="card prod-p-card bg-c-yellow">
                              <div class="card-body p-2">
                                <div class="row align-items-center m-b-5">
                                  <div class="col">
                                    <h6 class="m-b-1 text-white">Stock Bajo</h6>
                                    <h3 class="m-b-0 text-white" id="totalsb"></h3>
                                  </div>
                                  <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle text-c-yellow f-18"></i>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="card prod-p-card bg-c-red">
                              <div class="card-body p-2">
                                <div class="row align-items-center m-b-5">
                                  <div class="col">
                                    <h6 class="m-b-1 text-white">Desactivados</h6>
                                    <h3 class="m-b-0 text-white" id="totald"></h3>
                                  </div>
                                  <div class="col-auto">
                                    <i class="fas fa-times text-c-red f-18"></i>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- /.col -->


                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Buscar por Categoria:</label>
                            <select name="Idcategoria2" id="Idcategoria2" class="form-control selectpicker" required>
                            </select>
                            <br>
                            <div class="icheck-primary d-inline">
                              <input type="checkbox" id="TodasCategoria" onclick="Function1()">
                              <label for="TodasCategoria"> Categorias</label>
                            </div>
                            <div class="icheck-primary d-inline">
                              <input type="checkbox" id="Stockbajo" onclick="Function2()">
                              <label for="Stockbajo"> Stock Bajo </label>
                            </div>
                            <button class="btn btn-primary" onclick="Buscar()" style="margin-top: 10px; float: right;"><i
                                class="fas fa-search"></i> Buscar</button>
                          </div>
                        </div>

                        <table id="tbllistado" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th class="centered" style="width: 100px;">Opciones</th>
                              <th class="centered">Nombre</th>
                              <th class="centered">Marca</th>
                              <th class="centered">Codigo</th>
                              <th class="centered">Categoria</th>
                              <th class="centered">Stock</th>
                              <th class="centered">Imagen</th>
                              <th class="centered">Estado</th>

                            </tr>
                          </thead>
                          <tbody></tbody>
                          <tfoot>
                            <tr>
                              <th class="centered" style="width: 100px;">Opciones</th>
                              <th class="centered">Nombre</th>
                              <th class="centered">Marca</th>
                              <th class="centered">Codigo</th>
                              <th class="centered">Categoria</th>
                              <th class="centered">Stock</th>
                              <th class="centered">Imagen</th>
                              <th class="centered">Estado</th>

                            </tr>
                          </tfoot>
                        </table>
                      </div>
                      <!-- /.card-body-->

                      <div class="card-body" id="formularioregistros" style="display:none;">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Nombre:</label>
                              <input type="hidden" name="Idarticulo" id="Idarticulo">
                              <input type="text" class="form-control" name="Nombre" id="Nombre" maxlength="30"
                                placeholder="Nombre del articulo o repuesto" required>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Categoria:</label>
                              <select name="Idcategoria" id="Idcategoria" class="form-control selectpicker" required>
                              </select>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12">
                              <label>Unidad de Medida:</label>
                              <select name="Idunidadmedida" id="Idunidadmedida" class="form-control selectpicker" required>
                              </select>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Descripcion:</label>
                              <input type="text" class="form-control" name="Descripcion" id="Descripcion" maxlength="90"
                                placeholder="Descripcion del Producto" required>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Marca:</label>
                              <input type="text" class="form-control" name="Marca" id="Marca" maxlength="50"
                                placeholder="Marca del Articulo" required>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-6">
                              <label>Stock:</label>
                              <input type="number" class="form-control" name="Cantidad" id="Cantidad" maxlength="50" min="0"
                                placeholder="Stock">
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
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

                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Código:</label>
                              <input type="text" class="form-control" name="Codigo" id="Codigo" placeholder="Código Barras">
                              <br>
                              <button class="btn btn-success" type="button" onclick="generarbarcode()"><i
                                  class="fas fa-barcode"></i> Generar</button>
                              <button class="btn btn-info" type="button" onclick="imprimir()"><i class="fas fa-print"></i>
                                Imprimir</button>
                              <div id="print">
                                <svg id="barcode"></svg>
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

    <?php
  } 
  else {
    require 'noacceso.php';
  }
  require 'footer.php';
  ?>
  <!--scrpts-->
  <script type="text/javascript" src="../assets/plugins/js/JsBarcode.all.min.js"></script>
  <script type="text/javascript" src="../assets/plugins/js/jquery.PrintArea.js"></script>
  <script type="text/javascript" src="scripts/articulo.js"></script>
  <?php
}
ob_end_flush();
?>