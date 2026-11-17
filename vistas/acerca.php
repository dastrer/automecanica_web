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
                                                <h5>Acerca de</h5>
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
                                            <h4>Proyecto: </h4> <p>“SISTEMA WEB PARA LA GESTION DE SERVICIOS E INVENTARIO DE ARTICULOS Y REPUESTOS PARA VEHICULOS”. 
                                            CASO: “TALLER DE MECANICA AUTONIC”
                                            </p>
                                            <h4>Postulante: </h4> <p>Bladimir Denilson Hidalgo Gemio</p>
                                            <h4>Tutor: </h4> <p>Fredy Irineo Huiza</p>
                                            <h4>Revisor: </h4> <p></p>
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


