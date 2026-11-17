     
<?php
require 'header.php';
?>
        <!-- Page Header Start -->
        <div class="page-header">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2>Servicio</h2>
                    </div>
                    <div class="col-12">
                        <a href="home.php">Inicio</a>
                        <a href="service.php">Nustros Servicios</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header End -->
        
        
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

<?php
require 'footer.php';
?>

<script>
function init() {
    $('#service').addClass("active");
}
init();
</script>