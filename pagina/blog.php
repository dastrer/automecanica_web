<?php
require 'header.php';
?>

<!-- Page Header Start -->
<div class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Artículos</h2>
            </div>
            <div class="col-12">
                <a href="home.php">Incio</a>
                <a href="blog.php">Artículos</a>
            </div>
        </div>
    </div>
</div>
<!-- Page Header End -->


<!-- Blog Start -->
<div class="blog">
    <div class="container">
        <div class="section-header text-center">
            <p>Artículos</p>
            <h2>Repuestos & Artículos</h2>
        </div>
        <div class="row">
                <?php
                require_once "../modelos/Articulo.php";

                $articulo = new Articulo();

                // Configuración de paginación
                $results_per_page = 6; // Ajustado para mostrar 15 productos
                $total_records = $articulo->contarRegistros();
                $total_pages = ceil($total_records / $results_per_page);
                $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
                $current_page = max(1, min($current_page, $total_pages));
                $start_from = ($current_page - 1) * $results_per_page;

                // Obtener los productos
                $resultado = $articulo->listarWeb2($start_from, $results_per_page);
                if ($resultado) {
                    while ($row = $resultado->fetch_assoc()) {
                        $nombre_producto = $row['Nombre'];
                        $precio_venta = $row['Precio_venta'];
                        $imagen = !empty($row['Imagen']) ? $row['Imagen'] : 'default.jpg';
                        $descripcion = $row['Descripcion'];
                        $marca = $row['Marca'];
                        $unidadmedida = $row['Unidadmedida'];
                        // Generar estructura HTML para cada producto
                        echo '
                    <div class="col-lg-4">
                        <div class="blog-item">
                            <div class="blog-img">
                                <img src="../files/articulo/' . $imagen . '" class="img-fluid  img-fija" alt="Producto">
                                <div class="meta-date">
                                    <span>' . $marca . '</span>
                                    <span>Bs/.' . $precio_venta . '</span>
                                </div>
                            </div>
                            <div class="blog-text">
                                <h3><a href="#">' . $nombre_producto . '</a></h3>
                                <p>
                                   ' . $descripcion . '
                                </p>
                            </div>
                               <div class="blog-meta">
                                    <p><i class="fa fa-box"></i><a href="">' . $unidadmedida . '</a></p>
                                    <p><i class="fa fa-award"></i><a href="">' . $marca . '</a></p>
                                    <p><i class="fa fa-coins"></i><a href="">Bs/.' . $precio_venta . '</a></p>
                                </div>
                        </div>
                    </div>';
                    }
                }
                ?>

        </div>


 <div class="row"> 
    <div class="col-12">
        <ul class="pagination justify-content-center">
            <!-- Botón Anterior -->
            <li class="page-item <?php if ($current_page == 1) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo max(1, $current_page - 1); ?>">Previous</a>
            </li>

            <!-- Números de página -->
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php if ($i == $current_page) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <!-- Botón Siguiente -->
            <li class="page-item <?php if ($current_page == $total_pages) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo min($total_pages, $current_page + 1); ?>">Next</a>
            </li>
        </ul>
    </div>
</div>

    </div>
</div>
<!-- Blog End -->

<?php
require 'footer.php';
?>