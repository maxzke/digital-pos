<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Community Auth - Examples Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2018, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Login extends MY_Controller{
	
	public function __construct(){
		parent::__construct();

		// Force SSL
		$this->force_ssl();

		// Form and URL helpers always loaded (just for convenience)
		$this->load->helper('url');
		$this->load->helper('form');
	}

	// -----------------------------------------------------------------------

	/**
	 * Demonstrate being redirected to login.
	 * If you are logged in and request this method,
	 * you'll see the message, otherwise you will be
	 * shown the login form. Once login is achieved,
	 * you will be redirected back to this method.
	 */
	public function index(){
		if( $this->require_min_level(1) ){
			redirect('pos');
		}
	}	
		
	// -----------------------------------------------------------------------

	/**
	 * Most minimal user creation. You will of course make your
	 * own interface for adding users, and you may even let users
	 * register and create their own accounts.
	 *
	 * The password used in the $user_data array needs to meet the
	 * following default strength requirements:
	 *   - Must be at least 8 characters long
	 *   - Must be at less than 72 characters long
	 *   - Must have at least one digit
	 *   - Must have at least one lower case letter
	 *   - Must have at least one upper case letter
	 *   - Must not have any space, tab, or other whitespace characters
	 *   - No backslash, apostrophe or quote chars are allowed
	 */
	public function create_user(){

		if( $this->require_min_level(9) ){
			// Load resources
			$this->load->helper('auth');
			$this->load->model('examples/examples_model');
			$this->load->model('examples/validation_callables');
			$this->load->library('form_validation');
			
			$bool = $this->is_role('admin');
			if ($bool){		
				// Customize this array for your user
				$user_data = [
					// 'username'   => 'admin3',
					// 'passwd'     => 'Admin123456',
					// 'email'      => 'ramzdave3@gmail.com',
					// 'auth_level' => '1', 
					// 9 if you want to login @ examples/index.
					'auth_level' => $this->input->post('auth_level'),
					'username' => $this->input->post('username'),
					'email' => $this->examples_model->get_unused_id(),
					'passwd' => $this->input->post('passwd'),
				];

				$this->is_logged_in();

				

				$this->form_validation->set_data( $user_data );

				$validation_rules = [
					[
						'field' => 'username',
						'label' => 'username',
						'rules' => 'max_length[12]|is_unique[' . db_table('user_table') . '.username]',
						'errors' => [
							'is_unique' => 'El nombre de Usuario ya se encuentra registrado, intente con uno diferente',
							'max_length' => 'El nombre de usuario debe tener un Maximo 12 caracteres',
						]
					],
					[
						'field' => 'passwd',
						'label' => 'passwd',
						'rules' => [
							'trim',
							'required',
							[ 
								'_check_password_strength', 
								[ $this->validation_callables, '_check_password_strength' ] 
							]
						],
						'errors' => [
							'required' => 'The password field is required.'
						]
					],
					// [
					// 	'field'  => 'email',
					// 	'label'  => 'email',
					// 	'rules'  => 'trim|valid_email|is_unique[' . db_table('user_table') . '.email]',
					// 	'errors' => [
					// 		'is_unique' => 'Email address already in use.'
					// 	]
					// ],
					[
						'field' => 'auth_level',
						'label' => 'auth_level',
						'rules' => 'required|integer|in_list[1,3,5,7,9]'
					]
				];

				$this->form_validation->set_rules( $validation_rules );

				if( $this->form_validation->run() )
				{
					$user_data['passwd']     = $this->authentication->hash_passwd($user_data['passwd']);
					$user_data['user_id']    = $this->examples_model->get_unused_id();
					$user_data['created_at'] = date('Y-m-d H:i:s');

					// If username is not used, it must be entered into the record as NULL
					if( empty( $user_data['username'] ) )
					{
						$user_data['username'] = NULL;
					}

					$this->db->set($user_data)
						->insert(db_table('user_table'));

					if( $this->db->affected_rows() == 1 ){
						$msg =$user_data['username'];
						$this->session->set_flashdata('usuario_creado', $msg);
						$params = array('usuario'=>$user_data['username']);
						$this->load->model('cortes_model');
						$this->cortes_model->create_cuenta_caja($params);
						redirect('user/index');
					}
						
				}
				else
				{
					//echo '<h1>User Creation Error(s)</h1>' . validation_errors();
					$this->session->set_flashdata('login_error', validation_errors());
					redirect('user/add');
				}

				
			}else{
				redirect('Error_404/sin_permiso');
			}//end else se requiere administrador
		}

		
	}
	
	// -----------------------------------------------------------------------

	/**
	 * This login method only serves to redirect a user to a 
	 * location once they have successfully logged in. It does
	 * not attempt to confirm that the user has permission to 
	 * be on the page they are being redirected to.
	 */
	public function login(){
		// Method should not be directly accessible
		if( $this->uri->uri_string() == 'login/login')
			show_404();

		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' )
			$this->require_min_level(1);

		$this->setup_login_form();

		$html = $this->load->view('login/index', '', TRUE);

		echo $html;
	}

	// --------------------------------------------------------------

	/**
	 * Log out
	 */
	public function logout()
	{
		$this->authentication->logout();

		// Set redirect protocol
		$redirect_protocol = USE_SSL ? 'https' : NULL;

		redirect( site_url( LOGIN_PAGE . '?' . AUTH_LOGOUT_PARAM . '=1', $redirect_protocol ) );
	}

	// --------------------------------------------------------------

	/**
	 * User recovery form
	 */
	// public function recover()
	// {
	// 	// Load resources
	// 	$this->load->model('examples/examples_model');

	// 	/// If IP or posted email is on hold, display message
	// 	if( $on_hold = $this->authentication->current_hold_status( TRUE ) )
	// 	{
	// 		$view_data['disabled'] = 1;
	// 	}
	// 	else
	// 	{
	// 		// If the form post looks good
	// 		if( $this->tokens->match && $this->input->post('email') )
	// 		{
	// 			if( $user_data = $this->examples_model->get_recovery_data( $this->input->post('email') ) )
	// 			{
	// 				// Check if user is banned
	// 				if( $user_data->banned == '1' )
	// 				{
	// 					// Log an error if banned
	// 					$this->authentication->log_error( $this->input->post('email', TRUE ) );

	// 					// Show special message for banned user
	// 					$view_data['banned'] = 1;
	// 				}
	// 				else
	// 				{
	// 					/**
	// 					 * Use the authentication libraries salt generator for a random string
	// 					 * that will be hashed and stored as the password recovery key.
	// 					 * Method is called 4 times for a 88 character string, and then
	// 					 * trimmed to 72 characters
	// 					 */
	// 					$recovery_code = substr( $this->authentication->random_salt() 
	// 						. $this->authentication->random_salt() 
	// 						. $this->authentication->random_salt() 
	// 						. $this->authentication->random_salt(), 0, 72 );

	// 					// Update user record with recovery code and time
	// 					$this->examples_model->update_user_raw_data(
	// 						$user_data->user_id,
	// 						[
	// 							'passwd_recovery_code' => $this->authentication->hash_passwd($recovery_code),
	// 							'passwd_recovery_date' => date('Y-m-d H:i:s')
	// 						]
	// 					);

	// 					// Set the link protocol
	// 					$link_protocol = USE_SSL ? 'https' : NULL;

	// 					// Set URI of link
	// 					$link_uri = 'examples/recovery_verification/' . $user_data->user_id . '/' . $recovery_code;

	// 					$view_data['special_link'] = anchor( 
	// 						site_url( $link_uri, $link_protocol ), 
	// 						site_url( $link_uri, $link_protocol ), 
	// 						'target ="_blank"' 
	// 					);

	// 					$view_data['confirmation'] = 1;
	// 				}
	// 			}

	// 			// There was no match, log an error, and display a message
	// 			else
	// 			{
	// 				// Log the error
	// 				$this->authentication->log_error( $this->input->post('email', TRUE ) );

	// 				$view_data['no_match'] = 1;
	// 			}
	// 		}
	// 	}

	// 	echo $this->load->view('examples/page_header', '', TRUE);

	// 	echo $this->load->view('examples/recover_form', ( isset( $view_data ) ) ? $view_data : '', TRUE );

	// 	echo $this->load->view('examples/page_footer', '', TRUE);
	// }

	// --------------------------------------------------------------

	/**
	 * Verification of a user by email for recovery
	 * 
	 * @param  int     the user ID
	 * @param  string  the passwd recovery code
	 */
	// public function recovery_verification( $user_id = '', $recovery_code = '' )
	// {
	// 	/// If IP is on hold, display message
	// 	if( $on_hold = $this->authentication->current_hold_status( TRUE ) )
	// 	{
	// 		$view_data['disabled'] = 1;
	// 	}
	// 	else
	// 	{
	// 		// Load resources
	// 		$this->load->model('examples/examples_model');

	// 		if( 
	// 			/**
	// 			 * Make sure that $user_id is a number and less 
	// 			 * than or equal to 10 characters long
	// 			 */
	// 			is_numeric( $user_id ) && strlen( $user_id ) <= 10 &&

	// 			/**
	// 			 * Make sure that $recovery code is exactly 72 characters long
	// 			 */
	// 			strlen( $recovery_code ) == 72 &&

	// 			/**
	// 			 * Try to get a hashed password recovery 
	// 			 * code and user salt for the user.
	// 			 */
	// 			$recovery_data = $this->examples_model->get_recovery_verification_data( $user_id ) )
	// 		{
	// 			/**
	// 			 * Check that the recovery code from the 
	// 			 * email matches the hashed recovery code.
	// 			 */
	// 			if( $recovery_data->passwd_recovery_code == $this->authentication->check_passwd( $recovery_data->passwd_recovery_code, $recovery_code ) )
	// 			{
	// 				$view_data['user_id']       = $user_id;
	// 				$view_data['username']     = $recovery_data->username;
	// 				$view_data['recovery_code'] = $recovery_data->passwd_recovery_code;
	// 			}

	// 			// Link is bad so show message
	// 			else
	// 			{
	// 				$view_data['recovery_error'] = 1;

	// 				// Log an error
	// 				$this->authentication->log_error('');
	// 			}
	// 		}

	// 		// Link is bad so show message
	// 		else
	// 		{
	// 			$view_data['recovery_error'] = 1;

	// 			// Log an error
	// 			$this->authentication->log_error('');
	// 		}

	// 		/**
	// 		 * If form submission is attempting to change password 
	// 		 */
	// 		if( $this->tokens->match )
	// 		{
	// 			$this->examples_model->recovery_password_change();
	// 		}
	// 	}

	// 	echo $this->load->view('examples/page_header', '', TRUE);

	// 	echo $this->load->view( 'examples/choose_password_form', $view_data, TRUE );

	// 	echo $this->load->view('examples/page_footer', '', TRUE);
	// }

	// --------------------------------------------------------------

	/**
	 * Attempt to login via AJAX
	 */
	// public function ajax_login()
	// {
	// 	$this->is_logged_in();

	// 	$this->tokens->name = config_item('login_token_name');

	// 	$data['javascripts'] = [
	// 		'https://code.jquery.com/jquery-1.12.0.min.js'
	// 	];

	// 	if( $this->authentication->on_hold === TRUE )
	// 	{
	// 		$data['on_hold_message'] = 1;
	// 	}

	// 	// This check for on hold is for normal login attempts
	// 	else if( $on_hold = $this->authentication->current_hold_status() )
	// 	{
	// 		$data['on_hold_message'] = 1;
	// 	}

	// 	$link_protocol = USE_SSL ? 'https' : NULL;

	// 	$data['final_head'] = "<script>
	// 		$(document).ready(function(){
	// 			$(document).on( 'submit', 'form', function(e){
	// 				$.ajax({
	// 					type: 'post',
	// 					cache: false,
	// 					url: '" . site_url('examples/ajax_attempt_login', $link_protocol ) . "',
	// 					data: {
	// 						'login_string': $('#login_string').val(),
	// 						'login_pass': $('#login_pass').val(),
	// 						'" . config_item('login_token_name') . "': $('[name=\"" . config_item('login_token_name') . "\"]').val()
	// 					},
	// 					dataType: 'json',
	// 					success: function(response){
	// 						$('[name=\"" . config_item('login_token_name') . "\"]').val( response.token );
	// 						console.log(response);
	// 						if(response.status == 1){
	// 							$('form').replaceWith('<p>You are now logged in.</p>');
	// 							$('#login-link').attr('href','" . site_url('examples/logout', $link_protocol ) . "').text('Logout');
	// 							$('#ajax-login-link').parent().hide();
	// 						}else if(response.status == 0 && response.on_hold){
	// 							$('form').hide();
	// 							$('#on-hold-message').show();
	// 							alert('You have exceeded the maximum number of login attempts.');
	// 						}else if(response.status == 0 && response.count){
	// 							alert('Failed login attempt ' + response.count + ' of ' + $('#max_allowed_attempts').val());
	// 						}
	// 					}
	// 				});
	// 				return false;
	// 			});
	// 		});
	// 	</script>";

	// 	$html = $this->load->view('examples/page_header', $data, TRUE);
	// 	$html .= $this->load->view('examples/ajax_login_form', $data, TRUE);
	// 	$html .= $this->load->view('examples/page_footer', '', TRUE);

	// 	echo $html;
	// }

	// --------------------------------------------------------------

	/**
	 * Test for login via ajax
	 */
	// public function ajax_attempt_login()
	// {
	// 	if( $this->input->is_ajax_request() )
	// 	{
	// 		// Allow this page to be an accepted login page
	// 		$this->config->set_item('allowed_pages_for_login', ['examples/ajax_attempt_login'] );

	// 		// Make sure we aren't redirecting after a successful login
	// 		$this->authentication->redirect_after_login = FALSE;

	// 		// Do the login attempt
	// 		$this->auth_data = $this->authentication->user_status( 0 );

	// 		// Set user variables if successful login
	// 		if( $this->auth_data )
	// 			$this->_set_user_variables();

	// 		// Call the post auth hook
	// 		$this->post_auth_hook();

	// 		// Login attempt was successful
	// 		if( $this->auth_data )
	// 		{
	// 			echo json_encode([
	// 				'status'   => 1,
	// 				'user_id'  => $this->auth_user_id,
	// 				'username' => $this->auth_username,
	// 				'level'    => $this->auth_level,
	// 				'role'     => $this->auth_role,
	// 				'email'    => $this->auth_email
	// 			]);
	// 		}

	// 		// Login attempt not successful
	// 		else
	// 		{
	// 			$this->tokens->name = config_item('login_token_name');

	// 			$on_hold = ( 
	// 				$this->authentication->on_hold === TRUE OR 
	// 				$this->authentication->current_hold_status()
	// 			)
	// 			? 1 : 0;

	// 			echo json_encode([
	// 				'status'  => 0,
	// 				'count'   => $this->authentication->login_errors_count,
	// 				'on_hold' => $on_hold, 
	// 				'token'   => $this->tokens->token()
	// 			]);
	// 		}
	// 	}

	// 	// Show 404 if not AJAX
	// 	else
	// 	{
	// 		show_404();
	// 	}
	// }
	
	// -----------------------------------------------------------------------

	/**
	 * If you are using some other way to authenticate a created user, 
	 * such as Facebook, Twitter, etc., you will simply call the user's 
	 * record from the database, and pass it to the maintain_state method.
	 *
	 * So, you must know either the user's username or email address to 
	 * log them in.
	 *
	 * How you would safely implement this in your application is your choice.
	 * Please keep in mind that such functionality bypasses all of the 
	 * checks that Community Auth does during a normal login.
	 */
	// public function social_login()
	// {
	// 	// Add the username or email address of the user you want logged in:
	// 	$username_or_email_address = $this->authentication->hash_passwd('Admin123456');

	// 	if( ! empty( $username_or_email_address ) )
	// 	{
	// 		$auth_model = $this->authentication->auth_model;

	// 		// Get normal authentication data using username or email address
	// 		if( $auth_data = $this->{$auth_model}->get_auth_data( $username_or_email_address ) )
	// 		{
	// 			/**
	// 			 * If redirect param exists, user redirected there.
	// 			 * This is entirely optional, and can be removed if 
	// 			 * no redirect is desired.
	// 			 */
	// 			$this->authentication->redirect_after_login();

	// 			// Set auth related session / cookies
	// 			$this->authentication->maintain_state( $auth_data );
	// 		}
	// 	}
	// 	else
	// 	{
	// 		echo 'Example requires that you set a username or email address.';
	// 	}
	// }
	
	// -----------------------------------------------------------------------
}

/* End of file Examples.php */
/* Location: /community_auth/controllers/Examples.php */
