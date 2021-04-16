<div class="main-panel">
    <div class="content">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">Agregar Nuevo Usuario</h4>
                <ul class="breadcrumbs">
                    <li class="nav-home">
                        <a href="<?php echo site_url('user'); ?>">
                            <i class="flaticon-home"></i> Volver
                        </a>
                    </li>
                </ul>
            </div>
            <div class="row">
				<div class="col-md-12">
				<!-- CONTENIDO -->
					<div class="row mt-1">
						

						<div class="col-md-4 mx-auto">
						<?php echo form_open('login/create_user',array("class"=>"form-horizontal")); ?>

							<div class="form-group">
								<label for="auth_level" class="col-md-4 control-label"
								data-toggle="tooltip" 
								data-placement="right" 
								title="Seleccionar el tipo de usuario que se desea registrar">
								Nivel</label>
								<div class="col-md-12">
									<select name="auth_level" class="form-control">
										<option value="">Seleccionar</option>
										<?php 
										$auth_level_values = array(
											'1'=>'Cocina',
											'6'=>'Mesero',
											'9'=>'Administrador',
										);

										foreach($auth_level_values as $value => $display_text)
										{
											$selected = ($value == $this->input->post('auth_level')) ? ' selected="selected"' : "";

											echo '<option value="'.$value.'" '.$selected.'>'.$display_text.'</option>';
										} 
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="username" class="col-md-4 control-label">Usuario</label>
								<div class="col-md-12">
									<input type="text" name="username" 
									value="<?php echo $this->input->post('username'); ?>" 
									class="form-control" id="username" 
									placeholder="Máximo 12 caracteres"/>
								</div>
							</div>
							<!-- <div class="form-group">
								<label for="email" class="col-md-4 control-label">Email</label>
								<div class="col-md-12">
									<input type="text" name="email" value="<?php //echo $this->input->post('email'); ?>" class="form-control" id="email" />
								</div>
							</div> -->
							<div class="form-group">
								<label for="passwd" class="col-md-4 control-label">Contraseña</label>
								<div class="col-md-12">
									<input type="password" name="passwd" value="<?php echo $this->input->post('passwd'); ?>" class="form-control" id="passwd" />
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-offset-4 col-sm-8">
									<button type="submit" class="btn btn-success">Guardar</button>
								</div>
							</div>

							

						<?php echo form_close(); ?>

						</div>
						<div class="col-md-3">
							<?php 
								if ($this->session->flashdata('login_error')){?>
									<div class="alert alert-warning" role="alert">
										<?php echo $_SESSION['login_error']; ?>
									</div>
								<?php
								}else{?>
									<div class="alert alert-warning" role="alert">
										<h2 class="text-danger">Contraseña</h2> 
										<span>Debe contener:</span>							
											<ul class="mt-3">
												<li>Al menos 8 caracteres</li>
												<li>1 Número</li>
												<li>1 Minúscula</li>
												<li>1 Mayúscula</li>
											</ul>
										</span>
									</div>
								<?php
								}
							?>
						</div>
					</div>
            	<!-- /CONTENIDO -->
				</div>
            </div>
        </div>
    </div>
</div>