<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
} else {
    require 'header.php';

    if ($_SESSION['ConsultaS'] == 1) {
        date_default_timezone_set('America/La_Paz');
        $fecha_actual = date("Y-m-d");
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
                                                    Consulta de servicio y articulos
                                                    <button id="btnGenerarPDF" class="btn btn-danger" onclick="generarPDF()"><i
                                                            class="fas fa-file-pdf"></i> Reporte PDF</button>
                                                </h4>
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" id="listadoregistros">
                                                <div class="form-row">

                                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label> Desde la Fecha: </label>
                                                        <input type="date" class="form-control" name="fecha_inicio"
                                                            id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                                                        <div class="icheck-primary d-inline-block" style="margin-top: 10px;">
                                                            <input type="checkbox" id="Todasfechas" onclick="fechas()">
                                                            <label for="Todasfechas"> Todas las Fechas</label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label> Hasta la Fecha: </label>
                                                        <input type="date" class="form-control" name="fecha_fin" id="fecha_fin"
                                                            value="<?php echo date("Y-m-d"); ?>">
                                                    </div>

                                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label>Articulos:</label>
                                                        <select name="Idarticulo" id="Idarticulo"
                                                            class="form-control selectpicker" data-live-search="true" required>
                                                        </select>
                                                        <div class="icheck-primary d-inline-block" style="margin-top: 10px;">
                                                            <input type="checkbox" id="TodosArticulos" onclick="articulos()">
                                                            <label for="TodosArticulos"> Todos los Articulos</label>
                                                        </div>
                                                        <button class="btn btn-primary" onclick="Buscar()"
                                                            style="margin-top: 10px; float: right;"><span class="fas fa-search">
                                                            </span> Buscar</button>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xl-3 col-md-6">
                                                        <div class="card prod-p-card bg-c-yellow">
                                                            <div class="card-body">
                                                                <div class="row align-items-center m-b-15">
                                                                    <div class="col">
                                                                        <h5 class="m-b-5 text-white">Costo</h5>
                                                                        <h6 class="m-b-0 text-white" id="totalcosto">Bs/.0.00</h6>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <i class="fas fa-coins text-c-yellow f-18"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6">
                                                        <div class="card prod-p-card bg-c-blue">
                                                            <div class="card-body">
                                                                <div class="row align-items-center m-b-15">
                                                                    <div class="col">
                                                                        <h5 class="m-b-5 text-white">Utilidad</h5>
                                                                        <h6 class="m-b-0 text-white" id="totalutilidad">Bs/.0.00</h6>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <i class="fas fa-dollar-sign text-c-blue f-18"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- /.col -->
                                                    <div class="col-xl-3 col-md-6">
                                                        <div class="card prod-p-card bg-c-green">
                                                            <div class="card-body">
                                                                <div class="row align-items-center m-b-15">
                                                                    <div class="col">
                                                                        <h5 class="m-b-5 text-white">Total</h5>
                                                                        <h6 class="m-b-0 text-white" id="total">Bs/.0.00</h6>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <i class="fas fa-money-bill text-c-green f-18"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- /.col -->
                                                    <div class="col-xl-3 col-md-6">
                                                        <div class="card prod-p-card bg-c-red">
                                                            <div class="card-body">
                                                                <div class="row align-items-center m-b-15">
                                                                    <div class="col">
                                                                        <h5 class="m-b-5 text-white">Cantidad</h5>
                                                                        <h6 class="m-b-0 text-white" id="cantidad">0</h6>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <i class="fas fa-box text-c-red f-18"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- /.col -->

                                                </div>
                                                <!-- /.row -->

                                                <!-- =========================================================== -->
                                                <div class="table-responsive">
                                                    <table id="tbllistado" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th class="centered" style="width: 70px;">Fecha</th>
                                                                <th class="centered">Vehiculo</th>
                                                                <th class="centered">Comprobante</th>
                                                                <th class="centered">Articulo</th>
                                                                <th class="centered">Cantidad</th>
                                                                <th class="centered">Precio Compra</th>
                                                                <th class="centered">Precio Venta</th>
                                                                <th class="centered">Utilidad</th>
                                                                <th class="centered">Subtotal</th>
                                                                <th class="centered">Sub/Costo</th>
                                                                <th class="centered">Sub/Utilidad</th>
                                                                <th class="centered">Descuento</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th class="centered" style="width: 70px;">Fecha</th>
                                                                <th class="centered">Vehiculo</th>
                                                                <th class="centered">Comprobante</th>
                                                                <th class="centered">Articulo</th>
                                                                <th class="centered">Cantidad</th>
                                                                <th class="centered">Precio Compra</th>
                                                                <th class="centered">Precio Venta</th>
                                                                <th class="centered">Utilidad</th>
                                                                <th class="centered">Subtotal</th>
                                                                <th class="centered">Sub/Costo</th>
                                                                <th class="centered">Sub/Utilidad</th>
                                                                <th class="centered">Descuento</th>

                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>

                                            </div>
                                            <!-- /.card-body -->
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
    <script type="text/javascript" src="scripts/consulta_servicio_art.js"></script>
<?php
}
ob_end_flush();
?>