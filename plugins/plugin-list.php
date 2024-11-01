<?php
if( !defined('WHIZZ_PLUGIN_URL') ){
	echo 'Direct call denied.';
	exit;
}
	$delete_flag='none';
	$error = "";

	if(isset($_POST['bulk_action_submit']) && isset($_POST['bulk_action_ddl']) && !empty($_POST['bulk_action_ddl']) && intval($_POST['bulk_action_ddl']) && $_POST['bulk_action_ddl'] == "3" && isset($_POST['wpnonce_pluginbulkactionsubmit']) && !empty($_POST['wpnonce_pluginbulkactionsubmit']) && wp_verify_nonce( $_POST['wpnonce_pluginbulkactionsubmit'], 'nonce_plugin_bulk_action_submit' ) && current_user_can('manage_options')) 
	{	 
		if(isset($_POST['selected_checkboxes']) && !empty($_POST['selected_checkboxes']) && count($_POST['selected_checkboxes']) >0 )
		{
			$selected_checkbox = array_map( 'esc_attr', $_POST['selected_checkboxes']);
			?>
			 <form class="deletation-form-class" name="frm_delete_final" method="post" action="">
				<h1><?php _e("Delete Plugins", 'whizz' ); ?></h1>
                <p><?php _e("You are about to remove the following plugin", 'whizz' ); ?>:</p>
                <?php
				foreach($selected_checkbox as $singlechk)
				{
echo "<div class='name-of-plugin'><ul><li>".$_POST['selected_'.str_replace('.','_',$singlechk)]."</li></ul></div>";
				}
				?>
				<p><?php _e("Are you sure you want to delete these/this Plugin/s?", 'whizz' ); ?></p>
				<input class="option-for-select" type="submit" name="final_delete_bulk" value="<?php _e("Yes Delete Plugin/s", 'whizz' ); ?>" />
				<input class="option-for-select" type="submit" name="return_back" value="<?php _e("No Return me to the plugins list", 'whizz' ); ?>" />
				<input type="hidden" name="plugin_ids" value="<?php echo implode(",", $selected_checkbox); ?>" />
				<?php wp_nonce_field( 'bulk_delete_plugin_whizz', 'bulkwpnonce' ); ?>
                             
			</form>
			<?php
		}
		else
		{
			?>
			 <form name="frm_delete_final" method="post" action="">
				<h1><?php _e("Delete Plugins", 'whizz' ); ?></h1>
				<p><?php _e("No any plugin selected.", 'whizz' ); ?></p>
				<input type="submit" name="return_back" id="return_back" value="Back" />
			</form>
			<?php
		}
	}
	else
	{	
		if(isset($_GET['deletec']) && !empty($_GET['deletec']) && sanitize_text_field($_GET['deletec']) == "yes" && current_user_can('manage_options'))
		{
			
			if ( isset($_POST['_wpnonce']) && !empty($_POST['_wpnonce']) && wp_verify_nonce( $_POST['_wpnonce'], 'delete_plugin_whizz' ) )
			{
				if( isset($_GET['ppath']) && !empty($_GET['ppath']) && strstr($_GET['ppath'], 'whizz-plugin') == false)
				{
				  $get_ppath = sanitize_text_field($_GET['ppath']);
				  $result = delete_plugins( array($get_ppath));
				  if ( is_wp_error( $result ) ) 
				  {
					  $delete_flag = false;
				  }
				  else
				  {
					  $delete_flag = true;
				  }
				}
			}
			else
			{
				  $delete_flag = false;
			}
  
		}
		
		if(isset($_GET['action']) && !empty($_GET['action']) && sanitize_text_field($_GET['action']) == "activatep" && isset($_GET['ppath']) && !empty($_GET['ppath']) && isset($_GET['nonce_is_plugin_deactive']) && !empty($_GET['nonce_is_plugin_deactive']) && wp_verify_nonce( $_GET['nonce_is_plugin_deactive'], 'nonce_activatep' ) && current_user_can('manage_options'))
		{

			$get_ppath = sanitize_text_field($_GET['ppath'] );
			$result = activate_plugin( $get_ppath );
			if ( is_wp_error( $result ) ) 
			{
				_e("Activation Failed.", 'whizz');
			}
		}
	
		if(isset($_GET['action']) && !empty($_GET['action']) && sanitize_text_field($_GET['action']) == "deactivatep" && isset($_GET['ppath']) && !empty($_GET['ppath']) && isset($_GET['nonce_is_plugin_active']) && !empty($_GET['nonce_is_plugin_active']) && wp_verify_nonce( $_GET['nonce_is_plugin_active'], 'nonce_deactivatep' ) && current_user_can('manage_options'))
		{

			$get_path = sanitize_text_field($_GET['ppath']);
			$result = deactivate_plugins($get_path);
			if ( is_wp_error( $result ) ) 
			{
				_e("Deactivation Failed.", 'whizz');
			}
		}
			
		if(isset($_POST['bulk_action_submit']) && isset($_POST['wpnonce_pluginbulkactionsubmit']) && !empty($_POST['wpnonce_pluginbulkactionsubmit']) && wp_verify_nonce( $_POST['wpnonce_pluginbulkactionsubmit'], 'nonce_plugin_bulk_action_submit' ) && current_user_can('manage_options'))
		{
			if(isset($_POST['bulk_action_ddl']) && !empty($_POST['bulk_action_ddl']) && intval($_POST['bulk_action_ddl']) && $_POST['bulk_action_ddl'] == "1" && isset($_POST['selected_checkboxes']) && !empty($_POST['selected_checkboxes']))
			{
				if(count($_POST['selected_checkboxes']) >0 )
				{
					$chk_ids = array_map( 'esc_attr', $_POST['selected_checkboxes']);
					foreach($chk_ids as $id)
					{
						$result = activate_plugin( $id );	
					}
					if ( is_wp_error( $result ) ) 
					{
						$error = "Activation Failed.";
					}
					else
					{
						$error = "Activated Successfully.";
					}
				} 
				
			}
			else if(isset($_POST['bulk_action_ddl']) && !empty($_POST['bulk_action_ddl']) && intval($_POST['bulk_action_ddl']) && intval($_POST['bulk_action_ddl']) == 2 && isset($_POST['selected_checkboxes']) && !empty($_POST['selected_checkboxes']))
			{
				if(count($_POST['selected_checkboxes']) >0 )
				{
					$chk_ids = array_map( 'esc_attr', $_POST['selected_checkboxes']);
					foreach($chk_ids as $id)
					{
						$result = deactivate_plugins( $id );
					}
					if ( is_wp_error( $result ) ) 
					{
						$error = "Deactivation Failed.";
					}
					else
					{
						$error = "Deactivated Successfully.";
					}
				}
			}
			else if(isset($_POST['bulk_action_ddl']) && !empty($_POST['bulk_action_ddl']) && intval($_POST['bulk_action_ddl']) && intval($_POST['bulk_action_ddl']) == 1)
			{
				$error = "Please select minimum 1 plugin to proceed.";
			}
			else
			{
				$error = "Please select minimum 1 plugin to proceed.";
			}
		}
		
		if(isset($_POST['final_delete_bulk']) && !empty($_POST['final_delete_bulk']) && current_user_can('manage_options'))
		{
			if ( isset($_POST['bulkwpnonce']) && !empty($_POST['bulkwpnonce']) && wp_verify_nonce( $_POST['bulkwpnonce'], 'bulk_delete_plugin_whizz' ) )
			{
				$plugin_id_num = '';
				if(isset($_POST['plugin_ids']) && !empty($_POST['plugin_ids'])) {
				$plugin_id_num = sanitize_text_field($_POST['plugin_ids']);	
				}	
				$plugin_ids = explode(",", $plugin_id_num);
	
				$ids = array();
				foreach($plugin_ids as $plugin_id)
				{				
					if( strstr($plugin_id, 'whizz-plugin') )
					{
					}
					else
					{
						$ids[] = $plugin_id;	
					}
				}
				$result = delete_plugins($ids);
				if ( is_wp_error( $result ) ) 
				{
					$error = "Plugin/s deletion error! Please try again.";
				}
				else
				{
					$error = "Plugin/s deleted successfully.";
				}
			}
		}
		if(isset($_POST['reset_order_current']) && !empty($_POST['reset_order_current']) && isset($_POST['wpnonce_reset_order_current']) && !empty($_POST['wpnonce_reset_order_current']) && wp_verify_nonce( $_POST['wpnonce_reset_order_current'], 'nonce_reset_order_submit' ) && current_user_can('manage_options'))
		{
			$selected_filter = '';
			if(isset($_GET['list_of']) && !empty($_GET['list_of'])) {
				$selected_filter = sanitize_text_field($_GET['list_of']);
			}
			
			if($selected_filter)
			{
				update_option(get_current_user_id().'_plugin_order_whizz_'.$selected_filter,"");
			}
			else
			{
				update_option(get_current_user_id().'_plugin_order_whizz_all_plugins',"");
			}
		}
		
		if(isset($_POST['reset_order_all']) && !empty($_POST['reset_order_all']) && isset($_POST['wpnonce_reset_order_all']) && !empty($_POST['wpnonce_reset_order_all']) && wp_verify_nonce( $_POST['wpnonce_reset_order_all'], 'nonce_reset_order_all_submit') && current_user_can('manage_options'))
		{
			update_option(get_current_user_id().'_plugin_order_whizz_active_plugins',"");
			update_option(get_current_user_id().'_plugin_order_whizz_inactive_plugins',"");
			update_option(get_current_user_id().'_plugin_order_whizz_active_update_available',"");
			update_option(get_current_user_id().'_plugin_order_whizz_inactive_update_available',"");
			update_option(get_current_user_id().'_plugin_order_whizz_my_preferences',"");
			update_option(get_current_user_id().'_plugin_order_whizz_all_plugins',"");
		}
	
		include_once('functions/functions.php');
		$total_plugins = 0;
		$active_plugins = 0;
		$active_update_available = 0;
		$inactive_update_available = 0;
		$my_list = 0;
		$plugin_list_count = get_plugins();

			foreach($plugin_list_count as $plugin_key => $plugin_value)
			{
				$total_plugins++;	
				$current = get_site_transient( 'update_plugins' );
				if ( is_plugin_active( $plugin_key ) )
				{
					$active_plugins++;
					foreach($current->response as $upkey => $upval)
					{
						if($upkey == $plugin_key)
						{
							$active_update_available++;
						}
					}
				}
				else
				{
					foreach($current->response as $upkey => $upval)
					{
						if(isset($upkey) && !empty($upkey) && sanitize_text_field($upkey) == $plugin_key)
						{
							$inactive_update_available++;
						}
					}
				}
			}

		$base_url="";
		$base_url = WHIZZ_PLUGINS_LIST_PLUGIN_URL."&view=list_view";		
		if(isset($_GET['action']) && !empty($_GET['action']) && sanitize_text_field($_GET['action']) == "deletep" && current_user_can('manage_options'))
		{	
			include_once('delete-plugin.php');		
		}
		else if(current_user_can('manage_options'))
		{
			?>
			<script type="text/javascript">
				(function($){
					$(document).ready(function(e) {
						$("#list_view_underline").addClass('head_text_li');
						$("#grid_view_underline").removeClass('head_text_li');
					});
				})(jQuery)
		</script>
		<form name="plugin_custom_form" action="" method="post">
			<div class="color-bar-plugin">
			<h1 class="plugin-new-title"><?php _e("WHIZZ Plugins", 'whizz' ); ?></h1>
			<div class="addnew-button-new">
				<a href="plugin-install.php" class="button"><?php _e("Add New", 'whizz' ); ?></a>
			</div>
            <div class="clear"></div>
            </div>
           	<div style="clear:both;"></div>
            <?php
				global $delete_flag;
				if(isset($delete_flag) && !empty($delete_flag))
				{
					if(sanitize_text_field($delete_flag) == true)
					{
						_e("Deleted Successfully.", 'whizz' );
					}
					else if(sanitize_text_field($delete_flag) == false)
					{
						_e("Deletion Error.", 'whizz' );
					}
				}
				if(!empty($error)) 
				{
					echo '<div class="errorshow-container"><p id="errorshow" class="errorshow-class-new">'.__($error, 'whizz').'</p></div>';
				}
			?>                    
			<div class="bulk-action-container">
            	<input type="checkbox" name="select_all" id="select_all" />
				<select id="bulk_action_ddl" name="bulk_action_ddl">
					<option value="0"><?php _e("Bulk Actions", 'whizz' ); ?></option>               
					<option value="1"><?php _e("Activate", 'whizz' ); ?></option>
					<option value="2"><?php _e("Deactivate", 'whizz' ); ?></option>
					<option value="3"><?php _e("Delete", 'whizz' ); ?></option>
				</select>
                <?php wp_nonce_field( 'nonce_plugin_bulk_action_submit', 'wpnonce_pluginbulkactionsubmit' ); ?>  
				<input type="submit" name="bulk_action_submit"  id="bulk_action_submit" value="<?php _e("Apply", 'whizz' ); ?>" class="button" />
                 <input type="submit" class="button" name="reset_order_current" value="<?php _e("Reset Order (Current)", 'whizz' ); ?>" />
        		<?php wp_nonce_field( 'nonce_reset_order_submit', 'wpnonce_reset_order_current' ); ?>                    
                        <input type="submit" class="button" name="reset_order_all" value="<?php _e("Reset Order (All)", 'whizz' ); ?>" />
        		<?php wp_nonce_field( 'nonce_reset_order_all_submit', 'wpnonce_reset_order_all' ); ?>                         
			</div>
			<div class="bulk-action-container-search">
				<input type="text" name="search_term_plugin" id="search_term_plugin" maxlength="100" size="20" />
				<input type="button" name="search_submit"  id="search_submit" value="<?php _e("Search Plugin", 'whizz' ); ?>" class="button" />
                  <?php wp_nonce_field( 'nonce_search_plugin', 'wpnonce_search_submit' ); ?>              
			</div>
			<div class="main_div_head_text">
			  <ul class="heading_filters">
				<?php
					if(isset($_GET['list_of']) && !empty($_GET['list_of']))
					{
						if(sanitize_text_field($_GET['list_of']) == "all_plugins")
						{
							echo "<li id='heading_filters_all' class='head_text_li'>";
						}
						else
						{
							echo "<li id='heading_filters_all'>";
						}
					}
					else
					{ 
						echo "<li id='heading_filters_all' class='head_text_li'>";
					}
				?>
                <?php $all_plugins = esc_url(add_query_arg( array( 'list_of' => 'all_plugins'), $base_url)); ?>
				<a href="<?php echo $all_plugins; ?>"> <?php _e("All Plugins", 'whizz' ); ?> <b>(<?php echo intval($total_plugins); ?>)</b> </a>
                </li>
				<?php
					  if(isset($_GET['list_of']) && !empty($_GET['list_of']) && sanitize_text_field($_GET['list_of']) == "active_plugins")
					  {
						  echo "<li id='heading_filters_all_active' class='head_text_li'>";
					  }
					  else
					  {
						  echo "<li id='heading_filters_all_active'>";
					  }
				?>
                <?php $all_active_plugins = esc_url(add_query_arg( array( 'list_of' => 'active_plugins'), $base_url)); ?>
				<a href="<?php echo $all_active_plugins; ?>"> <?php _e("Active Plugins", 'whizz' ); ?> <b>(<?php echo intval($active_plugins); ?>)</b> </a>           
				</li>            
				<?php
					  if(isset($_GET['list_of']) && !empty($_GET['list_of']) && sanitize_text_field($_GET['list_of']) == "inactive_plugins")
					  {
						  echo "<li id='heading_filters_all_inactive' class='head_text_li'>";
					  }
					  else
					  {
						  echo "<li id='heading_filters_all_inactive'>";
					  }
				?>
					<?php $inactive_plugins = esc_url(add_query_arg( array( 'list_of' => 'inactive_plugins'), $base_url)); ?>
                    
                    <a href="<?php echo $inactive_plugins; ?>"> <?php _e("Inactive Plugins", 'whizz' ); ?> <b>(<?php echo intval($total_plugins) - intval($active_plugins); ?>)</b> </a>
					</li>
					<?php
						if(isset($_GET['list_of']) && !empty($_GET['list_of']) && sanitize_text_field($_GET['list_of']) == "active_update_available")
						{
							echo "<li id='heading_filters_active_update_available' class='head_text_li'>";
						}
						else
						{
							echo "<li id='heading_filters_active_update_available'>";
						}
					?>
                    <?php $active_update = esc_url(add_query_arg( array( 'list_of' => 'active_update_available'), $base_url)); ?>
					<a href="<?php echo $active_update ?>"> <?php _e("Active & Updates Available", 'whizz' ); ?> <b>(<?php echo intval($active_update_available); ?>)</b> </a>
					</li>
					<?php
						if(isset($_GET['list_of']) && !empty($_GET['list_of']) && sanitize_text_field($_GET['list_of']) == "inactive_update_available")
						{
							echo "<li id='heading_filters_inactive_update_available' class='head_text_li'>";
						}
						else
						{
							echo "<li id='heading_filters_inactive_update_available'>";
						}
					?>
					<?php $inactive_update = esc_url(add_query_arg( array( 'list_of' => 'inactive_update_available'), $base_url)); ?>
                    
                    <a href="<?php echo $inactive_update; ?>"> <?php _e("Inactive & Require Update", 'whizz' ); ?> <b>(<?php echo intval($inactive_update_available); ?>)</b> </a>
					</li>
				  </ul>
				  
				  <ul class="heading_text">
					<li id="list_view_underline">
                    <?php if(isset($_GET['list_of']) && !empty($_GET['list_of']))
						{ 	
                    	$get_list_of = sanitize_text_field($_GET['list_of']);
						?>		
						<?php $plugin_list_view = esc_url(add_query_arg( array( 'view' => 'list_view', 'list_of' => $get_list_of), WHIZZ_PLUGINS_LIST_PLUGIN_URL)); ?>
                        <a href="<?php echo $plugin_list_view; ?>"> List View </a>
                        <?php }else{ ?>
                        <?php $listview = esc_url(add_query_arg( array( 'view' => 'list_view'), WHIZZ_PLUGINS_LIST_PLUGIN_URL)); ?>
                        <a href="<?php echo $listview; ?>"> List View </a>
                        <?php } ?>
					</li>					
				  </ul>
				</div>
				<div id="ajax_loader"> 
                	<img src="<?php echo plugin_dir_url(__FILE__); ?>img/ajax-loader.gif" />
                </div>
				<table border="0" cellpadding="0" width="100%">
					<tr>
					<td id="plugin_list_td">
					<?php
					
						  if(isset($_GET['list_of']) && !empty($_GET['list_of']) && sanitize_text_field($_GET['list_of']) == "active_plugins")
						  {
							  echo whizz_plugin_list_view_active_plugins();
						  }
						  else if(isset($_GET['list_of']) && !empty($_GET['list_of']) && sanitize_text_field($_GET['list_of']) == "inactive_plugins")
						  {					  
							  echo whizz_plugin_list_view_inactive_plugins();
						  }
						  else if(isset($_GET['list_of']) && !empty($_GET['list_of']) && sanitize_text_field($_GET['list_of']) == "active_update_available")						  
						  {						  
							  echo whizz_plugin_list_view_active_update_available_plugins();
						  }
						  else if(isset($_GET['list_of']) && !empty($_GET['list_of']) && sanitize_text_field($_GET['list_of']) == "inactive_update_available")
						  {
							  echo whizz_plugin_list_view_inactive_update_available_plugins();
						  }						  
						  else
						  {							  
							  echo whizz_plugin_list_view();
						  }					  
					?> 
                    <?php wp_nonce_field( 'nonce_plugin_reordering', 'wpnonce_plugin_reordering' ); ?> 
					</td>
					</tr>
				</table>
			</form>       
		<?php
		}
	}
?>