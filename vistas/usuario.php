<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
} else {
    require 'header.php';
    if ($_SESSION['Acceso'] == 1) {
        date_default_timezone_set('America/La_Paz');
        $fecha_hora_actual = date("Y-m-d H:i:s");

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
                                                    Usuarios
                                                    <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)">
                                                        <i class="fa fa-plus-circle"></i> Agregar
                                                    </button>
                                                    <a href="../reportes/rptusuarios.php" target="_blank"><button
                                                            class="btn btn-info"><i class="fa fa-clipboard"></i> Reporte
                                                        </button></a>
                                                </h4>
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" id="listadoregistros">
                                                <div class="table-responsive">
                                                    <table id="tbllistado" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th class="centered" style="width: 100px;">Opciones</th>
                                                                <th class="centered">Nombre</th>
                                                                <th class="centered">Documento</th>
                                                                <th class="centered">Email</th>
                                                                <th class="centered">Telefono</th>
                                                                <th class="centered">Foto</th>
                                                                <th class="centered">Estado</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th class="centered" style="width: 100px;">Opciones</th>
                                                                <th class="centered">Nombre</th>
                                                                <th class="centered">Documento</th>
                                                                <th class="centered">Email</th>
                                                                <th class="centered">Telefono</th>
                                                                <th class="centered">Foto</th>
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
                                                        <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <input type="hidden" name="Idusuario" id="Idusuario">
                                                            <input type="hidden" name="Tipopersona" id="Tipopersona"
                                                                value="Usuario">
                                                            <label>Nombres:</label>
                                                            <input type="text" class="form-control" name="Nombres" id="Nombres"
                                                                maxlength="50" placeholder="Ingrese sus Nombres"
                                                                oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')"
                                                                required>
                                                        </div>
                                                        <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>Numero:</label>
                                                            <input type="number" class="form-control" name="Num_documento"
                                                                id="Num_documento" oninput="validarLongitud(this, 7,14)"
                                                                placeholder="Numero del Documento" required>
                                                        </div>
                                                        <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>Documento:</label>
                                                            <select class="form-control selectpicker" name="Tipo_documento"
                                                                id="Tipo_documento" maxlength="20" required>
                                                                <option value="CEDULA">CEDULA</option>
                                                                <option value="NIT">NIT</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>Direccion:</label>
                                                            <input type="text" class="form-control" name="Direccion"
                                                                id="Direccion" maxlength="180" placeholder="Ingrese Direccion"
                                                                required>
                                                        </div>


                                                        <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>Email:</label>
                                                            <input type="email" class="form-control" name="Email" id="Email"
                                                                maxlength="80" placeholder="Ingrese Email" required>
                                                        </div>


                                                        <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>Telefono:</label>
                                                            <input type="number" class="form-control" name="Telefono"
                                                                id="Telefono" oninput="validarLongitud(this, 8,8)"
                                                                placeholder="Numero de Celular">
                                                        </div>


                                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Fecha de Creacion:</label>
                                                            <input type="text" class="form-control" name="Fecha_creacion"
                                                                id="Fecha_creacion" value="<?php echo $fecha_hora_actual; ?>"
                                                                readonly>
                                                        </div>


                                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Cargo:</label>
                                                            <select name="Cargo" id="Cargo" class="form-control selectpicker"
                                                                data-live-search="true" required>
                                                                <option value="ADMINISTRADOR">ADMINISTRADOR</option>
                                                                <option value="VENDEDOR">VENDEDOR</option>
                                                                <option value="ENCARGADO_ALMACEN">ENCARGADO DE ALMACÉN</option>
                                                                <option value="AUXILIAR_ALMACEN">AUXILIAR DE ALMACÉN</option>
                                                                <option value="RECEPCIONISTA">RECEPCIONISTA</option>
                                                                <option value="GERENTE">GERENTE</option>
                                                                <option value="MECANICO">MECÁNICO</option>
                                                                <option value="AUXILIAR_MECANICO">AUXILIAR DE MECANICO</option>
                                                            </select>
                                                        </div>


                                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Login:</label>
                                                            <input type="text" id="Login" name="Login" class="form-control"
                                                                placeholder="Ingrese su Usuario" required>
                                                        </div>

                                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Contraseña:</label>
                                                            <div class="input-group">
                                                                <input type="password" id="Clave" name="Clave"
                                                                    class="form-control" placeholder="Ingrese su Contraseña"
                                                                    oninput="validarClave(this, 8, 20)">
                                                                <div class="input-group-append">
                                                                    <button type="button" class="btn btn-outline-secondary"
                                                                        onclick="togglePassword()">
                                                                        <i class="fa fa-eye" id="iconoClave"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label>Permisos:</label>
                                                            <ul style="list-style: none;" id="permisos">
                                                            </ul>
                                                        </div>

                                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label for="Imagen">Foto:</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input" name="Imagen"
                                                                        id="Imagen" accept="image/x-png,image/gif,image/jpeg">
                                                                    <label class="custom-file-label" for="Imagen">Selecione
                                                                        Archivo</label>
                                                                    <input type="hidden" name="Imagenactual" id="Imagenactual">
                                                                </div>
                                                            </div>
                                                            <img src="" width="150px" height="120px" id="Imagenmuestra">
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
        <?php
    } else {
        require 'noacceso.php';
    }
    require 'footer.php';
    ?>

    <script type="text/javascript" src="scripts/usuario.js"></script>


    <?php
}
ob_end_flush();
?>