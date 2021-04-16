<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>DigitalEstudio</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="<?php echo base_url(); ?>assets/img/icon.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="<?php echo base_url(); ?>assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Lato:300,400,700,900"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ['<?php echo base_url(); ?>assets/css/fonts.min.css']
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.css">

    
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Digital Estudio</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      
        <div class="collapse navbar-collapse" id="navbarColor02">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="#">Terminal
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Ventas
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Historial</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Creditos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Corte</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('configuracion') }}">Configuracion</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Dropdown</a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Separated link</a>
              </div>
            </li>
          </ul>
          <ul class="navbar-nav ml-auto">
            <li class="nav-link text-capitalize">
              <i class="fas fa-user text-success"></i>
              <?php echo $auth_username; ?>
            </li>
            <li class="nav-item dropdown">              
                <a class="nav-link dropdown-toggle" 
                data-toggle="dropdown" 
                href="#" role="button" 
                aria-haspopup="true" 
                aria-expanded="false">Cerrar sesión</a>
                <div class="dropdown-menu">
                  <form method="POST" action="<?php echo site_url('login/logout') ?>">
                    <a class="dropdown-item" 
                      href="<?php echo site_url('login/logout') ?>" 
                      onclick="event.preventDefault(); this.closest('form').submit();">Salir</a>
                  </form>
                </div>
              </li>
          </ul>            
        </div>
      </nav>
      <div class="container-fluid">
        <!-- Sidebar -->
        <!-- End Sidebar -->
        <!-- Custom template | don't include it in your project! -->		
            <?php	if(isset($_view) && $_view)
                $this->load->view($_view);
            ?> 	
        <!-- End Custom template -->
    </div><!-- /wrapper -->
    <!--   Core JS Files   -->
    <script src="<?php echo base_url(); ?>assets/js/core/jquery.3.2.1.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/core/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery UI -->
    <script src="<?php echo base_url(); ?>assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="<?php echo base_url(); ?>assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>


    <!-- Chart JS -->
    <script src="<?php echo base_url(); ?>assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="<?php echo base_url(); ?>assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="<?php echo base_url(); ?>assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- DataTables Responsivo-Botones Export-   -->
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/plugin/datatables/datatables/JSZip-2.5.0/jszip.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/plugin/datatables/datatables/pdfmake-0.1.32/pdfmake.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/plugin/datatables/datatables/pdfmake-0.1.32/vfs_fonts.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/plugin/datatables/datatables/DataTables-1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/plugin/datatables/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/plugin/datatables/datatables/Buttons-1.5.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/plugin/datatables/datatables/Buttons-1.5.1/js/buttons.bootstrap4.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/plugin/datatables/datatables/Buttons-1.5.1/js/buttons.colVis.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/plugin/datatables/datatables/Buttons-1.5.1/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/plugin/datatables/datatables/Buttons-1.5.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/plugin/datatables/datatables/Buttons-1.5.1/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/plugin/datatables/datatables/Responsive-2.2.1/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/plugin/datatables/datatables/Responsive-2.2.1/js/responsive.bootstrap4.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="<?php echo base_url(); ?>assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="<?php echo base_url(); ?>assets/js/plugin/jqvmap/jquery.vmap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

    <!-- Sweet Alert -->
    <script src="<?php echo base_url(); ?>assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Atlantis JS -->
    <script src="<?php echo base_url(); ?>assets/js/atlantis.min.js"></script>

    <!-- Data Tables Personalizadas -->
    <script src="<?php echo base_url(); ?>assets/js/draggable.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/tablas.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/app.js"></script>
    <!-- ALERTAS COCINA -->
    <script src="<?php echo base_url(); ?>assets/js/alertas_cocina.js"></script>
</body>

</html>