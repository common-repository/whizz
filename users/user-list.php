<?php
if( !defined('WHIZZ_PLUGIN_URL') ){
	echo 'Direct call denied.';
	exit;
}

if(isset($_POST['bulk_action_submit']) && isset($_POST['bulk_action']) && !empty($_POST['bulk_action']) && intval($_POST['bulk_action']) && $_POST['bulk_action'] > 0 && isset($_POST['wpnonce_bulkactionsubmit']) && !empty($_POST['wpnonce_bulkactionsubmit']) && wp_verify_nonce( $_POST['wpnonce_bulkactionsubmit'], 'form_bulk_action_submit' ) && current_user_can('manage_options'))
{  
		$list_of = '';
		if(isset($_GET['list_of']) && !empty($_GET['list_of'])) 
		{
			$list_of = sanitize_text_field($_GET['list_of']);
		}
		$view = '';
		if(isset($_GET['view']) && !empty($_GET['view']))
		{
			$view = sanitize_text_field($_GET['view']);
		}
		$user_ids = '';
		if(isset($_POST['chk_user']) && !empty($_POST['chk_user']) && count($_POST['chk_user']) > 0)
		{
			$user_ids = array_map( 'esc_attr', $_POST['chk_user']);
		}

		$action_url = esc_url(add_query_arg( array( 'view' => $view, 'deletec' => 'yes', 'list_of' =>$list_of), WHIZZ_USERS_LIST_PLUGIN_URL));
		?>
		<?php if(isset($user_ids) && !empty($user_ids) && count($user_ids) > 0 ) { ?>
        <form name="frm_delete_final" method="post" action="<?php echo $action_url; ?>">
			<h1><?php _e("Delete Users", 'whizz'); ?></h1>
			<p><?php _e("Are you sure you want to delete these/this user?", 'whizz'); ?></p>
			<input type="submit" name="final_delete_bulk" id="final_delete_bulk" value="<?php _e("Yes Delete User/s", 'whizz'); ?>" />
			<input type="submit" name="return_back" id="return_back" value="<?php _e("No Return me to the users list", 'whizz'); ?>" />
			<input type="hidden" name="user_ids" value="<?php echo implode(",", $user_ids); ?>" />
            <?php wp_nonce_field( 'bulk_delete_user_whizz', 'bulkuserwpnonce' ); ?>
		</form> 
        <?php 
		} 
		else { ?>
		<form name="frm_delete_final" method="post" action="<?php echo $action_url; ?>">
        <h1><?php _e("Delete User", 'whizz'); ?></h1>
		<p><?php _e("No any user selected.", 'whizz'); ?></p>
        <input type="submit" name="return_back" id="return_back" value="<?php _e("Back", 'whizz'); ?>" />
		</form> 

		<?php }
		?>

		<?php 
	
}
else if(current_user_can('manage_options'))
{
		$delete_flag = 'none';
		if(isset($_GET['deletec']) && !empty($_GET['deletec']) && sanitize_text_field($_GET['deletec']) == "yes")
		{
				if(isset($_POST['final_delete_bulk']) && !empty($_POST['final_delete_bulk']) && isset($_POST['user_ids']) && !empty($_POST['user_ids']) && isset($_POST['bulkuserwpnonce']) && !empty($_POST['bulkuserwpnonce']) && wp_verify_nonce( $_POST['bulkuserwpnonce'], 'bulk_delete_user_whizz' ))
				{
					$user_ids_str = sanitize_text_field($_POST['user_ids']);
					$user_ids = explode(",", $user_ids_str);
					foreach($user_ids as $user)
					{
						$user_info = get_userdata( $user );
						if($user_info)
						{
							if($user_info->roles[0] != "administrator")
							{
								wp_delete_user( $user); 
							}						
						}
					}
					$delete_flag = true;
				}
				else
				{
					
					if(isset($_GET['uid']) && !empty($_GET['uid']) && intval($_GET['uid']) && isset($_POST['wpnonceuser']) && !empty($_POST['wpnonceuser']) && wp_verify_nonce( $_POST['wpnonceuser'], 'delete_single_user_whizz' ) && current_user_can('manage_options'))
					{
							$uid = intval($_GET['uid']);
							$user_info = get_userdata($uid);
							if(isset($user_info) && !empty($user_info))
							{
								if($user_info->roles[0] != "administrator")
								{ 
									$result = wp_delete_user( $uid );
									
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
					}
				}
		} 
		include_once('functions/functions.php');
		$base_url="";
		if(isset($_GET['action']) && !empty($_GET['action']) && isset($_GET['uid']) && !empty($_GET['uid']) && intval($_GET['uid']) )
		{
			$get_uid = intval($_GET['uid']); 
			if(isset($_GET['action']) && !empty($_GET['action']) && sanitize_text_field($_GET['action']) == "Activate")
			{
				update_user_meta($get_uid, 'user_authenticattion_status', 'yes'); 
			}
			else if(isset($_GET['action']) && !empty($_GET['action']) && sanitize_text_field($_GET['action']) == "Deactivate")
			{
				update_user_meta($get_uid, 'user_authenticattion_status', 'no'); 
			}
		}
		
		/* escaped where used, there is also added extra parameters */
		$base_url = add_query_arg( array( 'view' => 'list_view'), WHIZZ_USERS_LIST_PLUGIN_URL);
			
		if(isset($_GET['action']) && !empty($_GET['action']) && sanitize_text_field($_GET['action']) == "deleteu")
		{
			include_once('delete-user.php');		
		}
		else
		{			
			?>
			<script type="text/javascript">
				(function($){		
					$(document).ready(function(e){
						$("#list_view_underline").addClass('head_text_li');		
						$("#grid_view_underline").removeClass('head_text_li');		
					});
				})(jQuery)		
			</script>
            	
			<h1 class="plugin-new-user-title"><?php _e("WHIZZ Users", 'whizz'); ?></h1>
			<span id="errorshow">
			<?php
				global $delete_flag;
				if(isset($delete_flag) && !empty($delete_flag) )
				{
					if(sanitize_text_field($delete_flag) == true)
					{
						_e("Deleted Successfully.", 'whizz');
					}
					else if(sanitize_text_field($delete_flag) == false)
					{
						_e("Deletion Error.", 'whizz');
					}
				}
			?>
			</span>
			<div class="main_div_head_text">
				<ul class="heading_filters">
					<?php
						$list_of = '';
						if(isset($_GET['list_of']) && !empty($_GET['list_of']))
						{
							$list_of = sanitize_text_field($_GET['list_of']);
						}
						$count_all_users = user_role_count_assoc_array();		
						$total_users = 0;		
						foreach($count_all_users as $key => $value)
						{
							$total_users = $total_users + $value;
						}
						if(isset($list_of) && !empty($list_of))
						{
							
							if($list_of == "all_users")
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
                    <?php $list_all_users = esc_url(add_query_arg( array( 'list_of' => 'all_users'), $base_url)); ?>
						<a href="<?php echo $list_all_users ; ?>">
							<?php _e("All Users", 'whizz'); ?> <b>(<?php echo $total_users; ?>)</b>
						</a>
					</li>
					 <?php
						global $wp_roles;
						
						$roles_and_count = user_role_count_assoc_array();
						foreach($roles_and_count as $key => $value)
						{
							if($list_of == $key)
							{   
								echo "<li id='heading_filters_all' class='head_text_li'>";
							} 
							else
							{
								echo "<li id='heading_filters_all'>";
							}
							$t_role_name = sanitize_text_field($wp_roles->roles[$key]['name']); 
							$roles_value_url = esc_url(add_query_arg( array( 'list_of' => $key))); 
							echo "<a href='".$roles_value_url."'>".$t_role_name." <b>(".$value.")</b> </a>";
							echo "</li>";
						}
						if($list_of == "my_list")
						{
							echo "<li id='heading_filters_all' class='head_text_li'>";
						}
						else
						{
							echo "<li id='heading_filters_all'>";
						}
						$count_my_list_users = 0;
						$saved_my_list = get_option(get_current_user_id().'_user_order_whizz_my_list');
						if(isset($saved_my_list) && !empty($saved_my_list))
						{
							$count_my_list_users =count($saved_my_list);	
						}
					?>
					</li>
                       
				</ul>
				<ul class="heading_text">
					<li id="list_view_underline">
                    <?php 
					if(isset($_GET['list_of']) && !empty($_GET['list_of'])) {
						$list_of1 = sanitize_text_field($_GET['list_of']);
					}
					else {
					$list_of1 ="";	
					}
					
					$list_view_url = esc_url(add_query_arg( array( 'view' => 'list_view', 'list_of' => $list_of1), WHIZZ_USERS_LIST_PLUGIN_URL));
					?>
						<a href="<?php echo $list_view_url; ?>">
							<?php _e("List View", 'whizz'); ?>
						</a>
					</li>
				</ul>
                <?php wp_nonce_field( 'nonce_heading_filter', 'wpnonce_user_roles' ); ?>
			</div>
            <form action="" method="post">
			<table border="0" cellpadding="0" width="100%">
				<tr>
					<td>
						<table border="0" cellpadding="0" width="100%" class="tb-new-enw">                            
                        	<tr>
                            	<td id="loader_top"> 
                                <?php $img_file_path = plugin_dir_url(__FILE__)."img/ajax-loader.gif"; ?>
                                <img src="<?php echo esc_url($img_file_path); ?>" /> 
                                </td>
                            </tr>
                            <tr>
                            	<td>
                                <input type="checkbox" name="select_all" id="select_all" />
                                <?php
								$item_per_page = get_option(get_current_user_id().'_item_per_page');
								?>
                                <select name="bulk_action" id="bulk_action">
                                    <option value="0"><?php _e("Bulk Actions", 'whizz'); ?></option>
                                    <option value="1"><?php _e("Delete", 'whizz'); ?></option>
                                </select>
                                <?php wp_nonce_field( 'form_bulk_action_submit', 'wpnonce_bulkactionsubmit' ); ?>
                                <input class="button" type="submit" name="bulk_action_submit" id="bulk_action_submit" value="<?php _e("Apply", 'whizz'); ?>" />
                                &nbsp; &nbsp; 
<div class="clear-res"></div>
                                <strong class="make-me-small-fontr-zie"><?php _e("Item per page", 'whizz'); ?></strong> : 
                                <select name="item_per_page" id="item_per_page">
                                    <option value="5" <?php if($item_per_page == 5){ echo "selected='selected'";} ?>>5</option>
                                    <option value="10" <?php if($item_per_page == 10){ echo "selected='selected'";} ?>>10</option>
                                    <option value="20" <?php if($item_per_page == 20){ echo "selected='selected'";} ?>>20</option>
                                    <option value="30" <?php if($item_per_page == 30){ echo "selected='selected'";} ?>>30</option>
                                    <option value="50" <?php if($item_per_page == 50){ echo "selected='selected'";} ?>>50</option>
                                </select>
                                <?php wp_nonce_field( 'nonce_item_per_page', 'wpnonce_itemper_page' ); ?>
                                	<input class="button" type="button" name="reset_to_default_order" id="reset_to_default_order" value="<?php _e("Reset to default order", 'whizz'); ?>" />
                                	<?php wp_nonce_field( 'nonce_user_default_order', 'wpnonce_reset_to_default_order' ); ?>                                    
                                </td>
                                <td class="new-new-new-one-two" align="right">
                                	<input type="text" name="search_users" id="search_users" maxlength="50" />
                                    <input type="button" name="search_users_submit" id="search_users_submit" value="<?php _e("Search Users", 'whizz'); ?>" class="button" />
                                	<?php wp_nonce_field( 'nonce_search_submit', 'wpnonce_search_users_submit' ); ?>                                       
                                </td>
                                <td class="atoz-cls" align="right">
                                	<span id="ascending_order" style="cursor:pointer;" title="<?php _e("click to sort in ascending order", 'whizz'); ?>"> <strong>A - Z</strong> </span>
                                    <span id="descending_order" style="cursor:pointer; display:none;" title="<?php _e("click to sort in descending order", 'whizz'); ?>"> <strong>Z - A</strong> </span>
                                </td>
                            </tr>
                        </table>
					</td>
				</tr>
				<tr>
					<td id="users_list_admin">
				   <?php
				   		$list_of = '';
						if(isset($_GET['list_of']) && !empty($_GET['list_of']))
						{
							$list_of = sanitize_text_field($_GET['list_of']);
						}
						if($list_of == "all_users")
						{
							echo user_list_view_all();
						}
						if($list_of == "my_list")
						{
							echo user_list_view_my_list();
						}
						else if($list_of)
						{
							echo user_list_view_role_wise($list_of);
						}
						else
						{
							echo user_list_view_all();
						}					
				   ?>
                    <?php wp_nonce_field( 'nonce_user_list_admin', 'wpnonce_user_list_reordering' ); ?>                   
					</td>
				</tr>
                <tr>
                	<td>
                    	<span style="cursor:pointer;" id="previous_links"><<< <?php _e("Previous", 'whizz'); ?> </span> &nbsp; &nbsp; &nbsp;
                        <span style="cursor:pointer;" id="next_links"><?php _e("Next", 'whizz'); ?> >>></span>
                    </td>
                    <?php wp_nonce_field( 'nonce_next_prev_link', 'wpnonce_user_next_prev_link' ); ?>                     
                </tr>
                <tr>
                	<td id="no_more_records">
                    </td>
                </tr>
                <tr>
                	<td id="paging_loader">
                        <?php $img_file_path = plugin_dir_url(__FILE__)."img/ajax-loader.gif" ?>
                        <img src="<?php echo esc_url($img_file_path); ?>" />
                    </td>
                </tr>
			</table>
            </form>
			<?php
		}
	} 
?>