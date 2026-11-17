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
                <h2>Contáctanos</h2>
            </div>
            <div class="col-12">
                <a href="home.php">Incio</a>
                <a href="contact.php">Contáctanos</a>
            </div>
        </div>
    </div>
</div>
<!-- Page Header End -->


<!-- Contact Start -->
<div class="contact">
    <div class="container">
        <div class="section-header text-center">
            <p>Ponte en contacto</p>
            <h2>Contacta por cualquier consulta</h2>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="contact-info">
                    <h2>Información de contacto</h2>
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="far fa-clock"></i>
                        </div>
                        <div class="contact-info-text">
                            <h3>Horario de atención</h3>
                            <p>Lunes - Sábado, 8:00 am - 9:00 pm</p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fa fa-phone-alt"></i>
                        </div>
                        <div class="contact-info-text">
                            <h3>Llamanos</h3>
                            <p>+591 74095226</p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="far fa-envelope"></i>
                        </div>
                        <div class="contact-info-text">
                            <h3>Nuestro Email</h3>
                            <p>autonic@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="contact-form">
                    <div id="success"></div>
                    <form name="contactForm" id="contactForm" novalidate="novalidate" method="POST">
                        <div class="control-group">
                            <input type="hidden" name="Idmensaje" id="Idmensaje">
                            <input type="hidden" class="form-control" name="Fecha" id="Fecha"
                                value="<?php echo $fecha_actual; ?>">
                            <input type="text" class="form-control" name="Nombre" id="Nombre" placeholder="Nombre"
                                maxlength="30" required="required" />
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="control-group">
                            <input type="tel" class="form-control" name="Telefono" id="Telefono"
                                placeholder="Numero de contacto" oninput="validarLongitud(this, 8,8)"
                                required="required" />
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="control-group">
                            <input type="email" class="form-control" name="Email" id="Email" placeholder="Email"
                                required="required" />
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="control-group">
                            <textarea class="form-control" name="Detalle" id="Detalle" placeholder="Message"
                                required="required" maxlength="150"
                                data-validation-required-message="Please enter your message"></textarea>
                            <p class="help-block text-danger"></p>
                        </div>
                        <div>
                            <button class="btn btn-custom" type="submit" id="btnGuardarWeb">Enviar Mensaje</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-12">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3825.1448561599036!2d-68.21004842605558!3d-16.51878304120682!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x915edf3341321b5d%3A0x879f4fc5bd861609!2sMec%C3%A1nica%20AUTONIC!5e0!3m2!1ses!2sbo!4v1752539515113!5m2!1ses!2sbo"
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</div>
<!-- Contact End -->

<?php
require 'footer.php';
?>
<script type="text/javascript" src="mail/contact.js"></script>