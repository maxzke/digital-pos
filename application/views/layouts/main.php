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
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"> -->
    
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/alertify.core.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/alertify.default.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/sweetalert2.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.css">
    <style>
      @import url('<?php echo base_url('assets/css/fontcss.css?family=Numans')?>');
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="navbar-brand">Digital Estudio</div>
        <input type="hidden" id="url_base" value="<?php echo site_url(); ?>">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      
        <div class="collapse navbar-collapse" id="navbarColor02">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item <?php echo $active == "pos" ? "active" : "" ; ?>">
              <a class="nav-link" href="<?php echo site_url("pos"); ?>"><i class="fas fa-shopping-cart"></i> POS
              </a>
            </li>
            <li class="nav-item <?php echo $active == "ventas" ? "active" : "" ; ?>">
              <a class="nav-link" href="<?php echo site_url("ventas"); ?>"><i class="far fa-file-alt"></i> Ventas
              </a>
            </li>
            <li class="nav-item <?php echo $active == "pagos" ? "active" : "" ; ?>">
              <a class="nav-link" href="<?php echo site_url("pagos"); ?>"><i class="fas fa-hand-holding-usd"></i> Pagos</a>
            </li>
            <li class="nav-item <?php echo $active == "corte" ? "active" : "" ; ?>">
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
              <input type="hidden" id="txt_idUser" value="<?php echo $auth_username; ?>">
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
    <script src="<?php echo base_url(); ?>assets/js/alertify.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery-3.6.0.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <!-- SWEET ALERT2-->
    <script src="<?php echo base_url(); ?>assets/js/sweetalert2.all.min.js"></script>    
    <!-- NOTA VENTA -->
    <script src="<?php echo base_url(); ?>assets/js/sisadmin_notas.js"></script>
</body>
</html>

