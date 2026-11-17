<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"]))
{
  header("Location: login.html");
}
else
{
require 'header.php';
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
                                                    <h5>Ayuda</h5>
                                                    <div class="card-header-right">
                                                        <div class="btn-group card-option">
                                                            <button type="button" class="btn dropdown-toggle btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="feather icon-more-horizontal"></i>
                                                            </button>
                                                            <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                                                <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a>
                                                                </li>
                                                                <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                                                                <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
                                                                <li class="dropdown-item close-card"><a href="#!"><i class="feather icon-trash"></i> remove</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            <!-- /.card-header -->
                                            <div class="card-body">
                                            <h4>Manual: </h4>  <a href="../manual/Manual_de_usuario.pdf" target="_blank" class="btn btn-success">
                                                <i class="fa fa-book"> </i> Manual
                                            </a>
                                            <h4>Desarrollado por: </h4> <p>Bladimir Denilson Hidalgo Gemio</p> 
                                            <!-- Botón de copia de seguridad -->
                                            <h4>Exportar Base de Datos: </h4> 
                                            <form action="../config\backup.php" method="post">
                                                <button type="submit" class="btn btn-info"><i class="fa fa-database" aria-hidden="true"> </i>Exportar</button>
                                            </form>
                                            </div>
                                        <!-- /.card -->
                                            
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
require 'footer.php';
?>
<?php 
}
ob_end_flush();
?>