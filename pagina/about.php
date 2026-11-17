<?php
require 'header.php';
?>
        
        <!-- Page Header Start -->
        <div class="page-header">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2>Acerca de Nosotros</h2>
                    </div>
                    <div class="col-12">
                        <a href="home.php">Incio</a>
                        <a href="about.php">Acerca de nosotros</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header End -->
        



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
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About End -->

        
        

<?php
require 'footer.php';
?>
<script>
function init() {
    $('#acerca').addClass("active");
}
init();
</script>
