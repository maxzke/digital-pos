<!DOCTYPE html>
<html>
<head>
	<title>DigitalEstudio</title>
	<link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
   <!--Made with love by Mutiullah Samim -->
   
	<!--Bootsrap 4 CDN-->	
	<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap_login.min.css"/>
    
    <!--Fontawesome CDN-->
	<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>assets/css/fontAwesome/css/all.min.css"/>

    <style>
        @import url('<?php echo base_url('assets/css/fontcss.css?family=Numans')?>');

html,body{
background-color: #000;
height: 100%;
font-family: 'Numans', sans-serif;
}

.container{
height: 100%;
align-content: center;
}

.card{
height: 370px;
margin-top: auto;
margin-bottom: auto;
width: 360px;
background-color: rgba(0,0,0,1) !important;
}

.social_icon span{
font-size: 40px;
margin-left: 10px;
color: #ffc412;
}

.social_icon span:hover{
color: white;
cursor: pointer;
}

.card-header h3{
color: white;
}

.social_icon{
position: absolute;
right: 20px;
top: -45px;
}

.input-group-prepend span{
width: 50px;
background-color: #ffc412;
color: black;
border:0 !important;
}

input:focus{
outline: 0 0 0 0  !important;
box-shadow: 0 0 0 0 !important;

}

.remember{
color: white;
}

.remember input
{
width: 20px;
height: 20px;
margin-left: 15px;
margin-right: 5px;
}

.login_btn{
color: black;
background-color: #ffc412;
width: 100px;
}

.login_btn:hover{
color: black;
background-color: white;
}

.links{
color: white;
}

.links a{
margin-left: 4px;
}
	</style>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
    <?php
		// Add any javascripts
		if( isset( $javascripts ) )
		{
			foreach( $javascripts as $js )
			{
				echo '<script src="' . $js . '"></script>' . "\n";
			}
		}

		if( isset( $final_head ) )
		{
			echo $final_head;
		}
	?>
</head>
<body>
<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header">				
				<div class="d-flex justify-content-end social_icon my-5 text-center">
					<span>Digital Estudio</span>
				</div>
				<br><br>
			</div>
			<div class="card-body">
				<?php
				if( ! isset( $on_hold_message ) ){
					
					echo form_open( $login_url, ['class' => 'std-form'] );?>

						<div class="input-group form-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
							</div>
							<input type="text" class="form-control" placeholder="Usuario" name="login_string" id="login_string" autocomplete="off" required/>							
						</div>
						<div class="input-group form-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-key"></i></span>
							</div>
							<input type="password" name="login_pass" id="login_pass" class="form-control" placeholder="Contraseña" <?php
							if( config_item('max_chars_for_password') > 0 )
								echo 'maxlength="' . config_item('max_chars_for_password') . '"'; 
						?> autocomplete="off" readonly="readonly" onfocus="this.removeAttribute('readonly');" />

							
						</div>
						<div class="row align-items-center remember">
							<!-- <input type="checkbox">Remember Me -->
						</div>
						<div class="form-group">
							<input type="submit" value="ENTRAR" class="btn float-right login_btn">
						</div>
					</form>
				<?php
				if( isset( $login_error_mesg ) ){
						echo '
							<div class="text-white text-center mt-4">
								<h5>
									Login Error #' . $this->authentication->login_errors_count . '/' . config_item('max_allowed_attempts') . '
								</h5>
							</div>
						';
					}

				if( $this->input->get(AUTH_LOGOUT_PARAM) ){
						echo '
							<br>
							<div class="text-white text-center mt-4">
								<h5>
									Sesión Finalizada.
								</h5>
							</div>
						';
					}

				}else
					{
						// EXCESSIVE LOGIN ATTEMPTS ERROR MESSAGE
						echo '
							<div class="text-white text-center mt-4">
								<h5>
									Problemas para iniciar sesión 
									<br><br>
									Contacta al Administrador
								</h5>
							</div>
						';
					}
					?>
			</div>
			<!--<div class="card-footer">
				 <div class="d-flex justify-content-center links">
					Don't have an account?<a href="#">Sign Up</a>
				</div>
				<div class="d-flex justify-content-center">
					<a href="#">Forgot your password?</a>
				</div> 
			</div>-->
		</div>
	</div>
</div>
<script src="<?php echo base_url()?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url()?>assets/js/bootstrap_login.min.js"></script>

</body>
</html>
