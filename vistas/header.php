<!DOCTYPE html>
<html lang="en">

<head>

	<title>Sistema Autonic</title>
	<!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 11]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	<!-- Meta -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description"
		content="Flash Able Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
	<meta name="keywords"
		content="admin templates, bootstrap admin templates, bootstrap 4, dashboard, dashboard templets, sass admin templets, html admin templates, responsive, bootstrap admin templates free download,premium bootstrap admin templates, Flash Able, Flash Able bootstrap admin template">
	<meta name="author" content="Codedthemes" />


	<!-- Favicon icon -->
	<link rel="icon" href="../assets/images/Rectangle.svg" type="image/x-icon">
	<!-- fontawesome icon -->
	<link rel="stylesheet" href="../assets/fonts/fontawesome/css/fontawesome-all.min.css">
	<!-- animation css -->
	<link rel="stylesheet" href="../assets/plugins/animation/css/animate.min.css">
	<!-- Data tables -->
	<link rel="stylesheet" href="../assets/plugins/jquery-scrollbar/css/perfect-scrollbar.css">
	<!-- CSS de DataTables para Bootstrap 4 -->
	<link rel="stylesheet" href="../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="../assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" href="../assets/plugins/bootstrap-select-1.13.18/css/bootstrap-select.min.css">
	<!-- vendor css -->
	<link rel="stylesheet" href="../assets/css/style.css">
	<!-- iCheck for checkboxes and radio inputs -->
	<link rel="stylesheet" href="../assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- animation css -->
	<link rel="stylesheet" href="../assets/plugins/animation/css/animate.min.css">
	<!-- morris css -->
	<link rel="stylesheet" href="../assets/plugins/chart-morris/css/morris.css">
	<link rel="stylesheet" href="../assets/plugins/sweetalert2/sweetalert2.min.css">

</head>

<body class="">
	<!-- [ Pre-loader ] start -->
	<div class="loader-bg">
		<div class="loader-track">
			<div class="loader-fill"></div>
		</div>
	</div>
	<!-- [ Pre-loader ] End -->

	<!-- [ navigation menu ] start -->
	<nav class="pcoded-navbar menupos-fixed menu-light brand-red ">
		<div class="navbar-wrapper ">
			<div class="navbar-brand header-logo">
				<a href="escritorio.php" class="b-brand">
					<img src="../assets/images/logo.png" alt="" class="logo images">
					<img src="../assets/images/LOGOBLANCOSVG.svg" alt="" class="logo-thumb images">
				</a>
				<a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
			</div>
			<div class="navbar-content scroll-div">
				<ul class="nav pcoded-inner-navbar">
					<li class="nav-item pcoded-menu-caption">
						<label>Navegacion</label>
					</li>
					<?php
					if ($_SESSION['Escritorio'] == 1) {
						echo '<li class="nav-item">
								<a href="escritorio.php" class="nav-link"><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Escritorio</span></a>
							</li>';
					}
					?>
					<li class="nav-item pcoded-menu-caption">
						<label>MENU</label>
					</li>
					<?php

					if ($_SESSION['Almacen'] == 1) {
						echo '<li class="nav-item pcoded-hasmenu">
						<a href="#" class="nav-link"><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Almacen</span></a>
						<ul class="pcoded-submenu">
							<li class=""><a href="unidad.php" class="">Unidad Medida</a></li>
							<li class=""><a href="categoria.php" class="">Categoria</a></li>
							<li class=""><a href="articulo.php" class="">Articulos</a></li>
						</ul>
					</li>';
					}
					?>
					<?php
					if ($_SESSION['Compra'] == 1) {
						echo '<li class="nav-item pcoded-hasmenu">
						<a href="#" class="nav-link"><span class="pcoded-micon"><i class="feather icon-shopping-cart"></i></span><span class="pcoded-mtext">Compra</span></a>
						<ul class="pcoded-submenu">
							<li class=""><a href="ingreso.php" class="">Ingreso</a></li>
							<li class=""><a href="proveedor.php" class="">Proveedor</a></li>
						</ul>
					</li>';
					}
					?>
					<?php
					if ($_SESSION['Vehículos'] == 1) {
						echo '<li class="nav-item pcoded-hasmenu">
						<a href="#" class="nav-link"><span class="pcoded-micon"><img src="../assets/images/truck.svg" alt="Ícono de camión" width="14" height="14"></span><span class="pcoded-mtext">Vehiculo</span></a>
						<ul class="pcoded-submenu">
							<li class=""><a href="cliente.php" class="">Cliente</a></li>
							<li class=""><a href="vehiculo.php" class="">Vehiculo</a></li>
						</ul>
					</li>';
					}
					?>
					<?php
					if ($_SESSION['Servicio'] == 1) {
						echo '<li class="nav-item pcoded-hasmenu">
						<a href="#" class="nav-link"><span class="pcoded-micon"><i class="feather icon-settings"></i></span><span class="pcoded-mtext">Servicio</span></a>
						<ul class="pcoded-submenu">
							<li class=""><a href="item_diagnostico.php" class="">Items de Diagnóstico</a></li>
							<li class=""><a href="servicio.php" class="">Servicio de Reparación</a></li>
							<li class=""><a href="cotizacion.php" class="">Cotizaciones</a></li>
							<li class=""><a href="mensaje.php" class="">Mensajes</a></li>
						</ul>
					</li>';
					}
					?>
					<?php
					if ($_SESSION['Venta'] == 1) {
						echo '<li class="nav-item pcoded-hasmenu">
						<a href="#" class="nav-link"><span class="pcoded-micon"><i class="feather icon-credit-card"></i></span><span class="pcoded-mtext">Venta</span></a>
						<ul class="pcoded-submenu">
							<li class=""><a href="cliente.php" class="">Cliente</a></li>
							<li class=""><a href="venta.php" class="">Venta</a></li>
						</ul>
					</li>';
					}
					?>
					<?php
					if ($_SESSION['Acceso'] == 1) {
						echo '  <li class="nav-item pcoded-hasmenu">
						<a href="#" class="nav-link"><span class="pcoded-micon"><i class="feather icon-user"></i></span><span class="pcoded-mtext">Acceso</span></a>
						<ul class="pcoded-submenu">
							<li class=""><a href="usuario.php" class="">Usuario</a></li>
							<li class=""><a href="permiso.php" class="">Permiso</a></li>
						</ul>
					</li>';
					}
					?>
					<li class="nav-item pcoded-menu-caption">
						<label>REPORTES</label>
					</li>
					<?php
					if ($_SESSION['ConsultaS'] == 1) {
						echo '<li class="nav-item pcoded-hasmenu">
						<a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-clipboard"></i></span><span class="pcoded-mtext">Consulta Servicios</span></a>
						<ul class="pcoded-submenu">
							<li class=""><a href="consulta_servicio.php" class="" >Servicio General</a></li>
							<li class=""><a href="consulta_servicio_art.php" class="" >Servicio Articulo</a></li>
							<li class=""><a href="consulta_pagos.php" class="" >Pagos</a></li>
						</ul>
					</li>';
					}
					?>
					<?php
					if ($_SESSION['ConsultaC'] == 1) {

						echo '<li class="nav-item pcoded-hasmenu">
						<a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-bar-chart-2"></i></span><span class="pcoded-mtext">Consulta Compras</span></a>
						<ul class="pcoded-submenu">
							<li class=""><a href="consulta_ingreso.php" class="" >Ingreso</a></li>
						</ul>
					</li>';
					}
					?>
					<?php
					if ($_SESSION['ConsultaV'] == 1) {
						echo '<li class="nav-item pcoded-hasmenu">
						<a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-bar-chart"></i></span><span class="pcoded-mtext">Consulta Ventas</span></a>
						<ul class="pcoded-submenu">
							<li class=""><a href="consulta_venta.php" class="" >Venta Articulo</a></li>
							<li class=""><a href="consulta_venta_gral.php" class="" >Venta General</a></li>
						</ul>
					</li>';
					}
					?>
					<li class="nav-item pcoded-menu-caption">
						<label>SOPORTE</label>
					</li>

					<li class="nav-item"><a href="acerca.php" class="nav-link"><span class="pcoded-micon"><i
									class="feather icon-book"></i></span><span class="pcoded-mtext">Acerca</span>
							<span class="right badge badge-danger">PDF</span></a></li>
					<li class="nav-item"><a href="ayuda.php" class="nav-link"><span class="pcoded-micon"><i
									class="feather icon-info"></i></span><span class="pcoded-mtext">Ayuda</span>
							<span class="right badge badge-warning">IT</span></a></li>
				</ul>

			</div>
		</div>
	</nav>
	<!-- [ navigation menu ] end -->

	<!-- [ Header ] start -->
	<header class="navbar pcoded-header navbar-expand-lg navbar-light headerpos-fixed ">
		<div class="m-header ">
			<a class="mobile-menu" id="mobile-collapse1" href="#!"><span></span></a>
			<a href="index.html" class="b-brand">
				<img src="../assets/images/logo.png" alt="" class="logo images">
				<img src="../assets/images/LOGOBLANCOSVG.svg" alt="" class="logo-thumb images">
			</a>
		</div>
		<a class="mobile-menu" id="mobile-header" href="#!">
			<i class="feather icon-more-horizontal"></i>
		</a>
		<div class="collapse navbar-collapse">
			<ul class="navbar-nav ml-auto">
				<li>
					<div class="dropdown">
						<a class="dropdown-toggle" href="#" data-toggle="dropdown">
							<i class="icon feather icon-mail"></i>
							<span class="badge badge-primary" id="notification-badge-mail"
								style="position: absolute;top: 20px;right: 35px;padding: 3px 6px;border-radius: 10px;">
								0 </span>
						</a>
						<div class="dropdown-menu dropdown-menu-right notification">
							<div class="noti-head">
								<h6 class="d-inline-block m-b-0" id="notification-header-mail">Mensajes</h6>
								<div class="float-right">
								</div>
							</div>

							<ul class="noti-body" id="notification-mail">
								<!-- Aquí se van a inyectar las notificaciones -->
							</ul>

							<div class="noti-footer">
								<a href="mensaje.php">Mostrar todo</a>
							</div>
						</div>
					</div>
				</li>

				<li>
					<div class="dropdown">
						<a class="dropdown-toggle" href="#" data-toggle="dropdown">
							<i class="icon feather icon-bell"></i>
							<span class="badge badge-danger" id="notification-badge"
								style="position: absolute;top: 20px;right: 35px;padding: 3px 6px;border-radius: 10px;">
								0 </span>
						</a>
						<div class="dropdown-menu dropdown-menu-right notification">
							<div class="noti-head">
								<h6 class="d-inline-block m-b-0" id="notification-header">Notifications</h6>
								<div class="float-right">
								</div>
							</div>
							<ul class="noti-body" id="notification-items">
								<!-- Aquí se van a inyectar las notificaciones -->
							</ul>
							<div class="noti-footer">
								<a href="articulo.php">Mostrar todo</a>
							</div>
						</div>
					</div>
				</li>
				<li>
					<div class="dropdown drp-user">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon feather icon-users"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-right profile-notification">
							<div class="pro-head">
								<img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="img-radius"
									alt="User-Profile-Image">
								<span><?php echo $_SESSION['nombre']; ?></span>

							</div>
							<ul class="pro-body">
								<li><a href="usuario.php" class="dropdown-item"><i class="feather icon-user"></i>
										Usuario</a></li>
								<li><a href="../ajax/usuario.php?op=salir" class="dropdown-item"><i
											class="feather icon-log-out"></i> Salir</a></li>
							</ul>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</header>
	<!-- [ Header ] end -->