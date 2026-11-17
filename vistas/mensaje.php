<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
} else {
    require 'header.php';
    if ($_SESSION['Servicio'] == 1) {
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
                                                   Mensajes de Solicitud de Servicio
                                                   <a href="../reportes/rptmensaje.php" target="_blank"><button
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
                                                                <th>Opciones</th>
                                                                <th>Fecha</th>
                                                                <th>Nombres</th>
                                                                <th>Correo Electronico</th>
                                                                <th>Celular</th>
                                                                <th>Detalle</th>
                                                                <th>Estado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th>Opciones</th>
                                                                <th>Fecha</th>
                                                                <th>Nombres</th>
                                                                <th>Correo Electronico</th>
                                                                <th>Celular</th>
                                                                <th>Detalle</th>
                                                                <th>Estado</th>
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
        <!--Fin-Contenido-->
        <?php
    } else {
        require 'noacceso.php';
    }
    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/mensaje.js"></script>
<?php
}
ob_end_flush();
?>