<?php

//if uninstall not called from WordPress exit

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 

    exit();

if(current_user_can('manage_options')) 
{
	
	delete_option(get_current_user_id().'selected_menus_colors');
	
	
	delete_option(get_current_user_id().'selected_top_menus_colors');
	
	
	delete_option(get_current_user_id().'selected_top_menus_colors_hover');
	
	
	delete_option(get_current_user_id().'selected_menus_colors_hover');
	
	
	delete_option(get_current_user_id().'user_whizz_plugin_choice');
	
	
	delete_option(get_current_user_id().'horizontal_menu_order_whizz');
	
	
	delete_option(get_current_user_id().'inactive_menus_horizontal');
	
	
	delete_option( get_current_user_id().'new_wp_custom_menu_order');
	
	
	global $wpdb;
	
	
	$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '%_plugin_order_whizz_%'" );
	
	
	delete_option(get_current_user_id().'_my_list_plugins');
	
	
	delete_option(get_current_user_id().'_item_per_page');
	
	
	$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '%_user_order_whizz_%'" );
	
	
	$wpdb->query( "DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE '%user_authenticattion_status%'" );
	
	
	delete_option(get_current_user_id().'_plugin_order_whizz_my_list');
	
	
	delete_option('dont_bug_me_top_bar');
	
	
	delete_option( get_current_user_id().'_plugin_order_whizz_my_preferences');
	
	
	delete_option( get_current_user_id().'_separator_locations');

}

?>