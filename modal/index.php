<?php 
if( !defined('WHIZZ_PLUGIN_URL') )
{
	echo 'Direct call denied.';
	exit;
}

add_action('admin_menu', 'admin_menu_link');
add_action('wp_print_styles', 'login_css');
add_action('wp_print_scripts', 'login_js');
add_filter('loginout', 'login_loginout');
add_action('wp_footer', 'login_footernew');

function admin_menu_link() 
{
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'filter_plugin_actions', 10, 2 );
}

/**
* @desc Adds the Settings link to the plugin activate/deactivate page
* @return string
*/
function filter_plugin_actions($links, $file) 
{
	$link_url = esc_url("options-general.php?page=". basename(__FILE__));
	$settings_link = '<a href="'.$link_url.'">'. __('Settings', 'whizz') . '</a>';
	array_unshift($links, $settings_link); // before other links
	return $links;
}
/**
* @desc Enqueue's the CSS for the specified theme.
*/
function login_css() {
	$link_url = esc_url(plugin_dir_url( __FILE__ ) . "css/loginmodal.css");
	wp_enqueue_style('login-modal', $link_url, false);
}
/**
* @desc Responsible for loading the necessary scripts and localizing JavaScript messages
*/
function login_js() {
	wp_enqueue_script('login-modal', esc_url(plugin_dir_url( __FILE__ ) . 'js/loginmodal.js'), array('jquery'), null);	
		$main_js_obj_props = array(
						'admin_ajax_url' => esc_url(admin_url().'admin-ajax.php'),
						);
	wp_localize_script( 'login-modal', 'login_modal_ajax_url', $main_js_obj_props );
	

}
/**
* @desc loginout filter that adds the WhizzLogin-Modal class to the "Log In" link
* @return string
*/
function login_loginout($link) {
	if (!is_user_logged_in()) {
		$link = str_replace('href=', 'class="login-modal" href=', esc_url($link));
	}
	return $link;
}
/**
* @desc Builds the login, registration, and password reset form HTML.
* Calls filters for each form, then echo's the output.
*/
function login_footernew() 
{
	$output = '<div id="WhizzLogin-Modal-overlay-osx" class="simplemodal-overlay"></div><div id="WhizzLogin-Modal-container-osx" class="simplemodal-container" ><div class="close simplemodal-close" id="model_login_close"><div class="simplemodal-close">x</div></div><div tabindex="-1" class="simplemodal-wrap"><div id="WhizzLogin-Modal-form">';
	$output .='<div id="WhizzLogin-Modal_login_form_main_div">';
	$login_form = login_form();
	$output .= apply_filters('simplemodal_login_form', $login_form);
	$output .='</div>';
	$login_modal_registration = get_option( 'login_modal_registration');
	$login_modal_reset_password = get_option( 'login_modal_reset_password');
	if (isset($login_modal_registration) && $login_modal_registration=="on") {
		$output .='<div id="register_form_main_div"><div class="close reg_simplemodal-close"><div class="reg_simplemodal-close">x</div></div>';
		$registration_form = registration_form();
		$output .= apply_filters('simplemodal_registration_form', $registration_form);
		$output .='</div>';
	}
	if (isset($login_modal_reset_password) && $login_modal_reset_password=="on") 
	{
		$output .='<div id="reset_form_main_div">';
		$reset_form = reset_form();
		$output .= apply_filters('simplemodal_login_reset_form', $reset_form);
		$output .='</div>';
		$output .='<div id="reset_confirm_form_main_div">';
		$output	.='<div class="title WhizzLogin-title">'.__('New Password', 'whizz').'</div>
		<div class="WhizzLogin-Modal-fields">
		<div id="reset_confirm_error" style="display:none;"></div>
		<p>
			<label>'.__('New Password', 'whizz').'<br />
			<input type="password" name="WhizzLogin_Modal_reset_newpassword" class="user_login input" value="" size="20" tabindex="10" id="WhizzLogin_Modal_reset_newpassword" min="6"/></label>
		</p>
		<p>
		<label>'.__('Confirm New Password', 'whizz').'<br />
			<input type="password" name="WhizzLogin_Modal_reset_confirm_newpassword" id="WhizzLogin_Modal_reset_confirm_newpassword" class="user_email input" value="" size="25" tabindex="20" min="6"/></label>
		</p>';
		$output	.='<p class="submit">
			<input type="hidden" id="reset_confirm_password_userid" name="reset_confirm_password_userid" />
			<input type="submit" name="wp_submit_WhizzLogin_Modal_reset_confirm_form" id="wp_submit_WhizzLogin_Modal_reset_confirm_form" value="'.__('Update', 'whizz').'" tabindex="100" />
			<input type="button" class="simplemodal-close" value="'.__('Cancel', 'whizz').'" tabindex="101" />
			<input type="hidden" name="whizz-ajax-nonce" id="whizz-ajax-nonce" value="' . wp_create_nonce( 'whizz-ajax-nonce' ) . '" />
		</p>
		<p class="nav">
			<a class="WhizzLogin_Modal_login_form" href="#">'.__('Login', 'whizz').'</a></p>';
		$output .='</div>';
	}
	$output .= '</div></div></div></div>';
	echo $output;
}
/**
* @desc Builds the login form HTML.
* If using the simplemodal_login_form filter, copy and modify this code
* into your function.
* @return string
*/
function login_form() {
	$wp_user_login_redirect_afterlogin = get_option( 'wp_user_login_redirect_afterlogin');
	if($wp_user_login_redirect_afterlogin == "")
	{
		$redirect_page = home_url()."/wp-admin";
	}
	else
	{
		if($wp_user_login_redirect_afterlogin=="-1")
		{
			$redirect_page= home_url()."/wp-admin";
		}
		else
		{
			$redirect_page= get_permalink($wp_user_login_redirect_afterlogin);
		}
	}
	$output = sprintf('
	<div class="WhizzLogin-title">'.__('Login', 'whizz').'</div>
	<div class="WhizzLogin-Modal-fields">
	<div id="login_error" style="display:none;"></div>
	<p>
		<label>'.__('Username', 'whizz').'<br />
		<input type="text" name="username" id="dv_username" class="user_login input" value="" size="20" tabindex="10" /></label>
	</p>
	<p>
		<label>'.__('Password', 'whizz').'<br />
		<input type="password" name="password" id="dv_password" class="user_pass input" value="" size="20" tabindex="20" /></label>
	</p>',
		esc_url(site_url('wp-login.php', 'login_post')),
			__('Login', 'whizz'),
			__('Username', 'whizz'),
			__('Password', 'whizz')
		);
	ob_start();
	do_action('login_form');
	$output .= ob_get_clean();
	
	
	$project_redirect = '';
	$output .= sprintf('
	<p class="forgetmenot"><label>'.$project_redirect.'<input name="rememberme" type="checkbox" id="rememberme" class="rememberme" value="forever" tabindex="90" />'.__('Remember Me', 'whizz').'</label></p>
	<p class="submit">
		<input type="button" name="dv-login-modal-wp-submit" id="dv-login-modal-wp-submit" value="'.__('Login', 'whizz').'" tabindex="100" />
		<input type="hidden" name="whizz-ajax-nonce" id="whizz-ajax-nonce" value="' . wp_create_nonce( 'whizz-ajax-nonce' ) . '" />
		<input type="button" class="simplemodal-reset" value="'.__('Cancel', 'whizz').'" tabindex="101" />
		<input type="hidden" name="testcookie" value="1" />
		<input type="hidden" name="login-modal-redirect-url" id="login-modal-redirect-url" value="'.$redirect_page.'" />
	</p>
	<p class="nav">',
		__('Remember Me', 'whizz'),
		__('Login', 'whizz'),
		__('Cancel', 'whizz')
	);
	$login_modal_registration = get_option( 'login_modal_registration');
	$login_modal_reset_password = get_option( 'login_modal_reset_password');
	if (isset($login_modal_registration) && $login_modal_registration=="on") {
		$output .= sprintf('<a class="simplemodal-register" href="%s">%s</a>',
		esc_url(site_url('wp-login.php?action=register', 'login')),
			__('Register', 'whizz')
		);
	}
	if (isset($login_modal_registration) && $login_modal_registration=="on" && isset($login_modal_reset_password) && $login_modal_reset_password=="on") {
		$output .= ' | ';
	}
	if (isset($login_modal_reset_password) && $login_modal_reset_password=="on") {
		$output .= sprintf('<a class="simplemodal-forgotpw" href="%s" title="%s">%s</a>',
		esc_url(site_url('wp-login.php?action=lostpassword', 'login')),
			__('Password Lost and Found', 'whizz'),
			__('Lost your password?', 'whizz')
		);
	}
	$output .= '
	</p>
	</div>
	<div class="WhizzLogin-Modal-activity" style="display:none;"></div>';
	return $output;
}
/* @desc Builds the reset password form HTML.
* If using the simplemodal_reset_form filter, copy and modify this code
* into your function.
* @return string 
*/
function reset_form() {
	$output = sprintf('
	<div class="WhizzLogin-title">'.__('Reset Password', 'whizz').'</div>
	<div class="WhizzLogin-Modal-fields">
	<div id="reset_error" style="display:none;"></div>
	<p>
	<label>'.__('E-mail', 'whizz').':<br />
	<input type="text" name="wp_submit_WhizzLogin_Modal_reset_text" id="wp_submit_WhizzLogin_Modal_reset_text" class="user_login input" value="" size="20" tabindex="10" /></label>
	</p>',
		site_url('wp-login.php?action=lostpassword', 'login_post'),
		__('Reset Password', 'whizz'),
		__('Username or E-mail:', 'whizz')
	);
	ob_start();
	do_action('lostpassword_form');
	$output .= ob_get_clean();
	$output .= sprintf('
		<p class="submit">
		<input type="button" name="wp_submit_WhizzLogin_Modal_reset_form" id="wp_submit_WhizzLogin_Modal_reset_form" value="'.__('Get Password', 'whizz').'" tabindex="100" />
		<input type="button" class="simplemodal-reset" value="'.__('Cancel', 'whizz').'" tabindex="101" />
		<input type="hidden" name="whizz-ajax-nonce" id="whizz-ajax-nonce" value="' . wp_create_nonce( 'whizz-ajax-nonce' ) . '" />
		</p>
		<p class="nav">
		<a class="WhizzLogin_Modal_login_form" href="#">'.__('Login', 'whizz').'</a>'
	);
	$login_modal_registration = get_option( 'login_modal_registration');	
	if (isset($login_modal_registration) && $login_modal_registration=="on") 
	{
		$output .= ' | ';
		$output .= sprintf('<a class="simplemodal-register" href="%s">%s</a>', esc_url(site_url('wp-login.php?action=register', 'login')), __('Register', 'whizz'));
	}
	$output .= '
	</p>
	</div>
	<div class="WhizzLogin-Modal-activity" style="display:none;"></div>';
	return $output;
}
/**
* @desc Builds the registration form HTML.
* If using the simplemodal_registration_form filter, copy and modify this code
* into your function.
* @return string
*/
function registration_form() {
	$output = '
	<div class="WhizzLogin-title">'.__('Register', 'whizz').'</div>
	<div class="WhizzLogin-Modal-fields">
	<div id="register_error" style="display:none;"></div>
	<p>
		<label>'.__('Username', 'whizz').'<br />
		<input type="text" name="WhizzLogin_Modal_register_username" class="user_login input" value="" size="20" tabindex="10" id="WhizzLogin_Modal_register_username" /></label>
	</p>';
		$output.='<p><label>'.__('E-mail', 'whizz').'<br /><input type="text" name="WhizzLogin_Modal_register_useremail" id="WhizzLogin_Modal_register_useremail" class="user_email input" value="" size="25" tabindex="20" /></label></p>
	<p>';
		$output.='<label>'.__('First Name', 'whizz').'<br />
		<input type="text" name="WhizzLogin_Modal_register_userfirstname" id="WhizzLogin_Modal_register_userfirstname" class="user_first_name input" value="" size="25" tabindex="20" /></label>
	</p><p>
		<label>'.__('Last Name', 'whizz').'<br />
		<input type="text" name="WhizzLogin_Modal_register_userlastname" id="WhizzLogin_Modal_register_userlastname" class="user_last_name input" value="" size="25" tabindex="20" /></label>
	</p>';
	$output.='<p class="reg_passmail">'.__('A password will be e-mailed to you.', 'whizz').'</p>';
	$output.='<p class="submit"><input type="submit" name="wp_submit_WhizzLogin_Modal_register_form" id="wp_submit_WhizzLogin_Modal_register_form" value="'.__('Register', 'whizz').'" tabindex="100" /><input type="hidden" name="whizz-ajax-nonce" id="whizz-ajax-nonce" value="' . wp_create_nonce( 'whizz-ajax-nonce' ) . '" /><input type="button" class="simplemodal-reset" value="'.__('Cancel', 'whizz').'" tabindex="101" /></p><p class="nav"><a class="WhizzLogin_Modal_login_form" href="#">'.__('Login', 'whizz').'</a>';
	$login_modal_reset_password = get_option( 'login_modal_reset_password');
	if (isset($login_modal_reset_password) && $login_modal_reset_password=="on") 
	{
		$output .= ' | ';
		$output .= sprintf('<a class="simplemodal-forgotpw" href="%s" title="%s">%s</a>',
		site_url('wp-login.php?action=lostpassword', 'login'),	__('Password Lost and Found', 'whizz'),	__('Lost your password?', 'whizz'));
	}
	$output .= '
	</p>
	</div>
	<div class="WhizzLogin-Modal-activity" style="display:none;"></div></form>';
	return $output;
}
/**
* @desc user login with email and password.
*/
add_action( 'wp_ajax_dv_login_modal_action', 'dv_my_action_callback' );
add_action( 'wp_ajax_nopriv_dv_login_modal_action', 'dv_my_action_callback' );
function dv_my_action_callback() 
{
	if( check_ajax_referer( 'whizz-ajax-nonce', 'security', false ) )
	{
		if(isset($_POST['action']) && sanitize_text_field($_POST['action']) == "dv_login_modal_action")
		{
			global $wpdb; // this is how you get access to the database
			$email = sanitize_text_field($_POST["user"]);
			$password = sanitize_text_field($_POST["password"]);
			$error = array('is_error' => true, 'msg' => __('Error Occurred', 'whizz'));
			/* check username blank */
			if( empty($email )) 
			{
				$error['is_error'] = true;
				$error['msg'] = __("<strong>ERROR</strong>: The username field is empty.",'whizz');
				wp_send_json_error($error);
			}
			/* check email blank */
			if(empty($password) ) 
			{
				$error['is_error'] = true;
				$error['msg'] = __("<strong>ERROR</strong>: The password field is empty.",'whizz');
				wp_send_json_error($error);
			}
			$usercheck = "";
			/* get user by email */
			$user_obj = get_user_by('email', sanitize_email($email));
			if($user_obj) 
			{
				$usercheck = "checkemail";
			}
			if($usercheck == "")
			{
				/* get user by username */
				$user_obj = get_user_by('login', sanitize_user($email));
				if($user_obj) 
				{
					$usercheck="checkusername";
				}
			}
			if($usercheck == "checkusername" || $usercheck == "checkemail")
			{
				if($usercheck=="checkemail")
				{
					/* check valid email */
					if( !is_email($email) ) 
					{
						$error['is_error'] = true;
						$error['msg'] = __("<strong>ERROR</strong>: The email doesn't seem to be a valid email. Please enter a valid registered email.", 'whizz');
						wp_send_json_error($error);
					}
					/* check email existence */
					if( !email_exists($email) ) {
						$error['is_error'] = true;
						$error['msg'] = __("<strong>ERROR</strong>: The email is not registered.", 'whizz');
						wp_send_json_error($error);
					}
				}
				if ( !( $user_obj && wp_check_password( $password, $user_obj->data->user_pass, $user_obj->ID) ) ) {
					$error['is_error'] = true;
					$error['msg'] = __("<strong>ERROR</strong>: The email and password doesn't match. Please try again.", 'whizz');
					wp_send_json_error($error);
				}
				/* all is will, now log him in */
				wp_set_current_user( $user_obj->ID, $user_obj->user_login );
				wp_set_auth_cookie( $user_obj->ID );
				do_action( 'wp_login', $user_obj->user_login );  
				wp_send_json_success();
			}
			else
			{
				$error['is_error'] = true;
				$error['msg'] = __("<strong>ERROR</strong>: User not found. Please Register yourself first.", 'whizz');
				wp_send_json_error($error);
			}
		}
	}
	else
	{
		$error['is_error'] = true;
		$error['msg'] = __("<strong>ERROR</strong>: Something went wrong. Please try again.", 'whizz');
		wp_send_json_error($error);
	}
}
/**
* @desc user registration with usename and email address.
*/
add_action( 'wp_ajax_dv_login_modal_register_action', 'dv_my_action_register_callback' );
add_action( 'wp_ajax_nopriv_dv_login_modal_register_action', 'dv_my_action_register_callback' );
function dv_my_action_register_callback() {
	if( check_ajax_referer( 'whizz-ajax-nonce', 'security', false ) )
	{
		if(isset($_POST['action']) && sanitize_text_field($_POST['action']) == "dv_login_modal_register_action")
		{
			global $wpdb; // this is how you get access to the database
			$email = '';
			$user = '';
			$firstname = '';
			$lastname = '';
	
			$error = array('is_error' => true, 'msg' => __('Error Occurred', 'whizz'));
			 /* check first name blank */
			if(empty($_POST["firstname"])) 
			{
				$error['is_error'] = true;
				$error['msg'] = __("<strong>ERROR</strong>: The First Name field is empty.", 'whizz');
				wp_send_json_error($error);
			}
			else
			{
				$firstname = sanitize_text_field($_POST["firstname"]);
			}
			if( empty($_POST["lastname"])) 
			{
				$error['is_error'] = true;
				$error['msg'] = __("<strong>ERROR</strong>: The Last Name field is empty.", 'whizz');
				wp_send_json_error($error);
			}
			else
			{
				$lastname = sanitize_text_field($_POST["lastname"]);
			}
			if( empty($_POST["user"])) 
			{
				$error['is_error'] = true;
				$error['msg'] = __("<strong>ERROR</strong>: The username field is empty.", 'whizz');
				wp_send_json_error($error);
			}
			else
			{
				$user = sanitize_user($_POST["user"]);
			}
			/* check email blank */
			if(empty($_POST["email"])) 
			{
				$error['is_error'] = true;
				$error['msg'] = __("<strong>ERROR</strong>: The email field is empty.", 'whizz');
				wp_send_json_error($error);
			}
			else
			{
				$email = sanitize_email($_POST["email"]);
			}
			/* check username existence */
			if( username_exists( $user ) ) 
			{
				$error['is_error'] = true;
				$error['msg'] = __("<strong>ERROR</strong>: The username is already registered.", 'whizz');
				wp_send_json_error($error);
			}
			/* check valid email */
			if( !is_email($email) ) 
			{
				$error['is_error'] = true;
				$error['msg'] = __("<strong>ERROR</strong>: The email doesn't seem to be a valid email. Please enter a valid registered email.", 'whizz');
				wp_send_json_error($error);
			}
			/* check email existence */
			if( email_exists($email) )
			{
				$error['is_error'] = true;
				$error['msg'] = __("<strong>ERROR</strong>: The email is already registered.", 'whizz');
				wp_send_json_error($error);
			}
			$user_pass=wp_generate_password();
			
			$login_modal_redirect = get_option('login_modal_redirect');
			$permalink = get_permalink( $login_modal_redirect);
					
			//$permalink = get_permalink( $login_modal_redirect);
			/* $permalink = site_url(); */
			$key = wp_rand(0,9999);
			$resetlink = $permalink."?key=".$key."&email=".$email;
			$errors =wp_create_user( $user, $user_pass, $email );
			$user_ii = get_userdatabylogin($user);
            $current_user_id= $user_ii->ID;
			update_user_meta( $current_user_id, "user_lostpass_key".$current_user_id, $resetlink);
			if( !is_wp_error($errors))
			{
			
			
//$mailmsg = "Registered successfully! <br> Your username: ".$user."<br/>To set your password, visit the following address: ".network_site_url('wp-login.php?action=rp&key='.$key.'&login=' . rawurlencode($user), 'login')."<br/>";
$mailmsg = "<br> Username: ".$user."<br/>To set your password, visit the following address: ".$resetlink."<br/>";
		
				$from_email = get_option( 'admin_email' );
				$headers .= 'From: '.site_url().'<'.$from_email.'>' . "\r\n";
				$headers .= "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				mail($email, "Your username and password info", $mailmsg, $headers);
				wp_send_json_success();
			}
			else
			{
				$error['is_error'] = true;
				$error['msg'] = __("<strong>ERROR</strong>: Errors in inserting please try again!", 'whizz');
				wp_send_json_error($error);
			}
		}
	}
	else
	{
		$error['is_error'] = true;
		$error['msg'] = __("<strong>ERROR</strong>: Something went wrong. Please try again.", 'whizz');
		wp_send_json_error($error);
	}
}
/**
* @desc send wp-user rest password link on email address.
*/
add_action( 'wp_ajax_dv_login_modal_reset_action', 'dv_my_action_reset_callback' );
add_action( 'wp_ajax_nopriv_dv_login_modal_reset_action', 'dv_my_action_reset_callback' );
function dv_my_action_reset_callback()
{
	if( check_ajax_referer( 'whizz-ajax-nonce', 'security', false ) )
	{
		if(isset($_POST['action']) && sanitize_text_field($_POST['action']) == "dv_login_modal_reset_action" && isset($_POST["user"]) && !empty($_POST["user"]))
		{
			global $wpdb; // this is how you get access to the database	 
			$user = sanitize_text_field($_POST["user"]);
			$error = array('is_error' => true, 'msg' => __('Error Occurred', 'whizz'));
			/* check username blank */
			if( empty($user )) 
			{
				$error['is_error'] = true;
				$error['msg'] = __("<strong>ERROR</strong>: The username field is empty.", 'whizz');
				wp_send_json_error($error);
			}
			$usercheck="";
			if( !is_email($user) ) 
			{
				$error['is_error'] = true;
				$error['msg'] = __("<strong>ERROR</strong>: The email doesn't seem to be a valid email. Please enter a valid registered email.", 'whizz');
				wp_send_json_error($error);
			}
			if( !email_exists($user) ) 
			{
				$error['is_error'] = true;
				$error['msg'] = __("<strong>ERROR</strong>: The email is not registered.", 'whizz');
				wp_send_json_error($error);
			}
			/* get user by email */
			$user_obj = get_user_by('email', $user);
			if($user_obj) 
			{
				$usercheck = "checkemail";
			}
			if($usercheck == "")
			{
				/* get user by username */
				$user_obj = get_user_by('login', sanitize_user($user));
				if($user_obj) 
				{
					$usercheck="checkusername";
				}
			}
			if($usercheck == "checkusername" || $usercheck == "checkemail")
			{
				$to = $user_obj->user_email;
				$login_modal_redirect = get_option('login_modal_redirect');
				$permalink = get_permalink( $login_modal_redirect);
				$key = wp_rand(0,9999);
				$current_user_id = $user_obj->ID;
				$resetlink = $permalink."?key=".$key."&email=".$to;
				if(isset($current_user_id))
				{
					update_user_meta( $current_user_id, "user_lostpass_key".$current_user_id, $resetlink);
				}
				
				$message="Someone requested that the password be reset for the following account:<br/><br/>
			".home_url()."<br/><br/>Username: ".$to."<br/><br/>If this was a mistake, just ignore this email and nothing will happen.<br/><br/>To reset your password, visit the following address: <br>".$resetlink;
				add_filter( 'wp_mail_content_type', 'set_html_content_type' );
				wp_mail( $to, 'Password Reset', $message);
				remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
				wp_send_json_success();
			}
			else
			{
				$error['is_error'] = true;
				$error['msg'] = __("<strong>ERROR</strong>: User not found. Please register yourself first.", 'whizz');
				wp_send_json_error($error);
			}
		}
	}
	else
	{
		$error['is_error'] = true;
		$error['msg'] = __("<strong>ERROR</strong>: Something went wrong. Please try again.", 'whizz');
		wp_send_json_error($error);
	}	
}


function set_html_content_type() {
	return 'text/html';
}
add_action( 'wp_ajax_dv_login_modal_reset_confirm_action', 'dv_my_action_reset_confirm_callback' );
add_action( 'wp_ajax_nopriv_dv_login_modal_reset_confirm_action', 'dv_my_action_reset_confirm_callback' );
function dv_my_action_reset_confirm_callback()
{
	if( check_ajax_referer( 'whizz-ajax-nonce', 'security', false ) )
	{
		if(isset($_POST['action']) && sanitize_text_field($_POST['action']) == "dv_login_modal_reset_confirm_action")
		{
			global $wpdb; // this is how you get access to the database	 
			$user = $_POST["user"];
			$error = array('is_error' => true, 'msg' => __('Error Occurred', 'whizz'));
			/* check password blank */
			if(isset($_POST["pass1"]) && ! empty($_POST["pass1"] )) {
				$pass1 = sanitize_text_field($_POST["pass1"]);
			}
			else
			{
				$error['is_error'] = true;
				$error['msg'] = __("<strong>ERROR</strong>: The new password field is empty.", 'whizz');
				wp_send_json_error($error);
			}
			/* check password blank */
			if(isset($_POST["pass2"]) && ! empty($_POST["pass2"] )) {
				$pass2 = sanitize_text_field($_POST["pass2"]);
			}
			else
			{
				$error['is_error'] = true;
				$error['msg'] = __("<strong>ERROR</strong>: The confirm password field is empty.", 'whizz');
				wp_send_json_error($error);
			}
			/* check password and confrim password id equel or not */
			if( $pass1 != $pass2) {
				$error['is_error'] = true;
				$error['msg'] = __("<strong>ERROR</strong>: The confirm password doesn't match.", 'whizz');
				wp_send_json_error($error);
			}
			
			if(isset($_POST["user"]) && ! empty($_POST["user"] ))
			{
				$user = sanitize_text_field($_POST["user"]);
				$user_id = wp_update_user( array( 'ID' => $user, 'user_pass'=>$pass1 ) );
				if ( is_wp_error( $userid ) )
				{
					$error['is_error'] = true;
					$error['msg'] = __("<strong>ERROR</strong>: Error in updating Please try again!", 'whizz');
					wp_send_json_error($error);
				}
				else
				{
					wp_send_json_success();
				}
			}
			else
			{
				$error['is_error'] = true;
				$error['msg'] = __("<strong>ERROR</strong>: User is not selected.", 'whizz');
				wp_send_json_error($error);
			} 
			
			
		}
	}
	else
	{
		$error['is_error'] = true;
		$error['msg'] = __("<strong>ERROR</strong>: Something went wrong. Please try again.", 'whizz');
		wp_send_json_error($error);
	}
}


add_action('init','wp_admin_redirect_to_custome_page');
function wp_admin_redirect_to_custome_page()
{
	global $pagenow;
	$login_modal_redirect = get_option( 'login_modal_redirect');
	if(isset($login_modal_redirect) && $login_modal_redirect != "")
	{
		if( 'wp-login.php' == $pagenow )
		{
			wp_logout();
			$permalink = get_permalink( $login_modal_redirect);
			wp_safe_redirect(esc_url($permalink));
			exit();
		}
	}
}

add_action('wp_footer', 'wp_admin_redirect_to_custome_page_show_login_modal');
function wp_admin_redirect_to_custome_page_show_login_modal()
{
	$Path = get_the_ID();
	$login_modal_redirect =get_option( 'login_modal_redirect');
	
	if(isset($login_modal_redirect) && $login_modal_redirect!="")
	{
		
		if( $login_modal_redirect == $Path )
		{
			if ( !is_user_logged_in() )
			{
				if(isset($_GET["key"]) && $_GET["key"] != "" && isset($_GET["email"]) && $_GET["email"] != "")
				{
					$current_user_id = get_user_by( 'email', sanitize_email($_GET["email"]) );
					$current_user_id = $current_user_id->ID;
					$usersentlink = get_user_meta($current_user_id, 'user_lostpass_key'.$current_user_id, true);
					$login_modal_redirect = get_option( 'login_modal_redirect');
					$permalink = get_permalink( $login_modal_redirect);
					$resetlink = $permalink."?key=".sanitize_text_field($_GET["key"])."&email=". sanitize_email($_GET["email"]);
					if($resetlink==$usersentlink)
					{
					?>
                    <script type="text/javascript">
						jQuery(document).ready(function($)
						{
							$("#WhizzLogin-Modal_login_form_main_div").css("display","none");
							$("#reset_confirm_form_main_div").css("display","block");
							$("#WhizzLogin-Modal-overlay-osx").fadeIn('slow',function()
							{
								$("#WhizzLogin-Modal-container-osx").slideDown('slow', function ()
								{
									$("#reset_confirm_password_userid").val('<?php echo $current_user_id; ?>');
									});
								});
							return false;
						});
					</script>
                    <?php
					}
					else
					{
						?>
						<script type="text/javascript">
                            jQuery(document).ready(function($){
                                $("#WhizzLogin-Modal_login_form_main_div").css("display","none");
                                $("#reset_form_main_div").css("display","block");
                                $("#WhizzLogin-Modal-overlay-osx").fadeIn('slow',function(){
                                    $("#WhizzLogin-Modal-container-osx").slideDown('slow', function () {
                                        $("#reset_error").html('Please enter your username or email address. You will receive a link to create a new password via email.<br/><br/>Sorry, that key does not appear to be valid.');
                                        $("#reset_error").css("display", "block");
                                        });
                                    });
                                    return false;
                                });
                        </script>
                        <?php				
					}
				}
				else
				{
					?>
					<script type="text/javascript">
                        jQuery(document).ready(function($) {
                            <?php if(isset($_GET["i"]) && isset($_GET["i"]) && isset($_GET["i"])){ ?>
                            $("#WhizzLogin-Modal_login_form_main_div").css("display","none");
                            $("#register_form_main_div").css("display","block");
                            $("#reset_form_main_div").css("display","none");
                            $("#reset_confirm_form_main_div").css("display","none");
                            <?php } ?>
                            $("#WhizzLogin-Modal-overlay-osx").fadeIn('slow',function(){
                                $("#WhizzLogin-Modal-container-osx").slideDown('slow', function () {
                                });
                            });
                            return false;
                        });
                    </script>
					<?php
				}
			}
		}
	}
}
add_shortcode('Whizz-Login-Modal-Login', 'Whizz_Login_Modal_Login_func');
function Whizz_Login_Modal_Login_func()
{
	if ( !is_user_logged_in() )	
	{	
		?>
		<script type="text/javascript">
            jQuery(document).ready(function($)
            {
                $("#WhizzLogin-Modal-overlay-osx").fadeIn('slow',function()
                 {						
                    $("#WhizzLogin-Modal-container-osx").slideDown('slow', function (){});					
                 });
                 return false;				
            });			
        </script>
        <?php
	}
}
add_shortcode('login-modal-link', 'login_modal_link_func');
function login_modal_link_func()
{
	$login_url = home_url() . '/wp-login.php';
	if ( is_user_logged_in() )	
	{
		return '<a class="button" href="'.esc_url($login_url).'">'.__('Log Out', 'whizz').'</a>';
	}
	else
	{
		return '<a href="'.esc_url($login_url).'" class="login-modal button">'.__('Login', 'whizz').'</a>';
	}
}

add_action("wp_ajax_manage_page_redirects", "manage_page_redirects_func");
function manage_page_redirects_func()
{
	if( check_ajax_referer( 'whizz-main-ajax-nonce', 'security', false ) )
	{
		if(isset($_POST['action']) && sanitize_text_field($_POST['action']) == "manage_page_redirects" && isset($_POST['process']) && sanitize_text_field($_POST['process']) == "savedata")
		{
			if(isset($_POST['registration_a']))
			{
				$login_modal_registration = sanitize_text_field($_POST['registration_a']);	
			}
			
			if(isset($_POST['reset_a']))
			{
				$login_modal_reset_password = sanitize_text_field($_POST['reset_a']);
			}
			
			if(isset($_POST['wp_login_redirect_text_login_modal_a']))
			{
				$login_modal_redirect = sanitize_text_field($_POST['wp_login_redirect_text_login_modal_a']);
			}
			if(isset($_POST['wp_user_login_redirect_text_afterlogin_a']))
			{
				$wp_user_login_redirect_text_afterlogin = sanitize_text_field($_POST['wp_user_login_redirect_text_afterlogin_a']);
			}
			
			if(isset($_POST['login_modal_redirect_a']))
			{
				$login_modal_redirect_check = sanitize_text_field($_POST['login_modal_redirect_a']);
			}
			if(isset($_POST['whizz_login_modal_redirect_afterlogin_a']))
			{
				$whizz_login_modal_redirect_afterlogin_a = sanitize_text_field($_POST['whizz_login_modal_redirect_afterlogin_a']);
			}
			if(isset($login_modal_redirect_check) && $login_modal_redirect_check == "on")
			{
				if(!isset($login_modal_redirect) || $login_modal_redirect == "")
				{
					$error = "com";
					$errormsg = __("If you want <b>WP-Login redirect</b> to custom page, Then please select redirect to custom page", 'whizz');
				}
			}
			if(isset($login_modal_redirect_afterlogin_check) && $login_modal_redirect_afterlogin_check == "on")
			{
				if(!isset($wp_user_login_redirect_text_afterlogin) || $wp_user_login_redirect_text_afterlogin == "")
				{
					$error1= "com";
					$errormsg1 = __("If you want <b>User Redirect</b> to custom page after login, Then please select redirect to custom page", 'whizz');
				}
			}
			if(isset($error) && $error == "com" || isset($error1) && $error1 == "com")
			{
				if(isset($errormsg) && isset($errormsg1))
				{
					wp_send_json_success(array("rcode"=>"2", "message"=>'<div class="error"><p>'. $errormsg."<br/>" .$errormsg1. '</p></div>'));
				}
				else if(isset($errormsg))
				{
					wp_send_json_success(array("rcode"=>"2", "message"=>'<div class="error"><p>'. $errormsg.'</p></div>'));
				}
				else if(isset($errormsg1))
				{
					wp_send_json_success(array("rcode"=>"2", "message"=>'<div class="error"><p>'. $errormsg1.'</p></div>'));
				}
			}
			else
			{
				if(isset($login_modal_registration))
				{
					update_option( 'login_modal_registration', $login_modal_registration );
				}
				if(isset($login_modal_reset_password))
				{
					update_option( 'login_modal_reset_password', $login_modal_reset_password );
				}
				if(isset($login_modal_redirect))
				{
					update_option( 'login_modal_redirect', $login_modal_redirect );
				}
				if(isset($wp_user_login_redirect_text_afterlogin))
				{
					update_option( 'wp_user_login_redirect_afterlogin', $wp_user_login_redirect_text_afterlogin );
				}
				update_option('whizz_model_login_admin_notification','close');
				wp_send_json_success(array("rcode"=>"1", "message"=>"successful"));
			}
		}
	}
	else
	{
		$error['is_error'] = true;
		$error['msg'] = __("<strong>ERROR</strong>: Something went wrong. Please try again.", 'whizz');
		wp_send_json_error($error);
	}
}
?>