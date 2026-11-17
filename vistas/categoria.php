<?php
//activamos el bufferr
ob_start();
session_start();

if(!isset($_SESSION["nombre"])) 
{
  header("Location:login.html");
}
else
{
  require 'header.php';

  if($_SESSION['Almacen']==1)
  {
?> 
  

    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <div class="main-body">
                        <div class="page-wrapper">
                            <div class="row">
                                <div class="col-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="box-title">
                                                Registrar Categoria
                                            </h4>
                                        </div>
                                        <!-- /.card-header -->
                                        <!-- /.card-body --> 
                                        <div class="card-body" id="formularioregistros">
                                            <form name="formulario" id="formulario" method="POST">
                                                <div class="form-row">
                                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-12">
                                                        <label>Nombre:</label>
                                                        <input type="hidden" name="Idcategoria" id="Idcategoria">
                                                        <input type="text" class="form-control" name="Nombre" id="Nombre" maxlength="60"  placeholder="Nombre de Categoria" required>
                                                    </div>
                                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-12">
                                                        <label>Descripcion:</label>
                                                        <input type="text" class="form-control" name="Descripcion" id="Descripcion" maxlength="60"placeholder="Descripcion de la Categoria" required>
                                                    </div>        

                                                    <div class="form-group col-12">
                                                        <button class="btn btn-primary" type="submit" id="btnGuardar">
                                                            <i class="fa fa-save"></i> Guardar
                                                        </button>
                                                        <button class="btn btn-danger" onclick="cancelarform()" type="button">
                                                            <i class="fa fa-trash"></i> Cancelar
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /.card -->
                                </div>
                                <!-- /.col -->
                                <div class="col-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="box-title">
                                            Categorias 
                                            <a href="../reportes/rptcategoria.php" target="_blank"><button class="btn btn-info"data-toggle="tooltip"><i class="fa fa-clipboard"></i> Reporte</button></a>
                                            </h4> 
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body" id="listadoregistros">
                                            <table id="tbllistado" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="centered">Estado</th>
                                                        <th class="centered">Nombre</th>
                                                        <th class="centered">Descripcion</th>
                                                        <th class="centered"  style="width: 75px;">Opciones</th>

                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th class="centered">Estado</th>
                                                        <th class="centered">Nombre</th>
                                                        <th class="centered">Descripcion</th>
                                                        <th class="centered"  style="width: 75px;">Opciones</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
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
else
{
  require 'noacceso.php';
}
require 'footer.php';
?>
<script type="text/javascript" src="scripts/categoria.js"></script>

<?php
}
ob_end_flush();
?>