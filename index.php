<?php
/*
 * Plugin Name: WHIZZ
 * Plugin URI: https://whizz.us.com
 * Description: WHIZZ helps you quickly organize, manage and add a color-coded interface to your WordPress Admin. Why accept bland? WHIZZ for WordPress is guaranteed to brighten up your day, and your WP-Admin! 
 * Version: 1.1.8
 * Author: Browserweb Inc
 * Author URI: https://whizz.us.com
 * Text Domain: whizz
 * Domain Path: /languages
*/
define( 'WHIZZ_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

/* Internationalization start */
add_action('plugins_loaded', 'whizz_load_language_translation_func');
function whizz_load_language_translation_func() 
{
	$plugin_rel_path = dirname( plugin_basename( __FILE__ ) ) .'/languages';
	load_plugin_textdomain('whizz', false, $plugin_rel_path );	
}
/* Internationalization end */

require_once('modal/index.php');
//wp_enqueue_style( 'my-style', plugins_url( '/css/common.css', __FILE__ ), false, '1.0' );
add_action('admin_menu','whizz_add_menu_colorize_menu_func');
function whizz_add_menu_colorize_menu_func()
{
	add_menu_page( __('WHIZZ','whizz'), __('WHIZZ','whizz'), 'manage_options', 'whizz-plugin', 'whizz_plugin_colorize_func',  plugin_dir_url( __FILE__ )."images/logo_s.png" );
	add_submenu_page( 'whizz-plugin', __('WHIZZ Features','whizz'), __('WHIZZ Features','whizz'), 'manage_options', 'whizz-plugin', 'whizz_plugin_colorize_func');	
	add_submenu_page( 'whizz-plugin', __('Colorize','whizz'), __('Colorize','whizz'), 'manage_options', 'colorize_menus', 'whizz_colorize_menus_func');	
	add_submenu_page( 'whizz-plugin', __('Menus','whizz'), __('Menus','whizz'), 'manage_options', 'whizz_menus', 'whizz_menus_func');
	add_submenu_page( 'whizz-plugin', __('Modal','whizz'), __('Modal','whizz'), 'manage_options', 'whizz_modal', 'admin_options_page_login');
	add_submenu_page( 'whizz-plugin', __('Plugins','whizz'), __('Plugins','whizz'), 'manage_options', 'plugin-list', 'whizz_plugin_modification_func');
	add_submenu_page( 'whizz-plugin', __('Users','whizz'), __('Users','whizz'), 'manage_options', 'users-list', 'whizz_user_modification_func');
	add_submenu_page( 'whizz-plugin', __('WHIZZ Support','whizz'), __('WHIZZ Support','whizz'), 'manage_options', 'whizz-support', 'whizz_support_f_func');
	
	$user_menu_choice = get_option(get_current_user_id().'user_whizz_plugin_choice');
	if(isset($user_menu_choice) && !empty($user_menu_choice))
	{
		include_once('colorize/index_bar_menu.php');
	}
	else
	{
		include_once('colorize/index_bar_menu.php');
	}
}
function whizz_plugin_colorize_func()
{
	include_once(plugin_dir_path(__FILE__)."center-body.php");
}
function whizz_support_f_func()
{
	include_once(plugin_dir_path(__FILE__)."center-content-support.php");
}
function whizz_menus_func()
{ 
	if(current_user_can('manage_options')) {
		?> 
		<?php echo wp_enqueue_style( 'my-style', plugins_url( '/css/style-admin.css', __FILE__ ), false, '1.0' ); ?>
		<?php echo wp_enqueue_style( 'my-style', plugins_url( '/css/support-style.css', __FILE__ ), false, '1.0' ); ?>
		<div class="page-heading" style="width:98%;margin-bottom:15px;">
			<h1 class="main-plugin-title-menu">
				<?php _e( "WHIZZ Menus", 'whizz' ); ?>
			</h1>
		</div>
		<div class="wpbody-content-box sep">
		  <div class="contaner-second-block-new inner">
			<h1 style="text-align:center;"><?php _e( "Select & Drag Separator", 'whizz' ); ?></h1>
			<ul id="seperator_to_select">
			  <li class="seperator_to_select_li"></li>
			  <div class="clear"></div>
			</ul>
			<?php wp_nonce_field( 'drag_seperator_menu_value', 'nonce_drag_seperator' ); ?>  
			<br />
			<div id="reset_separators" class="reset_separators_btn"><?php _e( "Reset Separators", 'whizz' ); ?></div>
		  </div>
		  <div class="clear"></div>
		</div>
		<?php
	}
}
function whizz_colorize_menus_func()
{
	if(current_user_can('manage_options')) {
		ob_start();
		?>
		<h1 class="whizz-h1"><?php _e( "WHIZZ Colorize", 'whizz' ); ?></h1>
		<div class="wpbody-content-box">
		<div class="whizz-left-align">
		<strong class="w-font"><?php _e( "Color Palette to change menus color", 'whizz' ); ?></strong>
		<br />
		<div class="choice_radio">
			<input type="radio" name="radio_choice_color" id="choice_background_color" />
			<label for="choice_background_color"><?php _e( "Background color for menus", 'whizz' ); ?></label> &nbsp; &nbsp; 
			<input type="radio" name="radio_choice_color" id="choice_hover_color" />
			<label for="choice_hover_color"><?php _e( "Hover color for menus", 'whizz' ); ?></label>
		</div>
		<div style='width:100%; display:none;' id='bgcolor_browserw'><strong><?php _e( "Background color for menus", 'whizz' ); ?> :</strong>
		  <input type='text' id='colorpallete' name='colorpallete'>
		</div>
			<?php wp_nonce_field( 'save_colorize_menu_value', 'nonce_colorize' ); ?>
		<br />
		<div style='width:100%; display:none;' id='hovercolor_browserw'><strong><?php _e( "Hover color for menus", 'whizz' ); ?> :</strong>
		  <input type='text' id='colorpallete_hover' name='colorpallete_hover'>
		</div>
		<?php wp_nonce_field( 'save_colorize_menu_hover_value', 'nonce_colorize_hover' ); ?>     
		<br />
		<div id='reset_all' class='reset-button'>
			<strong><?php _e( "Reset Bucket", 'whizz' ); ?></strong>
		</div>
		 <div id='reset_menus_color' class='reset-button'>
			<strong><?php _e( "Reset Menus Color", 'whizz' ); ?></strong>
		</div> 
		<?php wp_nonce_field( 'reset_colorize_menu', 'nonce_reset_colorize' ); ?>    
		<div style="clear:both;"></div>
		<div id='DivToShow'></div>
		</div></div>
		<?php
		$content = ob_get_clean();
		echo $content;
	}
}
function whizz_plugin_modification_func()
{
	include_once('plugins/plugin-list.php');
}
function whizz_user_modification_func()
{	
	include_once('users/user-list.php');	
}
/* call back of login modal*/
function admin_options_page_login() 
{
	if(current_user_can('manage_options')) 
	{
		$login_modal_registration = get_option( 'login_modal_registration');
		$login_modal_reset_password = get_option( 'login_modal_reset_password');
		$login_modal_redirect = get_option( 'login_modal_redirect');
		$wp_user_login_redirect_afterlogin = get_option( 'wp_user_login_redirect_afterlogin');
		
		?>
		<!--<div class='wrap'>-->
		<div id="errormessage"></div>
		<div class='icon32' id='icon-options-general'><br/>
		  </div>
         
		<h1 class="modal_title_login"><?php _e( "WHIZZ Modal Login", 'whizz' ); ?></h1>
		<form method='post' id='login_modal_options' name="login_modal_options" >
			<table class='form-table'>
			  <tr valign="top">
				<th scope="row"><?php _e( "User Registration", 'whizz' ); ?>:</th>
				<td><label for="registration">
					<input type="checkbox" id="registration" name="registration" <?php if(isset($login_modal_registration) && $login_modal_registration == "on"){?>checked="checked"<?php }?> />
					<?php _e( "Enable", 'whizz' ); ?></label>
				  <br/>
				  <span class='description'></span></td>
			  </tr>
			  <tr valign="top">
				<th scope="row"><?php _e( "Password Reset", 'whizz' ); ?>:</th>
				<td><label for="reset">
					<input type="checkbox" id="reset" name="reset" <?php if(isset($login_modal_reset_password) && $login_modal_reset_password=="on"){?>checked="checked"<?php }?>/>
					<?php _e( "Enable", 'whizz' ); ?></label>
				  <br/>
				  <span class='description'></span></td>
			  </tr>
			  <tr valign="top">
				<th scope="row"><?php _e( "Select Custom Login Page", 'whizz' ); ?>:</th>
				<td><label for="shortcut">
					<input type="checkbox" id="login_modal_redirect" name="login_modal_redirect" <?php if(isset($login_modal_redirect) && $login_modal_redirect!=""){?>checked="checked"<?php }?> />
					<?php _e( "Enable", 'whizz' ); ?></label>
				  <br/>
				  <div id="wp_login_redirect_login_modal" <?php if(isset($login_modal_redirect) && $login_modal_redirect!=""){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?> ><?php _e( "Select Page", 'whizz' ); ?>
					<?php
						// WP_Query arguments
						global $wpdb;
						$login_modal_redirect =get_option( 'login_modal_redirect');
						$args = array (
							'post_type'   => 'page',
							'post_status' => 'publish',
							'fields'	=>	'ids',
							'posts_per_page' => '-1',
							'orderby'          => 'title',
							'order'            => 'DESC',
						);
						$pages_ids=get_posts($args);
					?>
					<select name="wp_login_redirect_text_login_modal" id="wp_login_redirect_text_login_modal">
					  <?php
					if(isset($pages_ids) && count($pages_ids) > 0)
					{
						?>
					  <option value=""><?php _e( "Select Login Page", 'whizz' ); ?></option>
					  <?php
							for($i=0;$i<count($pages_ids);$i++)
								{
								?>
						<option <?php if(isset($login_modal_redirect) && $login_modal_redirect==$pages_ids[$i]){?> selected="selected" <?php } ?> value="<?php echo $pages_ids[$i]; ?>">
						<?php echo get_the_title( $pages_ids[$i] ); ?>
						</option>
					  <?php
								}
							}
					else
					{
						?>
					  <option value=""><?php _e( "Pages Not Found", 'whizz' ); ?></option>
					  <?php
					}
						?>
					</select>
				  </div>
				  <span class='description'></span></td>
			  </tr>
			  <tr valign="top">
				<th scope="row"><?php _e( "User Redirect to Custom Page After Login", 'whizz' ); ?>:</th>
				<td><label for="shortcut">
					<input type="checkbox" id="whizz_login_modal_redirect_afterlogin" name="whizz_login_modal_redirect_afterlogin" <?php if(isset($wp_user_login_redirect_afterlogin) && $wp_user_login_redirect_afterlogin!=""){?>checked="checked"<?php }?> />
					<?php _e( "Enable", 'whizz' ); ?></label>
				  <br/>
				  <div id="user_login_redirect_afterlogin" <?php if(isset($wp_user_login_redirect_afterlogin) && $wp_user_login_redirect_afterlogin != ""){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>><?php _e( "Select Page", 'whizz' ); ?>
					<?php
						// WP_Query arguments
						global $wpdb;
						$wp_user_login_redirect_afterlogin = get_option( 'wp_user_login_redirect_afterlogin');
						$args = array (
							'post_type'   => 'page',
							'post_status' => 'publish',
							'fields'	=>	'ids',
							'posts_per_page' => '-1',
							'orderby'          => 'title',
							'order'            => 'DESC',
						);
						$pages_ids=get_posts($args);
						?>
					<select name="wp_user_login_redirect_text_afterlogin" id="wp_user_login_redirect_text_afterlogin">
					  <?php
							if(isset($pages_ids) && count($pages_ids) > 0)
							{ 
					?>
					  <option value="0" disabled><?php _e( "Select Redirect page", 'whizz' ); ?></option>
					  <option value="-1" <?php if(isset($wp_user_login_redirect_afterlogin) && $wp_user_login_redirect_afterlogin=="-1"){?> selected="selected" <?php } ?>><?php echo 'WP-Admin'; ?></option>
					  <?php
								for($i=0;$i<count($pages_ids);$i++)
								{
									?>
					  <option <?php if(isset($wp_user_login_redirect_afterlogin) && $wp_user_login_redirect_afterlogin==$pages_ids[$i]){?> selected="selected" <?php } ?> value="<?php echo $pages_ids[$i]; ?>">
						<?php echo get_the_title( $pages_ids[$i] ); ?>
						</option>
					  <?php
								}
							}
							else
							{
								?>
					  <option value=""><?php _e( "Pages Not Found", 'whizz' ); ?></option>
					  <?php
							}
						?>
					</select>
				  </div>
				  <span class='description'></span></td>
			  </tr>
			  <tr valign="top">
				<th scope="row"><?php _e( "Shortcode for Show Whizz Login Modal Popup", 'whizz' ); ?>:</th>
				<td>[Whizz-Login-Modal-Login]<br/>
				  <span class='description'></span></td>
			  </tr>
			  <tr valign="top">
				<th scope="row"><?php _e( "Shortcode for Whizz Login Modal Login Link", 'whizz' ); ?>:</th>
				<td>[login-modal-link]<br/>
				  <span class='description'></span></td>
			  </tr>
			</table>
			<p class='submit'>
			  <!--<input type='submit' value='Save Changes' name='simplemodal_login_save' id="simplemodal_login_save" class='button-primary' />-->
			  <input type='button' value='<?php _e( "Save Changes", 'whizz' ); ?>' name='simplemodal_login_save' id="simplemodal_login_save" class='button-primary' />
              <input type="hidden" name="whizz-main-ajax-nonce" id="whizz-main-ajax-nonce" value="<?php echo wp_create_nonce( 'whizz-main-ajax-nonce' ); ?>" />
			</p>
		   
		  </form>
		<!--</div>-->
		<?php
	}
}
/* call back of login modal end*/
$page_size = get_option(get_current_user_id().'_item_per_page');
if(empty($page_size))
{
	update_option(get_current_user_id().'_item_per_page', 10);
}
include_once('colorize/index.php');
include_once('colorize/index-rearrange.php');
include_once('plugins/index.php');	
include_once('users/index.php');
function whizz_add_dashboard_widgets_seperator()
{
	if(current_user_can('manage_options')) {
	wp_add_dashboard_widget(
                 'whizz_seperator',         // Widget slug.
                 __('WHIZZ Separator', 'whizz'),         // Title.
                 'whizz_add_dashboard_widgets_seperator_func' // Display function.
        );
	}
}
add_action( 'wp_dashboard_setup', 'whizz_add_dashboard_widgets_seperator' );
function whizz_add_dashboard_widgets_seperator_func()
{
	if(current_user_can('manage_options')) 
	{
		?>
		<div class="main-container-menus-new-plugins dashboard_widget">
		  <div class="contaner-second-block-new outer">
			<h1 style="text-align:center;"><?php _e( "Select & Drag Separator", 'whizz' ); ?></h1>
			<ul id="seperator_to_select">
			  <li class="seperator_to_select_li"></li>
			  <div class="clear"></div>
			</ul>
            <?php wp_nonce_field( 'drag_seperator_menu_value', 'nonce_drag_seperator' ); ?>  
			<br />
			<div id="reset_separators" class="reset_separators_btn"><?php _e( "Reset Separators", 'whizz' ); ?></div>
		  </div>
		  <div class="clear"></div>
		</div>
		<?php
	}
}
/* Save separators, START */
add_action('wp_ajax_whizz_save_separators', 'whizz_save_separators_func');
if(!function_exists('whizz_save_separators_func'))
{
	function whizz_save_separators_func()
	{
		if(isset($_POST['sep_loc']) && !empty($_POST['sep_loc']) && isset($_POST['sep_nonce']) && !empty($_POST['sep_nonce']) && wp_verify_nonce( $_POST['sep_nonce'], 'drag_seperator_menu_value' ) && current_user_can('manage_options'))
		{
			
			if(!is_array($_POST['sep_loc']) && sanitize_text_field($_POST['sep_loc']) == "reset")
			{
				update_option(get_current_user_id().'_separator_locations', '');
				wp_send_json_success('reset');
			}
			else if(is_array($_POST['sep_loc']) && count($_POST['sep_loc']) > 0)
			{
				$sep_loc = array_map( 'esc_attr', $_POST['sep_loc']);
				update_option(get_current_user_id().'_separator_locations', $sep_loc);
				wp_send_json_success($sep_loc);
			}
		}
		else
		{
			wp_send_json_error();
		}
	}
} 
/* Save separators, END */
/* Get separators, START */
add_action('wp_ajax_whizz_get_separators', 'whizz_get_separators_func' );
if(!function_exists('whizz_get_separators_func'))
{
	function whizz_get_separators_func()
	{
		$separators = get_option(get_current_user_id().'_separator_locations');
		if(isset($separators) && !empty($separators) && current_user_can('manage_options'))
		{
			wp_send_json_success($separators);
		}
		else
		{
			wp_send_json_success(false);
		}
	}
}
/* Get separators, END */
/* Get separators, START */
add_action('wp_ajax_whizz_reset_separators', 'whizz_reset_separators_func' );
if(!function_exists('whizz_reset_separators_func'))
{
	function whizz_reset_separators_func()
	{
		$separators = update_option(get_current_user_id().'_separator_locations', '');
		if(isset($separators) && !empty($separators) && current_user_can('manage_options')) 
		{
			wp_send_json_success($separators);
		}
	}
}
/* Get separators, END */
add_action( 'admin_enqueue_scripts', 'whizz_enqueue_and_register_my_scripts_sep' );
function whizz_enqueue_and_register_my_scripts_sep()
{
	wp_register_script( 'reg_custom_sep_js_h', plugin_dir_url(__FILE__).'js/common.js',array('jquery'), false, true );
	$main_js_obj_props = array(
							'home_url' => esc_url(home_url()),
							'admin_url' => esc_url(admin_url()),
							'admin_ajax_url' => esc_url(admin_url()).'admin-ajax.php',
							);
	wp_localize_script( 'reg_custom_sep_js_h', 'main_js_obj_common', $main_js_obj_props );	
   	wp_enqueue_script( 'reg_custom_sep_js_h' );
	
	wp_register_style( 'reg_custom_sep_css_h', plugin_dir_url(__FILE__).'css/sep_style_custom.css');
	wp_enqueue_style( 'reg_custom_sep_css_h' );
}
add_action('wp_ajax_dont_bug_me_modal_login', 'dont_bug_me_modal_login_func');
if(!function_exists('dont_bug_me_modal_login_func'))
{
	function dont_bug_me_modal_login_func()
	{
		if(current_user_can('manage_options') && isset($_POST['messageb']) && sanitize_text_field($_POST['messageb']) == "disable")
		{
			update_option('whizz_model_login_admin_notification','close');
			wp_send_json_success('ok');
		}
		else
		{
			wp_send_json_error();
		}
	}
}
function my_admin_error_notice() 
{
	$whizz_model_login = get_option("whizz_model_login_admin_notification");
	if($whizz_model_login != "close")
	{
		?>
		<div class="updated bg_message_whizz" id="whizz_modal_page_redirect_settings_notice">
			<div class="left-bug-text-new">
				<p>
					<strong><?php _e( "Whizz Modal Login :", 'whizz' ); ?> 
						<a href="admin.php?page=whizz_modal"> <?php _e( "Manage Redirect Settings", 'whizz' ); ?></a>
					</strong>
				</p>
			</div>
			<div class="right-bug-text-new" id="dont_bug_me_modal_login"><?php _e( "Don't bug me.", 'whizz' ); ?></div>
			<div style="clear:both;"></div>
		</div>
		<?php
	}
}