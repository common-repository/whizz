<?php
if( !defined('WHIZZ_PLUGIN_URL') ){
	echo 'Direct call denied.';
	exit;
}
include_once('functions/functions.php');
function whizz_user_modification_users_func()
{
	?>
    <h1><?php _e("Plugin details here", 'whizz'); ?></h1>
    <?php
}
/* Register, localize, enqueue script and style START */
add_action( 'admin_enqueue_scripts', 'whizz_enqueue_and_register_my_scripts_users',0 );
function whizz_enqueue_and_register_my_scripts_users()
{
	wp_register_style( 'reg_custom_users_css_h', plugin_dir_url(__FILE__).'css/user_style-change-style.css');
	wp_register_script( 'reg_custom_users_js_h', plugin_dir_url(__FILE__).'js/custom-js-user-view.js',array('jquery','jquery-ui-core','jquery-ui-draggable','jquery-ui-droppable','jquery-ui-sortable') , false, true );
	$main_js_obj_props = array(
							'home_url' => esc_url(home_url()),
							'admin_url' => esc_url(admin_url()),
							'admin_ajax_url' => esc_url(admin_url()).'admin-ajax.php',
							'for_plugin_url' => plugin_dir_url( __FILE__ ),
							);
	wp_localize_script( 'reg_custom_users_js_h', 'main_js_obj_user', $main_js_obj_props );	
   	wp_enqueue_script( 'reg_custom_users_js_h' );
   	wp_enqueue_style( 'reg_custom_users_css_h' );
}
/* Register, localize, enqueue script and style END */ 
/* Define Constants START */
define('WHIZZ_USERS_LIST_PLUGIN_URL','?page=users-list');
/* Define Constants START */
add_action('wp_ajax_whizz_save_new_user_order_custom', 'whizz_save_new_user_order_custom_func' );
function whizz_save_new_user_order_custom_func()
{
	if(isset($_POST['user_order']) && !empty($_POST['user_order']) && isset($_POST['nonce_user_reorder']) && !empty($_POST['nonce_user_reorder']) && wp_verify_nonce( $_POST['nonce_user_reorder'], 'nonce_user_list_admin' ) && current_user_can('manage_options'))
	{
		$list_of = '';
		if(isset($_POST['list_of_u']) && !empty($_POST['list_of_u'])) {
		$list_of = sanitize_text_field($_POST['list_of_u']);
		}
		$user_order = stripslashes($_POST['user_order']);
		$user_order = json_decode( $user_order );
		$user_order = whizz_dv_plugin_order_object_to_array_users($user_order);
		update_option(get_current_user_id().'_user_order_whizz_'.$list_of,$user_order);
		wp_send_json_success( 'Success' );
	}
	else
	{
		wp_send_json_error();
	}
}
add_action('wp_ajax_whizz_save_item_per_page', 'whizz_save_item_per_page_func' );
function whizz_save_item_per_page_func()
{
	if(isset($_POST['item_per_page']) && !empty($_POST['item_per_page']) && intval($_POST['item_per_page'])  && isset($_POST['nonce_paging_item_per_page']) && !empty($_POST['nonce_paging_item_per_page']) && wp_verify_nonce( $_POST['nonce_paging_item_per_page'], 'nonce_item_per_page' ) && current_user_can('manage_options'))
	{
		   
		update_option(get_current_user_id().'_item_per_page', intval($_POST['item_per_page']));
		wp_send_json_success( 'Success' );
	}
	else
	{
		wp_send_json_error();
	}
}
add_action('wp_ajax_whizz_order_by_users', 'whizz_order_by_users_func' );
function whizz_order_by_users_func()
{
	if(isset($_POST['orderby']) && !empty($_POST['orderby'])  && isset($_POST['wpnonce_user_cap']) && !empty($_POST['wpnonce_user_cap']) && wp_verify_nonce( $_POST['wpnonce_user_cap'], 'nonce_heading_filter' )&& current_user_can('manage_options'))
	{
		$order = "ASC";
		$order_by = sanitize_text_field($_POST['orderby']);	
		if($order_by == "descending")
		{
			$order = "DESC";
		}
		$role = "";
		$args = "";
		$page_size = get_option(get_current_user_id().'_item_per_page');
		if(isset($_POST['list_of']) && !empty($_POST['list_of']) && sanitize_text_field($_POST['list_of']) == "all_users")
		{ 
			if(isset($_POST['search_set']) && !empty($_POST['search_set']) && sanitize_text_field($_POST['search_set']) =="yes" && isset($_POST['search_term']) && !empty($_POST['search_term']))
			{
				$args = array(
					'orderby'      		=> 'display_name',
					'order'        		=> $order,
					'number'       		=> $page_size,
					'search'         	=> '*'.esc_attr(sanitize_text_field($_POST['search_term'])).'*',
					'search_columns' 	=> array(
											'user_login',
											'user_nicename',
											'user_email',
										),
				 	);
			}
			else
			{
				$args = array(
					'orderby'      => 'display_name',
					'order'        => $order,
					'number'       => $page_size
				 );
			}
		}
		else
		{
			if(isset($_POST['search_set']) && !empty($_POST['search_set']) && sanitize_text_field($_POST['search_set']) =="yes" && isset($_POST['search_term']) && !empty($_POST['search_term']))
			{
				$search_term = sanitize_text_field($_POST['search_term']);
				$args = array(				
					'role'         		=> $list_of,
					'orderby'      		=> 'display_name',
					'order'        		=> $order,
					'number'       		=> $page_size,
					'search'         	=> '*'.esc_attr( $search_term ).'*',
					'search_columns' 	=> array(
											'user_login',
											'user_nicename',
											'user_email',
										),
				 	);
			}
			else
			{
				$args = array(
					'role'         => $list_of,
					'orderby'      => 'display_name',
					'order'        => $order,
					'number'       => $page_size
				 );
			}
		}
		$user_list_temp = get_users( $args );
		if(isset($_POST['view']) && !empty($_POST['view']) && sanitize_text_field($_POST['view']) == "list_view" && current_user_can('manage_options'))
		{
			$list = "<ul id='list_view_user' class='list-view-class'>";
			ob_start();
			foreach($user_list_temp as $user)
			{	
				$user_data_id = '';  
				if(isset($user->data->ID) && !empty($user->data->ID) && intval($user->data->ID)) 
				{
					$user_data_id = intval($user->data->ID);
				}
				?>
				<li class="li_inside_ul_list" id="user<?php echo $user_data_id; ?>">
					<input type="checkbox" name="chk_user[]" value="<?php echo $user_data_id; ?>" />
					<div class="user_avtar"><?php echo get_avatar($user_data_id); ?></div>
					<div class="user_name">      
					<?php $user_data = "user-edit.php?user_id=".$user_data_id ; ?>                        
                        <a href="<?php echo esc_url($user_data); ?>">
							<?php echo sanitize_text_field($user->data->display_name); ?>
						</a>
					</div>
					<div class="user_email"><?php echo sanitize_email($user->data->user_email); ?></div>
					<div class="user_description-new">
						<?php echo sanitize_text_field(get_the_author_meta( 'description', $user_data_id)); ?>
					</div>
					<div class="user_delete-user-button">
						<b>
						<?php $user_edit = "user-edit.php?user_id=".$user_data_id; ?>
						<a href="<?php esc_url($user_edit); ?>"><?php _e("Edit", 'whizz'); ?></a> &nbsp;
                          <?php
							if($user->roles[0] != "administrator")
							{
						?>
                        
                        <?php  
						$list_view = "";
						if(isset($_GET['list_of']) && !empty($_GET['list_of']))
						{ 
							$list_view =  sanitize_text_field($_GET['list_of']);
						}
						$get_view = "";
						if(isset($_GET['view']) && !empty($_GET['view']))
						{ 
							$get_view =  sanitize_text_field($_GET['view']);
						}
						
						$delete_user = esc_url(add_query_arg( array( 'action' => 'deleteu', 'list_of' => $list_view, 'view' =>$get_view, 'uid' => $user_data_id), WHIZZ_USERS_LIST_PLUGIN_URL)); ?>
                        <a href="<?php echo $delete_user; ?>"><?php _e("Delete", 'whizz'); ?></a>&nbsp;
                        <?php } ?>                       
					</b>
					</div>
					<input type="hidden" name="user_id_h" class="user_id_h" value="<?php echo $user_data_id; ?>" />
					<div class="clear"></div>
                </li>
				<?php
			}
			$list .= ob_get_clean();
			$list .="</ul>";
			wp_send_json_success($list);
		}		
		else
		{
			wp_send_json_success('No record found.');
		}
	}
	else
	{
		wp_send_json_error();
	}
}
add_action('wp_ajax_whizz_order_by_users_my_list', 'whizz_order_by_users_my_list_func' );
function whizz_order_by_users_my_list_func()
{
	if(isset($_POST['orderby']) && !empty($_POST['orderby']) && isset($_POST['wpnonce_user_cap']) && !empty($_POST['wpnonce_user_cap']) && wp_verify_nonce( $_POST['wpnonce_user_cap'], 'nonce_heading_filter' )&& current_user_can('manage_options'))
	{
		$order = "ASC";
		$post_order_by = sanitize_text_field($_POST['orderby']); 
		if($post_order_by == "descending")
		{
			$order = "DESC";
		}
		$role = "";
		$args = "";
		/* retrieve saved users and pass their ids to arguments*/
		$user_id_array = array();
		$saved_users = get_option(get_current_user_id().'_user_order_whizz_my_list');
		foreach($saved_users as $user)
		{
			$user_id_array[] = $user['id'];
		}
		$page_size = get_option(get_current_user_id().'_item_per_page');

        if(isset($_POST['search_set']) && !empty($_POST['search_set']) && sanitize_text_field($_POST['search_set']) =="yes" && isset($_POST['search_term']) && !empty($_POST['search_term']))		
		{
			$args = array(				
				'include'      		=> $user_id_array,
				'orderby'      		=> 'display_name',
				'order'        		=> $order,
				'number'       		=> $page_size,
				'search'         	=> '*'.esc_attr(sanitize_text_field($_POST['search_term'])).'*',
				'search_columns' 	=> array(
										'user_login',
										'user_nicename',
										'user_email',
									),
			 	);
		}
		else
		{
			$args = array(
				'include'      => $user_id_array,
				'orderby'      => 'display_name',
				'order'        => $order,
				'number'       => $page_size
			 );
		}
	
		$user_list_temp = get_users( $args );
		if(isset($_POST['view']) && !empty($_POST['view']) && sanitize_text_field($_POST['view']) == "list_view")
		{
			$post_view = sanitize_text_field($_POST['view']);
			$list = "<ul id='list_view_user' class='list-view-class'>";
			ob_start();
			foreach($user_list_temp as $user)
			{
				$user_data_id = '';  
				if(isset($user->data->ID) && !empty($user->data->ID) && intval($user->data->ID)) 
					{
						$user_data_id = intval($user->data->ID);
        			}
				?>
				<li class="li_inside_ul_list" id="user<?php echo $user_data_id; ?>">
					<input type="checkbox" name="chk_user[]" value="<?php echo $user_data_id; ?>" />
					<div class="user_avtar"><?php echo get_avatar($user_data_id); ?></div>
					<div class="user_name">		
                        <?php $user_edit = "user-edit.php?user_id=".$user_data_id; ?>
						<a href="<?php echo esc_url($user_edit); ?>">		
							<?php echo sanitize_text_field($user->data->display_name); ?>		
						</a>		
					</div>		
					<div class="user_email"><?php echo sanitize_email($user->data->user_email); ?></div>		
					<div class="user_description-new">		
						<?php echo sanitize_text_field(get_the_author_meta( 'description', $user_data_id)); ?>		
					</div>		
					<div class="user_delete-user-button">
						<b>
                        <?php  $user_edit_data = "user-edit.php?user_id=".$user_data_id ; ?>
						<a href="<?php echo esc_url($user_edit_data); ?>"><?php _e("Edit", 'whizz'); ?></a> &nbsp; 
                         <?php
							if($user->roles[0] != "administrator")
							{
								$list_view = "";
								if(isset($_GET['list_of']) && !empty($_GET['list_of']))
								{ 
									$list_view =  sanitize_text_field($_GET['list_of']);
								}
								$get_view = "";
								if(isset($_GET['view']) && !empty($_GET['view']))
								{ 
									$get_view =  sanitize_text_field($_GET['view']);
								}
						?>
						<?php $delete_user_records = esc_url(add_query_arg( array( 'action' => 'deleteu', 'list_of' => $list_view, 'view' =>$get_view, 'uid' => $user_data_id), WHIZZ_USERS_LIST_PLUGIN_URL)); ?><a href="<?php echo $delete_user_records; ?>"><?php _e("Delete", 'whizz'); ?></a>&nbsp;
                        <?php
							}
						?>
						</b>
					</div>
					<input type="hidden" name="user_id_h" class="user_id_h" value="<?php echo $user_data_id; ?>" />
					<div class="clear"></div>
                </li>
				<?php				
			}
			$list .= ob_get_clean();
			$list .="</ul>";
			wp_send_json_success($list);
		}		
		else
		{
			wp_send_json_success('No record found.');
		}
	}
	else
	{
		wp_send_json_error();
	}
}
function whizz_dv_plugin_order_object_to_array_users($data)
{
    if (is_array($data) || is_object($data))
    {
        $result = array();
        foreach ($data as $key => $value)
        {
            $result[$key] = whizz_dv_plugin_order_object_to_array_users($value);
        }
        return $result;
    }
    return $data;
}
function whizz_check_main_menu_whizz_plugin_users($menu_slug_check)
{
	global $menu;
	$flag=0;
	foreach($menu as $single_menu)
	{
		 if($single_menu[2] == $menu_slug_check)
		 {
			 $flag=1;
		 }
	}
	return $flag;
}
add_action('wp_ajax_whizz_paging_users', 'whizz_paging_users_func', 999 );
function whizz_paging_users_func()
{	
	if(isset($_POST['page_no']) && !empty($_POST['page_no']) && intval($_POST['page_no'])  && isset($_POST['nonce_next_prev_paging']) && !empty($_POST['nonce_next_prev_paging']) && wp_verify_nonce( $_POST['nonce_next_prev_paging'], 'nonce_next_prev_link') && current_user_can('manage_options'))
	{
		$page_no = intval($_POST['page_no']);
		$page_size = get_option(get_current_user_id().'_item_per_page');
		$view = '';
		if(isset($_POST['view']) && !empty($_POST['view'])) 
		{
			$view = sanitize_text_field($_POST['view']);
		}
		$role = "";
		$order = "ASC";
		$orderd = '';
		if(isset($_POST['orderd']) && !empty($_POST['orderd'])) {
		$orderd = sanitize_text_field($_POST['orderd']);
		}
		$position ='';
		if(isset($_POST['position']) && !empty($_POST['position'])) {
		$position = sanitize_text_field($_POST['position']);
		}			
			
		if(isset($_POST['orderby']) && !empty($_POST['orderby']) && sanitize_text_field($_POST['orderby']) == "descending")
		{
			$order = "DESC";
		}
		if(isset($_POST['list_of']) && !empty($_POST['list_of']) && sanitize_text_field($_POST['list_of']) == "all_users")
		{
			$role="";
		}
		else
		{
			$list_of = sanitize_text_field($_POST['list_of']);
			$role=$list_of;
		}
		$args ="";
		if(isset($role) && !empty($role))
		{
			if(isset($_POST['search_set']) && !empty($_POST['search_set']) && sanitize_text_field($_POST['search_set']) =="yes" && isset($_POST['search_term']) && !empty($_POST['search_term']))
			{
				$args = array(				
					'role'         		=> $role,
					'orderby'      		=> 'display_name',
					'order'        		=> $order,
					'search'         	=> '*'.esc_attr(sanitize_text_field($_POST['search_term'])).'*',
					'search_columns' 	=> array(
											'user_login',
											'user_nicename',
											'user_email',
										),
				 	);
			}
			else
			{
				$args = array(
					'role'         => $role,
					'orderby'      => 'display_name',
					'order'        => $order,
				 );
			}
		}
		else
		{
		if(isset($_POST['search_set']) && !empty($_POST['search_set']) && sanitize_text_field($_POST['search_set']) =="yes" && isset($_POST['search_term']) && !empty($_POST['search_term']))			
			{
				$args = array(				
					'orderby'      		=> 'display_name',
					'order'        		=> $order,
					'search'         	=> '*'.esc_attr( sanitize_text_field($_POST['search_term']) ).'*',
					'search_columns' 	=> array(
											'user_login',
											'user_nicename',
											'user_email',
										),
				 	);
			}
			else
			{
				$args = array(
					'orderby'      => 'display_name',
					'order'        => $order,
				 );
			}
		}
		$list_of = '';
		if(isset($_POST['list_of']) && !empty($_POST['list_of'])) {
		$list_of = sanitize_text_field($_POST['list_of']);
		}		
		$ordered_appended_array = whizz_list_user_paged($args, $page_no, $page_size, $orderd, $list_of, $position);
		if(isset($ordered_appended_array) && !empty($ordered_appended_array))
		{
			if(isset($view) && !empty($view) && sanitize_text_field($view) == "list_view" && current_user_can('manage_options'))
			{ 
				$list = "<ul id='list_view_user' class='list-view-class'>";									
				ob_start();
				foreach($ordered_appended_array as $user)
				{
					$user_data_id = '';  
					if(isset($user->data->ID) && !empty($user->data->ID) && intval($user->data->ID)) 
					{
						$user_data_id = intval($user->data->ID);
        			}
					?>
					<li class="li_inside_ul_list" id="user<?php echo $user_data_id; ?>">
						<input type="checkbox" name="chk_user[]" value="<?php echo $user_data_id; ?>" />		
						<div class="user_avtar"><?php echo get_avatar($user_data_id); ?></div>		
						<div class="user_name">		
                            <?php $user_link = "user-edit.php?user_id=".$user_data_id ; ?>
							<a href="<?php echo esc_url($user_link); ?>">		
								<?php echo sanitize_text_field($user->data->display_name); ?>		
							</a>		
						</div>		
						<div class="user_email"><?php echo sanitize_email($user->data->user_email); ?></div>		
						<div class="user_description-new">		
							<?php echo sanitize_text_field(get_the_author_meta( 'description', $user_data_id)); ?>		
						</div>		
						<div class="user_delete-user-button">
							<b>

                            <?php $user_edit = "user-edit.php?user_id=".$user_data_id ; ?>
							<a href="<?php echo esc_url($user_edit); ?>"><?php _e("Edit", 'whizz'); ?></a> &nbsp; 
                             <?php
								if($user->roles[0] != "administrator")
								{ 
								$list_view = "";
								if(isset($_GET['list_of']) && !empty($_GET['list_of']))
								{ 
									$list_view =  sanitize_text_field($_GET['list_of']);
								}
								$get_view = "";
								if(isset($_GET['view']) && !empty($_GET['view']))
								{ 
									$get_view =  sanitize_text_field($_GET['view']);
								}
								$delete_user = esc_url(add_query_arg( array( 'action' => 'deleteu', 'list_of' => $list_view, 'view' =>$get_view, 'uid' => $user_data_id), WHIZZ_USERS_LIST_PLUGIN_URL)); 
							?> 

							 <a href="<?php echo $delete_user; ?>"><?php _e("Delete", 'whizz'); ?></a>&nbsp;
                             <?php
								}
							?>
							</b>
						</div>
						<input type="hidden" name="user_id_h" class="user_id_h" value="<?php echo $user_data_id; ?>" />
					<div class="clear"></div></li>
					<?php
				} 
				$list .= ob_get_clean();
				$list .="</ul>";
				wp_send_json_success($list);
			}
		}
	}
	wp_send_json_error();
}
function whizz_list_user_paged($args, $page_no, $page_size, $orderd, $user_role, $position)
{
	$user_list_temp = get_users( $args );
	if(isset($orderd) && !empty($orderd) && sanitize_text_field($orderd) == "yes"  && isset($_POST['nonce_next_prev_paging']) && !empty($_POST['nonce_next_prev_paging']) && wp_verify_nonce( $_POST['nonce_next_prev_paging'], 'nonce_next_prev_link' )&& current_user_can('manage_options')) /*  currently orderd so no need to get the modified order */
	{
		if(isset($position) && !empty($position) && sanitize_text_field($position) == "next")
		{
			$ordered_appended_array = array();
			$record_bypass = $page_no * $page_size;		
			$i = 1;
			foreach($user_list_temp as $ult_key => $user)
			{
				if(isset($i) && !empty($i) && intval($i) && $i > $record_bypass)
				{ 
					if($i <= $record_bypass + $page_size)
					{
 						$ordered_appended_array[] = $user;
					}
					else
					{
						break;
					}
					$i++;
				}
				else
				{
					$i++;
				}
			} 
			return $ordered_appended_array;
		}
		else if(isset($position) && !empty($position) && sanitize_text_field($position) == "previous")
		{
			if(isset($page_no) && !empty($page_no) && intval($page_no) && $page_no > 1)
			{
				$page_no = $page_no -2;
				$ordered_appended_array = array();
				$record_bypass = $page_no * $page_size;		
				$i = 1;
				foreach($user_list_temp as $ult_key => $user)
				{
					if(isset($i) && !empty($i) && intval($i) && $i > $record_bypass)
					{
						if($i <= $record_bypass + $page_size)
						{
							$ordered_appended_array[] = $user;
						}
						else
						{
							break;
						}
						$i++;
					}
					else
					{
						$i++;
					}
				}
				return $ordered_appended_array;
			}
			else
			{
				return false;
			}
		}
	}
	else if(current_user_can('manage_options'))
	{
		$user_order = get_option(get_current_user_id().'_user_order_whizz_'.$user_role);
		
		$ordered_appended_array = array();
		if(isset($user_order) && !empty($user_order))
		{
			foreach($user_order as $order_key => $order_value)
			{
				foreach($user_list_temp as $ult_key => $user)
				{
					if(isset($user->data->ID) && !empty($user->data->ID) && intval($user->data->ID)){ 
					$user_data_id = intval($user->data->ID);
					}
					if(isset($order_key) && !empty($order_key) && $order_key == $user_data_id."ID")
					{
						$ordered_appended_array[] = $user;
						unset($user_list_temp[$ult_key]);
					}
				}
			}
		}
		if(isset($user_list_temp) && !empty($user_list_temp))
		{
			foreach($user_list_temp as $user)
			{
				$ordered_appended_array[] = $user;
			}
		}		
		if(isset($position) && !empty($position) && sanitize_text_field($position) == "next")
		{
			$ordered_array = array();
			$record_bypass = $page_no * $page_size;		
			$i = 1;
			foreach($ordered_appended_array as $ult_key => $user)
			{
				if(isset($i) && !empty($i) && intval($i) && $i > $record_bypass)
				{
					if($i <= $record_bypass + $page_size)
					{
 						$ordered_array[] = $user;
					}
					else
					{
						break;
					}
					$i++;
				}
				else
				{
					$i++;
				}
			}
			return $ordered_array;
		}
		else if(isset($position) && !empty($position) && sanitize_text_field($position) == "previous")
		{
			if(isset($page_no) && !empty($page_no) && intval($page_no) && $page_no > 1)
			{
				$page_no = $page_no -2;
				$ordered_array = array();
				$record_bypass = $page_no * $page_size;		
				$i = 1;
				foreach($ordered_appended_array as $ult_key => $user)
				{
					if(isset($i) && !empty($i) && intval($i) && $i > $record_bypass)
					{
						if($i <= $record_bypass + $page_size)
						{
							$ordered_array[] = $user;
						}
						else
						{
							break;
						}
						$i++;
					}
					else
					{
						$i++;
					}
				}
				return $ordered_array;
			}
			else
			{
				return false;
			}
		}		
	}
	return false;
}
add_action('wp_ajax_whizz_paging_users_my_list', 'whizz_paging_users_my_list_func', 999 );
function whizz_paging_users_my_list_func()
{	
	if(isset($_POST['page_no']) && !empty($_POST['page_no']) && intval($_POST['page_no']) && isset($_POST['nonce_paging_item_per_page']) && !empty($_POST['nonce_paging_item_per_page']) && wp_verify_nonce( $_POST['nonce_paging_item_per_page'], 'nonce_item_per_page' ) && current_user_can('manage_options'))
	{
		$page_no = intval($_POST['page_no']);
		$page_size = get_option(get_current_user_id().'_item_per_page');
		$view = '';
		if(isset($_POST['view']) && !empty($_POST['view'])) 
		{
			$view = sanitize_text_field($_POST['view']);
		}
		$role = "";		
		$order = "ASC";
		$orderd = '';
		if(isset($_POST['orderd']) && !empty($_POST['orderd'])) {
		$orderd = sanitize_text_field($_POST['orderd']);
		}
		$position ='';
		if(isset($_POST['position']) && !empty($_POST['position'])) {
		$position = sanitize_text_field($_POST['position']);
		}
		
		if(isset($_POST['orderby']) && !empty($_POST['orderby']) && sanitize_text_field($_POST['orderby']) == "descending")
		{
			$order = "DESC";
		}
		
		$user_id_array = array();
		$saved_users = get_option(get_current_user_id().'_user_order_whizz_my_list');
		foreach($saved_users as $user)
		{
			$user_id_array[] = $user['id'];
		}
		
		$args ="";		
		if(isset($_POST['search_set']) && !empty($_POST['search_set']) && sanitize_text_field($_POST['search_set']) =="yes" && isset($_POST['search_term']) && !empty($_POST['search_term']))
		{
			$args = array(				
				'include'      		=> $user_id_array,
				'orderby'      		=> 'display_name',
				'order'        		=> $order,
				'search'         	=> '*'.esc_attr( sanitize_text_field($_POST['search_term']) ).'*',
				'search_columns' 	=> array(
										'user_login',
										'user_nicename',
										'user_email',
									),
			 	);
		}
		else
		{
			$args = array(
				'include'      => $user_id_array,
				'orderby'      => 'display_name',
				'order'        => $order,
			 );
		}
	
		if(isset($_POST['list_of']) && !empty($_POST['list_of'])) {
		$user_role = sanitize_text_field($_POST['list_of']);
		}
		$ordered_appended_array = list_user_paged($args, $page_no, $page_size, $orderd, $user_role, $position);
		if(isset($ordered_appended_array) && !empty($ordered_appended_array))
		{ 
			if($view == "list_view" && current_user_can('manage_options'))
			{
				$list = "<ul id='list_view_user' class='list-view-class'>";
				ob_start();
				foreach($ordered_appended_array as $user)
				{
					$user_data_id = '';  
					if(isset($user->data->ID) && !empty($user->data->ID) && intval($user->data->ID)) 
						{
							$user_data_id = intval($user->data->ID);
						}
					?>
					<li class="li_inside_ul_list" id="user<?php echo $user_data_id; ?>">
						<input type="checkbox" name="chk_user[]" value="<?php echo $user_data_id; ?>" />
						<div class="user_avtar"><?php echo get_avatar($user_data_id); ?></div>
						<div class="user_name">
                            <?php $user_edit = "user-edit.php?user_id=".$user_data_id; ?>
                            <a href="<?php echo esc_url($user_edit); ?>">
                                <?php echo sanitize_text_field($user->data->display_name); ?>
                            </a>
						</div>
						<div class="user_email"><?php echo sanitize_email($user->data->user_email); ?></div>
						<div class="user_description-new">
							<?php echo sanitize_text_field(get_the_author_meta( 'description', $user_data_id)); ?>
						</div>
						<div class="user_delete-user-button">
							<b>
							<a href="<?php echo esc_url($user_edit); ?>"><?php _e("Edit", 'whizz'); ?></a> &nbsp; 
                             <?php
								if($user->roles[0] != "administrator")
								{
								$get_view ='';
								if(isset($_GET['view']) && !empty($_GET['view'])) 
								{
								$get_view = sanitize_text_field($_GET['view']);
								}
								$get_list_of ='';
								if(isset($_GET['list_of']) && !empty($_GET['list_of'])) {
								$get_list_of = sanitize_text_field($_GET['list_of']);
								}
								$delete_user = esc_url(add_query_arg( array( 'action' => 'deleteu', 'list_of' => $get_list_of, 'view' =>$get_view, 'uid' => $user_data_id), WHIZZ_USERS_LIST_PLUGIN_URL));									
							?>
							<a href="<?php echo $delete_user; ?>"><?php _e("Delete", 'whizz'); ?></a> &nbsp;
                            
                             <?php
								}							
							?>
							</b>
						</div>
						<input type="hidden" name="user_id_h" class="user_id_h" value="<?php echo $user_data_id; ?>" />
					<div class="clear"></div></li>
					<?php
				} 
				$list .= ob_get_clean();
				$list .="</ul>";
				wp_send_json_success($list);
			}			
		}
	}
	wp_send_json_error();
}
add_action('wp_ajax_whizz_search_users_admin', 'whizz_search_users_admin_func', 999 );
function whizz_search_users_admin_func()
{
	if(isset($_POST['search_term']) && !empty($_POST['search_term']) && isset($_POST['nonce_user_search_submit']) && !empty($_POST['nonce_user_search_submit']) && wp_verify_nonce( $_POST['nonce_user_search_submit'], 'nonce_search_submit' ) && current_user_can('manage_options'))
	{
		$page_size = get_option(get_current_user_id().'_item_per_page');
		global $wpdb;
		$users ="";
		$list_of = '';
		if(isset($_POST['list_of']) && !empty($_POST['list_of']))
		{
			$list_of = sanitize_text_field($_POST['list_of']); 
			if($list_of == "all_users")
			{
				$users = new WP_User_Query( array(
					'search'         => '*'.esc_attr( sanitize_text_field($_POST['search_term']) ).'*',
					'search_columns' => array(
						'user_login',
						'user_nicename',
						'user_email',
						'user_url',
					),
					'number'       => $page_size
				));
			}
			else if($list_of == "my_list")
			{
				
				$user_id_array = array();
				$saved_users = get_option(get_current_user_id().'_user_order_whizz_my_list');
				foreach($saved_users as $user)
				{
					$user_id_array[] = $user['id'];
				}
				
				$users = new WP_User_Query( array(
					'include'      		=> $user_id_array,
					'search'         	=> '*'.esc_attr( sanitize_text_field($_POST['search_term']) ).'*',
					'search_columns' 	=> array(
						'user_login',
						'user_nicename',
						'user_email',
						'user_url',
					),
					'number'       		=> $page_size
				));
			}
			else
			{
				$users = new WP_User_Query( array(
					'search'         	=> '*'.esc_attr( sanitize_text_field($_POST['search_term']) ).'*',
					'search_columns'	=> array(
						'user_login',
						'user_nicename',
						'user_email',
						'user_url',
					),
					'number'			=> $page_size,
					'role'				=> $list_of
				));
			}
		}
		else
		{
			$users = new WP_User_Query( array(
				'search'         => '*'.esc_attr( sanitize_text_field($_POST['search_term']) ).'*',
				'search_columns' => array(
					'user_login',
					'user_nicename',
					'user_email',
					'user_url',
				),
				'number'       => $page_size
			));
			
		}		
		$users_found = $users->get_results();		
		if(isset($users_found) && !empty($users_found))
		{
			if(isset($_POST['view']) && !empty($_POST['view']) && sanitize_text_field($_POST['view']) == "list_view" && current_user_can('manage_options'))			
			{
				$list = "<ul id='list_view_user' class='list-view-class'>";
				ob_start();
				foreach($users_found as $user)
				{
					$user_data_id = '';  
					if(isset($user->data->ID) && !empty($user->data->ID) && intval($user->data->ID)) 
						{
							$user_data_id = intval($user->data->ID);
						}
					?>
					<li class="li_inside_ul_list" id="user<?php echo $user_data_id; ?>">
						<input type="checkbox" name="chk_user[]" value="<?php echo $user_data_id; ?>" />		
						<div class="user_avtar"><?php echo get_avatar($user_data_id); ?></div>		
						<div class="user_name">		
                            <?php $user_edit = "user-edit.php?user_id=".$user_data_id; ?>
                            <a href="<?php echo esc_url($user_edit); ?>">
                                <?php echo sanitize_text_field($user->data->display_name); ?>
                            </a>		
						</div>		
						<div class="user_email"><?php echo sanitize_email($user->data->user_email); ?></div>		
						<div class="user_description-new">		
							<?php echo sanitize_text_field(get_the_author_meta( 'description', $user_data_id)); ?>		
						</div>		
						<div class="user_delete-user-button">
							<b>
							<a href="<?php echo esc_url($user_edit); ?>"><?php _e("Edit", 'whizz'); ?></a> &nbsp;
                             <?php
								if($user->roles[0] != "administrator")
								{
									$get_list_of = '';
									if(isset($_GET['list_of']) && !empty($_GET['list_of'])) 
									{
									$get_list_of = sanitize_text_field($_GET['list_of']); 
									}    
									$view_list_of = '';
									if(isset($_GET['view']) && !empty($_GET['view'])) 
									{
									$view_list_of = sanitize_text_field($_GET['view']);
									}
								$delete_user = esc_url(add_query_arg( array( 'action' => 'deleteu', 'list_of' => $get_list_of, 'view' =>$view_list_of, 'uid' => $user_data_id), WHIZZ_USERS_LIST_PLUGIN_URL));																		
							?>
							<a href="<?php echo $delete_user; ?>"><?php _e("Delete", 'whizz'); ?></a> &nbsp; 
                             <?php
								}
							?>
							</b>
						</div>
						<input type="hidden" name="user_id_h" class="user_id_h" value="<?php echo $user_data_id; ?>" />
						<div class="clear"></div>
					</li>
					<?php				
				} 
				$list .= ob_get_clean();
				$list .="</ul>";
				wp_send_json_success($list);
			} 
			elseif(current_user_can('manage_options')) {
				$list = "<ul id='list_view_user' class='list-view-class'>";
				ob_start();
				foreach($users_found as $user)
				{
					$user_data_id = '';  
					if(isset($user->data->ID) && !empty($user->data->ID) && intval($user->data->ID)) 
						{
							$user_data_id = intval($user->data->ID);
						}
					?>
					<li class="li_inside_ul_list" id="user<?php echo $user_data_id; ?>">
						<input type="checkbox" name="chk_user[]" value="<?php echo $user_data_id; ?>" />		
						<div class="user_avtar"><?php echo get_avatar($user_data_id); ?></div>		
						<div class="user_name">		
                            <?php $user_edit = "user-edit.php?user_id=".$user_data_id; ?>
                            <a href="<?php echo esc_url($user_edit); ?>">
                                <?php echo sanitize_text_field($user->data->display_name); ?>
                            </a>		
						</div>		
						<div class="user_email"><?php echo sanitize_email($user->data->user_email); ?></div>		
						<div class="user_description-new">		
							<?php echo sanitize_text_field(get_the_author_meta( 'description', $user_data_id)); ?>		
						</div>		
						
                                <div class="user_delete-user-button">
                                    <b>
                                    <a href="<?php echo esc_url($user_edit); ?>"><?php _e("Edit", 'whizz'); ?></a> &nbsp;
                                     <?php
                                        if($user->roles[0] != "administrator")
                                        {
                                            $get_list_of = '';
                                            if(isset($_GET['list_of']) && !empty($_GET['list_of'])) 
                                            {
                                            $get_list_of = sanitize_text_field($_GET['list_of']); 
                                            }    
                                            $view_list_of = '';
                                            if(isset($_GET['view']) && !empty($_GET['view'])) 
                                            {
                                            $view_list_of = sanitize_text_field($_GET['view']);
                                            }
                                        $delete_user = esc_url(add_query_arg( array( 'action' => 'deleteu', 'list_of' => $get_list_of, 'view' =>$view_list_of, 'uid' => $user_data_id), WHIZZ_USERS_LIST_PLUGIN_URL));																		
                                    ?>
                                    <a href="<?php echo $delete_user; ?>"><?php _e("Delete", 'whizz'); ?></a> &nbsp; 
                                     <?php
                                        }
                                    ?>
                                    </b>
                                </div>
                                <input type="hidden" name="user_id_h" class="user_id_h" value="<?php echo $user_data_id; ?>" />
                        
						<div class="clear"></div>
					</li>
					<?php				
				} 
				$list .= ob_get_clean();
				$list .="</ul>";
				wp_send_json_success($list);
			}
		}
		else
		{
			wp_send_json_success("No record found.");
		}
		wp_send_json_error();
	}
}
add_action('wp_ajax_whizz_reset_to_default_order', 'whizz_reset_to_default_order_func' );
function whizz_reset_to_default_order_func()
{
	if(isset($_POST['list_of']) && !empty($_POST['list_of']) && isset($_POST['nonce_user_reset']) && !empty($_POST['nonce_user_reset']) && wp_verify_nonce( $_POST['nonce_user_reset'], 'nonce_user_default_order' ) && current_user_can('manage_options'))
	{
		update_option(get_current_user_id().'_user_order_whizz_'.sanitize_text_field($_POST['list_of']),'');
		wp_send_json_success( 'Success' );
	}
	else
	{
		wp_send_json_error();
	}
}
function whizz_validate_user_activation($user, $password)
{
	if(!is_wp_error( $user ) && current_user_can('manage_options'))
	{
		$res = whizz_check_user_authenticattion_status_admin(intval($user->data->ID));
		if(isset($res) && !empty($res) && sanitize_text_field($res) == "not_exists")
		{
		}
		else if(isset($res) && !empty($res) && sanitize_text_field($res) == "yes")
		{
			// do nothing
		}
		else
		{
			$user = new WP_Error( 'denied', __("<strong>ERROR</strong>: User deactivated.") );
		}
	}
    return $user;
}
add_filter( 'wp_authenticate_user', 'whizz_validate_user_activation', 10, 3 );