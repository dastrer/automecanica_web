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
                                                    Proveedores
                                                    <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)">
                                                        <i class="fa fa-plus-circle"></i> Agregar
                                                    </button>
                                                    <a href="../reportes/rptproveedor.php" target="_blank"><button
                                                            class="btn btn-info"><i class="fa fa-clipboard"></i> Reporte
                                                        </button></a>
                                                </h4>
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" id="listadoregistros">
                                                <table id="tbllistado" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th class="centered">Opciones</th>
                                                            <th class="centered">Nombre</th>
                                                            <th class="centered">Documento</th>
                                                            <th class="centered">Telefono</th>
                                                            <th class="centered">Empresa</th>
                                                            <th class="centered">Estado</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th class="centered">Opciones</th>
                                                            <th class="centered">Nombre</th>
                                                            <th class="centered">Documento</th>
                                                            <th class="centered">Telefono</th>
                                                            <th class="centered">Empresa</th>
                                                            <th class="centered">Estado</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <!-- /.card-body -->
                                            <div class="card-body" id="formularioregistros" style="display:none;">
                                                <form name="formulario" id="formulario" method="POST">
                                                    <div class="form-row">

                                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <input type="hidden" name="Idproveedor" id="Idproveedor">
                                                            <input type="hidden" name="Tipopersona" id="Tipopersona"
                                                                value="Proveedor">
                                                            <label>Nombres:</label>
                                                            <input type="text" class="form-control" name="Nombres" id="Nombres"
                                                                maxlength="50" placeholder="Ingrese su nombre y apellido"
                                                                oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')"
                                                                required>
                                                        </div>

                                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Telefono:</label>
                                                            <input type="number" class="form-control" name="Telefono"
                                                                id="Telefono" maxlength="12" placeholder="Numero de Celular"
                                                                oninput="validarLongitud(this, 8,8)" required>
                                                        </div>

                                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Numero documento:</label>
                                                            <input type="number" class="form-control" name="Num_documento"
                                                                id="Num_documento" oninput="validarLongitud(this, 7,10)"
                                                                placeholder="Numero del Documento" required>
                                                        </div>

                                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Tipo Documento:</label>
                                                            <select class="form-control selectpicker" name="Tipo_documento"
                                                                id="Tipo_documento" maxlength="20" required>
                                                                <option value="CEDULA">CEDULA</option>
                                                                <option value="NIT">NIT</option>
                                                            </select>
                                                        </div>



                                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Direccion:</label>
                                                            <input type="text" class="form-control" name="Direccion"
                                                                id="Direccion" maxlength="256" placeholder="Ingrese Direccion"
                                                                required>
                                                        </div>


                                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Nombre de Empresa:</label>
                                                            <input type="text" class="form-control" name="Nombre_empresa"
                                                                id="Nombre_empresa" maxlength="20" placeholder="Ingrese Empresa"
                                                                required>
                                                        </div>

                                                        <div class="form-group col-12">
                                                            <button class="btn btn-primary" type="submit" id="btnGuardar">
                                                                <i class="fa fa-save"></i> Guardar
                                                            </button>
                                                            <button class="btn btn-danger" onclick="cancelarform()"
                                                                type="button">
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
        <!--Fin-Contenido-->
        <?php
    } else {
        require 'noacceso.php';
    }
    require 'footer.php';
    ?>

    <script type="text/javascript" src="scripts/proveedor.js"></script>


    <?php
}
ob_end_flush();
?>