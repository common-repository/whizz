<?php
if( !defined('WHIZZ_PLUGIN_URL') ){
	echo 'Direct call denied.';
	exit;
}
/* Register, localize, enqueue script and style START */
add_action( 'admin_enqueue_scripts', 'whizz_enqueue_and_register_my_scripts_horizontal_menu' );
function whizz_enqueue_and_register_my_scripts_horizontal_menu()
{
	
	wp_register_script( 'reg_custom_horizontal_js_h', plugin_dir_url(__FILE__).'js/custom-js-drag-horizontal.js',array('jquery','jquery-ui-core','jquery-ui-draggable','jquery-ui-droppable','jquery-ui-sortable') , false, false );	
	$dont_bug_me_saved = get_option('dont_bug_me_top_bar');
	$dont_bug_me = "no";
	if(isset($dont_bug_me_saved) && !empty($dont_bug_me_saved))
	{
		$dont_bug_me =$dont_bug_me_saved; 
		
	}
	global $menu;
	global $submenu;
	$main_js_obj_props = array(
							'home_url' => esc_url(home_url()),
							'admin_url' => esc_url(admin_url()),
							'admin_ajax_url' => esc_url(admin_url()).'admin-ajax.php',
							'admin_menus'	=> $menu,
							'for_plugin_url' => plugin_dir_url( __FILE__ ),
							'admin_submenu'	=> $submenu,
							'dont_bug_me'	=> $dont_bug_me
							);
	wp_localize_script( 'reg_custom_horizontal_js_h', 'main_js_obj_horizontal', $main_js_obj_props );	
   	wp_enqueue_script( 'reg_custom_horizontal_js_h' );
	wp_register_style( 'style_horizontal', plugin_dir_url(__FILE__).'style/style_horizontal_menu.css' );
   	wp_enqueue_style( 'style_horizontal' );

}
/* Register, localize, enqueue script and style END */