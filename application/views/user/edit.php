<?php echo form_open('user/edit/'.$user['user_id'],array("class"=>"form-horizontal")); ?>

	<div class="form-group">
		<label for="auth_level" class="col-md-4 control-label">Auth Level</label>
		<div class="col-md-8">
			<select name="auth_level" class="form-control">
				<option value="">select</option>
				<?php 
				$auth_level_values = array(
					'1'=>'Empleado',
					'6'=>'Vendedor',
					'9'=>'Administrador',
				);

				foreach($auth_level_values as $value => $display_text)
				{
					$selected = ($value == $user['auth_level']) ? ' selected="selected"' : "";

					echo '<option value="'.$value.'" '.$selected.'>'.$display_text.'</option>';
				} 
				?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="username" class="col-md-4 control-label">Username</label>
		<div class="col-md-8">
			<input type="text" name="username" value="<?php echo ($this->input->post('username') ? $this->input->post('username') : $user['username']); ?>" class="form-control" id="username" />
		</div>
	</div>
	<div class="form-group">
		<label for="email" class="col-md-4 control-label">Email</label>
		<div class="col-md-8">
			<input type="text" name="email" value="<?php echo ($this->input->post('email') ? $this->input->post('email') : $user['email']); ?>" class="form-control" id="email" />
		</div>
	</div>
	<div class="form-group">
		<label for="passwd" class="col-md-4 control-label">Passwd</label>
		<div class="col-md-8">
			<input type="text" name="passwd" value="<?php echo ($this->input->post('passwd') ? $this->input->post('passwd') : $user['passwd']); ?>" class="form-control" id="passwd" />
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-8">
			<button type="submit" class="btn btn-success">Save</button>
        </div>
	</div>
	
<?php echo form_close(); ?>