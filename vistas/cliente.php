<?php
//activamos el bufferr
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location:login.html");
} else {
    require 'header.php';

    if ($_SESSION['Venta'] == 1 || $_SESSION['Vehículos'] == 1) {
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
                                                    Registrar Cliente
                                                </h4>
                                            </div>
                                            <!-- /.card-header -->
                                            <!-- /.card-body -->
                                            <div class="card-body" id="formularioregistros">
                                                <form name="formulario" id="formulario" method="POST">
                                                    <div class="form-row">
                                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <input type="hidden" name="Idpersona" id="Idpersona">
                                                            <input type="hidden" name="Tipopersona" id="Tipopersona"
                                                                value="Cliente">
                                                            <label>Nombres:</label>
                                                            <input type="text" class="form-control" name="Nombres" id="Nombres"
                                                                maxlength="50" placeholder="Cliente"
                                                                oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')"
                                                                required>
                                                        </div>

                                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <label>Tel/Celular:</label>
                                                            <input type="number" class="form-control" name="Telefono"
                                                                id="Telefono" placeholder="Telefono"
                                                                oninput="validarLongitud(this, 8,8)" required>
                                                        </div>

                                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Numero:</label>
                                                            <input type="number" class="form-control" name="Num_documento"
                                                                id="Num_documento" placeholder="Numero"
                                                                oninput="validarLongitud(this, 7,8)" required>
                                                        </div>

                                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Documento:</label>
                                                            <select class="form-control selectpicker" name="Tipo_documento"
                                                                id="Tipo_documento" maxlength="20" required>
                                                                <option value="CEDULA">Cedula</option>
                                                                <option value="NIT">Nit</option>
                                                            </select>
                                                        </div>



                                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <label>Direccion:</label>
                                                            <input type="text" class="form-control" name="Direccion"
                                                                id="Direccion" maxlength="256" placeholder="Ingrese Direccion"
                                                                required>
                                                        </div>

                                                        <div class="form-group col-12">
                                                            <button class="btn btn-primary" type="submit" id="btnGuardar">
                                                                <i class="fa fa-save"></i> Guardar
                                                            </button>
                                                            <button class="btn btn-danger" onclick="cancelarform()"
                                                                type="button">
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
                                                    Lista de Clientes
                                                    <a href="../reportes/rptcliente.php" target="_blank"><button
                                                            class="btn btn-sm btn-info"><i class="fa fa-clipboard"></i>
                                                            Reporte</button></a>
                                                </h4>
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" id="listadoregistros">
                                                <div class="table-responsive">
                                                    <table id="tbllistado" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th class="centered" style="width: 80px;">Opciones</th>
                                                                <th class="centered">Nombre</th>
                                                                <th class="centered">Documento</th>
                                                                <th class="centered">Telefono</th>
                                                                <th class="centered">Estado</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th class="centered" style="width: 80px;">Opciones</th>
                                                                <th class="centered">Nombre</th>
                                                                <th class="centered">Documento</th>
                                                                <th class="centered">Telefono</th>
                                                                <th class="centered">Estado</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
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
    <script type="text/javascript" src="scripts/cliente.js"></script>

    <?php
}
ob_end_flush();
?>