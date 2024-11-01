<?php
if( !defined('WHIZZ_PLUGIN_URL') ){
	echo 'Direct call denied.';
	exit;
}
include('functions/functions.php');
function whizz_user_modification_plugins_func()
{ 
	?>	<h1><?php _e("Plugin details here", 'whizz' ); ?></h1>
   <?php
}
define('WHIZZ_PLUGINS_LIST_PLUGIN_URL','?page=plugin-list');
/* Register, localize, enqueue script and style START */
add_action( 'admin_enqueue_scripts', 'whizz_enqueue_and_register_my_scripts_plugin',0 );
function whizz_enqueue_and_register_my_scripts_plugin()
{
		wp_register_style( 'reg_custom_plugin_css_h', plugin_dir_url(__FILE__).'css/plugin_style-change-style.css');
		wp_register_script( 'reg_custom_plugin_js_h', plugin_dir_url(__FILE__).'js/custom-js-plugin-view.js',array('jquery','jquery-ui-core','jquery-ui-draggable','jquery-ui-droppable','jquery-ui-sortable') , false, true );	
		$main_js_obj_props = array(
								'home_url' => esc_url(home_url()),
								'admin_url' => esc_url(admin_url()),
								'admin_ajax_url' => esc_url(admin_url()).'admin-ajax.php',
								'for_plugin_url' => plugin_dir_url( __FILE__ ),
								);
		wp_localize_script( 'reg_custom_plugin_js_h', 'main_js_obj_plugin', $main_js_obj_props );	
		wp_enqueue_script( 'reg_custom_plugin_js_h' );
		wp_enqueue_style( 'reg_custom_plugin_css_h' );

}
/* Register, localize, enqueue script and style END */ 
add_action('wp_ajax_whizz_save_new_plugin_order_custom', 'whizz_save_new_plugin_order_custom_func' );
function whizz_save_new_plugin_order_custom_func()
{
	$plugin_order = '';
	$list_of = '';
	if(current_user_can('manage_options') && isset($_POST['plugin_order']) && !empty($_POST['plugin_order']) && isset($_POST['list_of_p']) && !empty($_POST['list_of_p']) && isset($_POST['nonce_plugin_order1']) && !empty($_POST['nonce_plugin_order1']) && wp_verify_nonce( $_POST['nonce_plugin_order1'], 'nonce_plugin_reordering' ))   
	{ 
		$list_of = sanitize_text_field($_POST['list_of_p']);
		$post_plugin_order = sanitize_text_field($_POST['plugin_order']);
		$plugin_order = stripslashes($post_plugin_order);
		$plugin_order = json_decode( $plugin_order );
		$plugin_order = whizz_dv_plugin_order_object_to_array($plugin_order);
		update_option(get_current_user_id().'_plugin_order_whizz_'.$list_of,$plugin_order);
		wp_send_json_success( 'Success' );
	}
	else
	{
		wp_send_json_error();
	}
}
function whizz_dv_plugin_order_object_to_array($data)
{
    if (is_array($data) || is_object($data))
    {
        $result = array();
        foreach ($data as $key => $value)
        {
            $result[$key] = whizz_dv_plugin_order_object_to_array($value);
        }
        return $result;
    }
    return $data;
}
function whizz_check_main_menu_whizz_plugins($menu_slug_check)
{
	global $menu;
	$flag=0;
	if(isset($menu_slug_check) && !empty($menu_slug_check))
	foreach($menu as $single_menu)
	{
		 if($single_menu[2] == $menu_slug_check)
		 {
			 $flag=1;
		 }
	}
	return $flag;
}
add_action('wp_ajax_whizz_search_plugin_wp_admin', 'whizz_search_plugin_wp_admin_func' );
function whizz_search_plugin_wp_admin_func()
{
	$search_string = '';
	if(isset($_POST['search_term']) && !empty($_POST['search_term']) && isset($_POST['nonce_search_submit_plugin']) && !empty($_POST['nonce_search_submit_plugin']) && wp_verify_nonce( $_POST['nonce_search_submit_plugin'], 'nonce_search_plugin' ) && current_user_can('manage_options'))
	{ 
		$search_string = sanitize_text_field($_POST['search_term']);
		$plugin_list_temp = get_plugins();
			$ordered_appended_array = array();

			if(isset($plugin_list_temp) && !empty($plugin_list_temp))
			{
				foreach($plugin_list_temp as $order_key => $order_value)
				{
					if(strstr(strtolower($order_value['Name']), strtolower($search_string)) != false || strstr(strtolower($order_value['Description']), strtolower($search_string)) != false) 
					{
						$ordered_appended_array[$order_key] = $order_value;
					}
				}
			}		
			$list = "<ul id='list_view_plugin'>";
			  ob_start();
			  foreach($ordered_appended_array as $plugin_key => $plugin_value)
			  {
				  ?>
				  <li class="li_inside_ul_list" id="<?php echo sanitize_text_field($plugin_key); ?>">
					  <div class="main_div_inside_li_list">
						  <div class="span6 col-md-6">
						  <div class="plugin_name_list">
						  <input type="checkbox" name="selected_checkboxes[]" value="<?php echo sanitize_text_field($plugin_key); ?>" />
						  <input type="hidden" name="selected_<?php echo str_replace('.','_', sanitize_text_field($plugin_key)); ?>" value="<?php echo trim(sanitize_text_field($plugin_value['Name'])); ?>" />
									
						  <?php 
							  echo sanitize_text_field($plugin_value['Name']);
						  ?>
						  </div>
						  <ul>						 
							  <li>
								<a href='<?php echo esc_url( add_query_arg( array('file' => sanitize_text_field($plugin_key) ),'plugin-editor.php')); ?>'><?php _e('Edit', 'whizz'); ?></a>
								  
							  </li>
							  <li>
								  <?php
								  if( strstr($plugin_key, 'whizz-plugin') == false)
								  {
									$list_of = '';
									if(isset($_GET['list_of']) && !empty($_GET['list_of']))
									{
										$list_of = sanitize_text_field($_GET['list_of']);
									}	
									?>
										
										<a href='<?php echo esc_url( add_query_arg( array('action' => 'deletep','ppath' => sanitize_text_field($plugin_key),'view' => 'list_view','list_of' => $list_of ),WHIZZ_PLUGINS_LIST_PLUGIN_URL)); ?>'><?php _e('Delete', 'whizz'); ?></a>
									<?php
								  }
								  ?> 
							  </li>						 
						  </ul>
						  </div>
							<div class="span6 col-md-6">
							<div class="plugin_desc_list"><?php echo sanitize_text_field($plugin_value['Description']); ?></div>
							<ul>
								<li><div class="plugin_version_list">Version <?php echo sanitize_text_field($plugin_value['Version']); ?></div></li> | 
								<li><div class="plugin_author_name_list"><?php echo sanitize_text_field($plugin_value['AuthorName']); ?></div></li> | 
								<li><div class="plugin_uri_list"><?php $more_details = esc_url($plugin_value['PluginURI']); echo "<a href='".$more_details."'>".__('More Details', 'whizz')."</a>"; ?></div></li>
							</ul>
						  </div>
					  </div>
					  <?php wp_nonce_field( 'plugin_list_reorder', 'nonce_plugin_list' ); ?>  
					  <div class="clear"></div>
					  
				  </li>
				  <?php
			  }
			  $list .= ob_get_clean();
			  $list .="</ul>";
			wp_send_json_success( $list );

	}
	wp_send_json_error();
} 