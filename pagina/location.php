<?php
require 'header.php';
date_default_timezone_set('America/La_Paz');
$fecha_actual = date("Y-m-d");
?>
        <!-- Page Header Start -->
        <div class="page-header">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2>Puntos de Servicio</h2>
                    </div>
                    <div class="col-12">
                        <a href="">Inicio</a>
                        <a href="">Ubicación</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header End -->
        
   <!-- Location Start -->
<div class="location">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="section-header text-left">
                    <p>Puntos de Servicio</p>
                    <h2>Reparación y Mantenimiento Automotriz</h2>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="location-item">
                            <i class="fa fa-map-marker-alt"></i>
                            <div class="location-text">
                                <h3>Ubicacion del Taller</h3>
                                <p>El Alto Av. Bolivia Entrada Villa Adela Calle Nro. 16</p>
                                <p><strong>LLámanos:</strong>+591 74095226</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="location-form">
                    <h3>Solicitud de reparacion de autos</h3>
                    <form name="formularioWeb" id="formularioWeb" method="POST">
                        <div class="control-group">
                            <input type="hidden" name="Idmensaje" id="Idmensaje">
                            <input type="hidden" class="form-control" name="Fecha" id="Fecha"
                                value="<?php echo $fecha_actual; ?>">
                            <input type="text" class="form-control" name="Nombre" id="Nombre" placeholder="Nombre"
                                maxlength="30" required="required" />
                        </div>
                        <div class="control-group">
                            <input type="tel" class="form-control" name="Telefono" id="Telefono" placeholder="Numero de contacto"oninput="validarLongitud(this, 8,8)" required="required" />
                        </div>
                        <div class="control-group">
                            <input type="email" class="form-control" name="Email" id="Email" placeholder="Email"
                                required="required" />
                        </div>
                        <div class="control-group">
                            <textarea class="form-control" name="Detalle" id="Detalle" placeholder="Description"
                                maxlength="100" required="required"></textarea>
                        </div>
                        <div>
                            <button class="btn btn-custom" id="btnGuardarWeb" type="submit">Enviar Mensaje</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Location End -->
   <?php
require 'footer.php';
?>
<script type="text/javascript" src="js/location.js"></script>
