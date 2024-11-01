<?php
if( !defined('WHIZZ_PLUGIN_URL') ){
	echo 'Direct call denied.';
	exit;
}
/* Register, localize, enqueue script and style START */
add_action( 'admin_enqueue_scripts', 'whizz_enqueue_and_register_my_scripts_color',0 );
function whizz_enqueue_and_register_my_scripts_color()
{

		wp_register_style( 'reg_custom_css_h', plugin_dir_url(__FILE__).'css/color-change-style.css');
		wp_register_script( 'reg_custom_colorize_js_h', plugin_dir_url(__FILE__).'js/custom-js-colorize.js',array('jquery','jquery-ui-core','jquery-ui-draggable','jquery-ui-droppable','jquery-ui-sortable','wp-color-picker') , false, true );
		$main_js_obj_props = array(
								'home_url' => esc_url(home_url()),
								'admin_url' => esc_url(admin_url()),
								'admin_ajax_url' => esc_url(admin_url()).'admin-ajax.php',
								'for_plugin_url' => plugin_dir_url( __FILE__ ),
								);
		wp_localize_script( 'reg_custom_colorize_js_h', 'main_js_obj_color_picker', $main_js_obj_props );
		wp_enqueue_script( 'reg_custom_colorize_js_h');
		wp_enqueue_style( 'reg_custom_css_h');
		wp_enqueue_style( 'wp-color-picker');
}
/* Register, localize, enqueue script and style END */ 
/* create element in wp admin dashboard START */
function whizz_example_add_dashboard_widgets_color()
{
	if(current_user_can('manage_options')) 
	{
		wp_add_dashboard_widget(
					 'example_dashboard_widget',         // Widget slug.
					 __('WHIZZ Colorize', 'whizz'),         // Title.
					 'whizz_example_dashboard_widget_function_color' // Display function.
			);
	}

}
add_action( 'wp_dashboard_setup', 'whizz_example_add_dashboard_widgets_color' );
function whizz_example_dashboard_widget_function_color() 
{
	if(current_user_can('manage_options')) 
	{
	ob_start();
		?>
		<div class="whizz_color_container">
		<h1><?php _e( "Color Palette to change menus color", 'whizz' ); ?></h1>
		<div class="choice_radio">
			<input type="radio" name="radio_choice_color" id="choice_background_color" />
			<label for="choice_background_color"><?php _e( "Background color for menus", 'whizz' ); ?></label><br />
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
		<div id='reset_all'>
		<strong><?php _e( "Reset Bucket", 'whizz' ); ?></strong>
		</div>
		 <div id='reset_menus_color' style=''>
			<strong><?php _e( "Reset Menus Color", 'whizz' ); ?></strong>
		</div>
		<?php wp_nonce_field( 'reset_colorize_menu', 'nonce_reset_colorize' ); ?>
	
	   <div style="clear:both;"></div>
		<div id='DivToShow'></div>
		</div>
		<?php
		$content = ob_get_clean();
		echo $content;
	}
}
/* create element in wp admin dashboard END */ 
/* For Background color START */
/* save menu ID and color of the menu via ajax call START */
add_action('wp_ajax_whizz_save_color_of_menu', 'whizz_save_color_of_menu_func' );
if(!function_exists('whizz_save_color_of_menu_func'))
{	
	function whizz_save_color_of_menu_func()
	{
		$menu_id = '';
		$menu_color = ''; 
		if(isset($_POST['menu_id']) && !empty($_POST['menu_id']) && isset($_POST['menu_color']) && !empty($_POST['menu_color']) && isset($_POST['wp_nonce']) && !empty($_POST['wp_nonce']) && wp_verify_nonce( $_POST['wp_nonce'], 'save_colorize_menu_value' ) && current_user_can('manage_options')) 
		{
			$menu_id = sanitize_text_field($_POST['menu_id']);
			$menu_color = sanitize_text_field($_POST['menu_color']);
			$data = get_option(get_current_user_id().'selected_menus_colors');
			if(!is_array($data))
			{
				$data = array();
			}
			$data[$menu_id] = $menu_color;
			update_option(get_current_user_id().'selected_menus_colors', $data);
			wp_send_json_success('Success');
		}
		else
		{
			wp_send_json_error();
		}
	}
}
/* save menu ID and color of the menu via ajax call END */
/* get id and color of the menu via ajax call START */
add_action('wp_ajax_whizz_get_color_of_menu', 'whizz_get_color_of_menu_func' );
if(!function_exists('whizz_get_color_of_menu_func'))
{
	function whizz_get_color_of_menu_func()
	{
		wp_send_json_success(get_option(get_current_user_id().'selected_menus_colors'));
	}
} 
/* get id and color of the menu via ajax call  END */
/* For Background color END */
/* For Hover color START */
/* save menu ID and color of the menu via ajax call START */
add_action('wp_ajax_whizz_save_color_of_menu_hover', 'whizz_save_color_of_menu_hover_func' );
if(!function_exists('whizz_save_color_of_menu_hover_func'))
{
	function whizz_save_color_of_menu_hover_func()
	{
		$menu_id = '';
		$menu_color = '';
		if(isset($_POST['menu_id']) && !empty($_POST['menu_id']) && isset($_POST['menu_color']) && !empty($_POST['menu_color']) && isset($_POST['wp_nonce_hover']) && !empty($_POST['wp_nonce_hover']) && wp_verify_nonce( $_POST['wp_nonce_hover'], 'save_colorize_menu_hover_value' ) && current_user_can('manage_options'))
		{ 
			$menu_id = sanitize_text_field($_POST['menu_id']);
			$menu_color = sanitize_text_field($_POST['menu_color']);
			$data = get_option(get_current_user_id().'selected_menus_colors_hover');
			if(!is_array($data))
			{
				$data = array();
			}
			$data[$menu_id] = $menu_color;
			update_option(get_current_user_id().'selected_menus_colors_hover', $data);
			wp_send_json_success('Success');
		}
		else
		{
			wp_send_json_error();
		}
	}
}
/* save menu ID and color of the menu via ajax call END */
/* get id and color of the menu via ajax call START */
add_action('wp_ajax_whizz_get_color_of_menu_hover', 'whizz_get_color_of_menu_hover_func' );
if(!function_exists('whizz_get_color_of_menu_hover_func'))
{
	function whizz_get_color_of_menu_hover_func()
	{
		wp_send_json_success(get_option(get_current_user_id().'selected_menus_colors_hover'));
	}
} 
/* get id and color of the menu via ajax call  END */
/* For Hover color END */
function whizz_check_main_menu_whizz_plugin_colorize($menu_slug_check)
{
	global $menu;
	$flag=0;
	$menu_slug ='';
	if(isset($menu_slug_check) && !empty($menu_slug_check) && current_user_can('manage_options'))
	{	
		$menu_slug = sanitize_text_field($menu_slug_check);
		foreach($menu as $single_menu)
		{
			 if($single_menu[2] == $menu_slug)
			 {
				 $flag=1;
			 }
		}
	}
	return $flag; 
}
/* save menu ID and color of the menu via ajax call START */
add_action('wp_ajax_whizz_reset_menus_color', 'whizz_reset_menus_color_func' );
if(!function_exists('whizz_reset_menus_color_func'))
{
	function whizz_reset_menus_color_func()
	{
		if(isset($_POST['task']) && !empty($_POST['task']) && sanitize_text_field($_POST['task']) == "reset" && isset($_POST['wp_nonce_reset_color']) && !empty($_POST['wp_nonce_reset_color']) && wp_verify_nonce( $_POST['wp_nonce_reset_color'], 'reset_colorize_menu' ) && current_user_can('manage_options'))
		{ 
			update_option(get_current_user_id().'selected_menus_colors', '');
			update_option(get_current_user_id().'selected_menus_colors_hover', '');
			update_option(get_current_user_id().'selected_top_menus_colors', '');
			update_option(get_current_user_id().'selected_top_menus_colors_hover', '');
			wp_send_json_success('Success'); 
		} 
		else
		{	
			wp_send_json_error();
		}
	}
} 
/* save menu ID and color of the menu via ajax call END */