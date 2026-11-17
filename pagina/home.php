<?php
require 'header.php';
require_once "../modelos/Consulta.php";
$consulta = new Consultas();
$rsptai = $consulta->usuarioPagina();
$regi = $rsptai->fetch_object();
$totalu = $regi->cantidad;

$rsptac = $consulta->clientePagina();
$regc = $rsptac->fetch_object();
$totalc = $regc->cantidad;

$rsptas = $consulta->servicioPagina();
$regs = $rsptas->fetch_object();
$totals = $regs->cantidad;
date_default_timezone_set('America/La_Paz');
$fecha_actual = date("Y-m-d");
?>

<!-- Carousel Start -->
<div class="carousel">
    <div class="container-fluid">
        <div class="owl-carousel">
            <!-- CAMBIO DE ACEITE -->
            <div class="carousel-item">
                <div class="carousel-img">
                    <img src="img/carousel-1.jpg" alt="Cambio de aceite">
                </div>
                <div class="carousel-text">
                    <h3>Cambio de Aceite</h3>
                    <h1>Protegé el Motor de tu Vehículo</h1>
                    <p>
                        Realizamos cambio de aceite y filtro con productos de calidad para alargar la vida útil del
                        motor.
                    </p>
                    <a class="btn btn-custom" href="service.php">Ver más</a>
                </div>
            </div>

            <!-- REPARACIÓN DE ESCAPE -->
            <div class="carousel-item">
                <div class="carousel-img">
                    <img src="img/carousel-2.jpg" alt="Reparación de escape">
                </div>
                <div class="carousel-text">
                    <h3>Reparación de Escape</h3>
                    <h1>Soluciones Rápidas y Eficientes</h1>
                    <p>
                        Detectamos y reparamos fugas o daños en el sistema de escape para mejorar el rendimiento y
                        reducir ruidos.
                    </p>
                    <a class="btn btn-custom" href="service.php">Ver más</a>
                </div>
            </div>

            <!-- SERVICIO DE MOTOR -->
            <div class="carousel-item">
                <div class="carousel-img">
                    <img src="img/carousel-3.jpg" alt="Servicio de motor">
                </div>
                <div class="carousel-text">
                    <h3>Servicio de Motor</h3>
                    <h1>Diagnóstico y Reparación Profesional</h1>
                    <p>
                        Atendemos fallas mecánicas, afinamiento, limpieza de inyectores y más para mantener tu motor en
                        óptimas condiciones.
                    </p>
                    <a class="btn btn-custom" href="service.php">Ver más</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Carousel End -->


<!-- About Start -->
<div class="about">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-img">
                    <img src="img/about.jpg" alt="Image">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-header text-left">
                    <p>Acerca de Nosotros</p>
                    <h2>Mecanica Automotriz Autonic</h2>
                </div>
                <div class="about-content">
                    <p style="text-align: justify;">
                        El “TALLER MECANICO DE AUTOMOVILES AUTONIC” viene brindando sus servicios por más de 6 años,
                        brinda los servicios de reparación de automóviles de todo tipo de marcas y modelos con el fin de
                        generar
                        ingresos y satisfacer con pronta atención puntualidad y eficacia a la hora de reparar los
                        automóviles
                        destacándose por la profesionalidad que se brinda al público en general. Fue creada en el año
                        2019 en la
                        ciudad de El Alto Bolivia, como propietario se tiene al Sr. Richard Calle.
                    </p>
                    <ul>
                        <li><i class="far fa-check-circle"></i>Cambio de aceite</li>
                        <li><i class="far fa-check-circle"></i> Revisión y cambio de frenos</li>
                        <li><i class="far fa-check-circle"></i> Alineación y balanceo</li>
                        <li><i class="far fa-check-circle"></i> Mantenimiento preventivo</li>
                        <li><i class="far fa-check-circle"></i> Revisión del sistema de suspensión</li>
                        <li><i class="far fa-check-circle"></i> Cambio de filtros (aire, combustible, cabina)</li>
                        <li><i class="far fa-check-circle"></i> Revisión eléctrica y de batería</li>
                    </ul>
                    <a class="btn btn-custom" href="about.php">Leer Mas</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About End -->


<!-- Service Start -->
<div class="service">
    <div class="container">
        <div class="section-header text-center">
            <p>¿Qué ofrecemos?</p>
            <h2>Servicios de Mecánica Automotriz</h2>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="service-item">
                    <i class="flaticon-seat"></i>
                    <h3>Lavado Gratis</h3>
                    <p>Disfruta de un lavado exterior sin costo adicional al realizar tu servicio mecánico.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="service-item">
                    <i class="flaticon-car-wash-1 "></i>
                    <h3>Lavado de Inyectores por Ultrasonido</h3>
                    <p>Proceso avanzado para limpiar los inyectores y mejorar el rendimiento del motor.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="service-item">
                    <i class="flaticon-car-wash"></i>
                    <h3>Cambio de Aceite</h3>
                    <p>Reemplazo de aceite de motor y filtro para un rendimiento óptimo del vehículo.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="service-item">
                    <i class="flaticon-brush-1"></i>
                    <h3>Servicio de Frenos</h3>
                    <p>Revisión, ajuste y cambio de pastillas, discos y líquido de frenos.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Service End -->


<!-- Facts Start -->
<div class="facts" data-parallax="scroll" data-image-src="img/facts.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="facts-item">
                    <i class="fa fa-map-marker-alt"></i>
                    <div class="facts-text">
                        <h3 data-toggle="counter-up">1</h3>
                        <p>Puntos de Servicio</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="facts-item">
                    <i class="fa fa-user"></i>
                    <div class="facts-text">
                        <h3 data-toggle="counter-up"><?php echo $totalu; ?></h3>
                        <p>Mecánicos & Trabajadores</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="facts-item">
                    <i class="fa fa-users"></i>
                    <div class="facts-text">
                        <h3 data-toggle="counter-up"><?php echo $totalc; ?></h3>
                        <p>Clientes que Confían en Nosotros</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="facts-item">
                    <i class="fa fa-check"></i>
                    <div class="facts-text">
                        <h3 data-toggle="counter-up"><?php echo $totals; ?></h3>
                        <p>Servicios Completados</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Facts End -->




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

<!-- Blog Start -->
<div class="blog">
    <div class="container">
        <div class="section-header text-center">
            <p>Nuestro Blog</p>
            <h2>Últimas noticias y artículos</h2>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="blog-item">
                    <div class="blog-img">
                        <img src="img/blog-1.jpg" alt="Image">
                    </div>
                    <div class="blog-text">
                        <h3><a href="#">ICE FREEZE ORGANICO 50-50 GREEN</a></h3>
                        <p  style="text-align: justify;">
                            Refrigerante con tecnología OAT (Organic Acid Technology) que inhiben la corrosión de todo
                            tipo de metales, especialmente aluminio. Libre de Fosfatos, nitratos, nitritos, aminas y
                            silicatos. Listo para usar compatible con con tecnologia similar.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="blog-item">
                    <div class="blog-img">
                        <img src="img/blog-2.jpg" alt="Image">
                    </div>
                    <div class="blog-text">
                        <h3><a href="#">Líquido De Frenos Sintético DOT 4</a></h3>
                        <p  style="text-align: justify;">
                            Líquido De Frenos Sintético DOT 4 Recomendado en especialmente para las motos que cuentan
                            con sistemas de seguridad activa como ABS y EBD, proporcionando un frenado seguro incluso
                            tras un largo periodo de uso.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="blog-item">
                    <div class="blog-img">
                        <img src="img/blog-3.jpg" alt="Image">
                    </div>
                    <div class="blog-text">
                        <h3><a href="#">Aceites, Aplicación Motor a Gasolina</a></h3>
                        <p  style="text-align: justify;">
                            Lubricante motor a gasolina Mobil Super 15W40 5L. Lubricante formulado a partir de bases
                            lubricantes de alta calidad combinadas con modernos aditivos de alto desempeño, que
                            proporcionan una comprobada protección y un óptimo rendimiento
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Blog End -->

<?php
require 'footer.php';
?>

<script type="text/javascript" src="js/home.js"></script>