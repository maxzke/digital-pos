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
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/alertify.core.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/alertify.default.css">
    

    
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="navbar-brand">Digital Estudio</div>
      
        <div class="collapse navbar-collapse" id="navbarColor02">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="#"><i class="fas fa-shopping-cart"></i> POS
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#"><i class="fas fa-file-alt"></i> Ventas
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#"><i class="fas fa-hand-holding-usd"></i> Pagos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#"><i class="fab fa-cc-visa"></i> Tarjeta</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#"><i class="fas fa-chart-line"></i> Corte</a>
            </li>
            <!-- <li class="nav-item">
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
            </li> -->
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
    <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/alertify.min.js"></script>


    <!-- NOTA VENTA -->
    <script src="<?php echo base_url(); ?>assets/js/sisadmin_notas.js"></script>
</body>

</html>