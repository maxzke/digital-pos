<div class="main-panel">
    <div class="content">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">Listado de Usuarios</h4>
            </div>
            <div class="row">
				<div class="col-md-12">
				<!-- CONTENIDO -->
					<div class="pull-right">
						<a href="<?php echo site_url('user/add'); ?>" class="btn btn-success">
							Agregar
						</a> 
					</div>
					<?php 
						if ($this->session->flashdata('usuario_creado')){ ?>
							<div class="alert alert-success alert-dismissible fade show" role="alert">
								Usuario <strong><?php echo $_SESSION['usuario_creado']; ?></strong>  creado con exito!
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
						<?php
						}
					?>
					<table id="content-table" class="table table-sm table-striped table-bordered">
						<thead>
							<tr>
								<th></th>
								<th>Nivel</th>
								<th>Nombre de Usuario</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>						
						<?php 
						$id=1;
						foreach($users as $u): ?>
						<?php if ($u['username'] != "superAdmin"):?>
							<tr>
								<td><?php echo $id;//$u['user_id']; 
								$id++?></td>
								<td><?php 
										switch ($u['auth_level']) {
											case '1':
												echo "Cocina";
												break;
											case '6':
												echo "Mesero";
												break;
											case '9':
												echo "Administrador";
												break;
										} ?>
								</td>
								<td><?php echo $u['username']; ?></td>
								<td>
									<!-- <a href="<?php //echo site_url('user/edit/'.$u['user_id']); ?>" class="btn btn-sm btn-info btn-xs">Edit</a>  -->
									<a href="<?php echo site_url('user/remove/'.$u['user_id']); ?>" data-url="<?php echo site_url('user/remove/'.$u['user_id']); ?>" class="btn btn-sm btn-danger" onClick="confirmar_alert(this);">Eliminar</a>
								</td>
							</tr>
									<?php endif; ?>
						<?php endforeach; ?>
					</tbody>
					</table>
					<div class="pull-right">
						<?php echo $this->pagination->create_links(); ?>    
					</div>

            	<!-- /CONTENIDO -->
				</div>
            </div>
        </div>
    </div>
</div>