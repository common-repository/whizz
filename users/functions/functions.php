<?php
function user_role_count_assoc_array()
{
  	if(current_user_can('manage_options'))
	{	
		$user_list = get_users();
		$roles_and_count['administrator']= 0;
		foreach($user_list as $user)
		{
			if(array_key_exists($user->roles[0],$roles_and_count))
			{
				$roles_and_count[$user->roles[0]] = $roles_and_count[$user->roles[0]] + 1;
			}
			else
			{
				$roles_and_count[$user->roles[0]] = 1;
			}
		}
		return $roles_and_count;
   }
}
function user_list_view_all()
{
	if(current_user_can('manage_options'))
	{
		$list = "<ul id='list_view_user' class='list-view-class'>";
		$user_list_temp = get_users();	
		$user_order = get_option(get_current_user_id().'_user_order_whizz_all_users');
		$ordered_appended_array = array();
		if(isset($user_order) && !empty($user_order))
		{
			foreach($user_order as $order_key => $order_value)
			{
				foreach($user_list_temp as $ult_key => $user)
				{
					if(isset($order_key) && !empty($order_key) && intval($user->data->ID) && $order_key == $user->data->ID."ID")
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
		$page_size = get_option(get_current_user_id().'_item_per_page');
		if(empty($page_size))
		{
			$page_size = 5;
		}
		ob_start();
		$i=1;
		foreach($ordered_appended_array as $user)
		{
			if(isset($i) && !empty($i) && intval($i) > $page_size)
			{
				break;
			}
			else if(current_user_can('manage_options'))
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
					<?php 
					$user_edit = "user-edit.php?user_id=".$user_data_id;  
					?>
					<a href="<?php echo esc_url($user_edit); ?>">
					<?php echo sanitize_text_field($user->data->display_name); ?>
					</a>
					</div>
					<div class="user_email"><?php echo sanitize_email($user->data->user_email); ?></div>
					<div class="user_description-new">
						<?php echo sanitize_text_field(get_the_author_meta( 'description', $user_data_id)); ?>
					</div>
				  
					<div class="user_delete-user-button">
						<strong>
						<a href="<?php echo esc_url($user_edit); ?>"><?php _e("Edit", 'whizz'); ?></a>&nbsp; 
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
									$delete_user = esc_url(add_query_arg( array( 'action' => 'deleteu', 'list_of' => $list_view, 'view' =>$get_view, 'uid' => $user_data_id), WHIZZ_USERS_LIST_PLUGIN_URL)); ?>				
								
									<a href="<?php echo $delete_user; ?>"><?php _e("Delete", 'whizz'); ?></a> &nbsp; 
								<?php
							}					
						?>
						</strong>
					</div>
					<input type="hidden" name="user_id_h" class="user_id_h" value="<?php echo $user_data_id; ?>" />
					
				<div class="clear"></div></li>
				<?php
				$i++;
			}
		}
		$list .= ob_get_clean();
		$list .="</ul>";
		return $list;
	}
}
function user_grid_view_all()
{
	if(current_user_can('manage_options'))
	{
		$list = "<ul id='list_view_user' class='grid-view-class'>";
		$user_list_temp = get_users();	
		$user_order = get_option(get_current_user_id().'_user_order_whizz_all_users');
		$ordered_appended_array = array();
		if(isset($user_order) && !empty($user_order))
		{
			foreach($user_order as $order_key => $order_value)
			{
				foreach($user_list_temp as $ult_key => $user)
				{
					if(isset($user->data->ID) && !empty($user->data->ID) && intval($user->data->ID) && $order_key == $user->data->ID."ID")
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
		$page_size = get_option(get_current_user_id().'_item_per_page');
		if(empty($page_size))
		{
			$page_size = 5;
		}
		ob_start();
		$i=1;
		foreach($ordered_appended_array as $user)
		{
			if(isset($i) && !empty($i) && intval($i) && $i > $page_size)
			{
				break;
			}
			elseif(current_user_can('manage_options'))
			{	
				$user_data_id = '';  
				if(isset($user->data->ID) && !empty($user->data->ID) && intval($user->data->ID)) 
				{
					$user_data_id = intval($user->data->ID);
				}		
				?>
				<li class="li_inside_ul_list" id="user<?php echo $user_data_id; ?>">	
				<input class="list-view-check-button-new" type="checkbox" name="chk_user[]" value="<?php echo $user_data_id; ?>" />
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
							<strong>
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
										$delete_user = esc_url(add_query_arg( array( 'action' => 'deleteu', 'list_of' => $list_view, 'view' =>$get_view, 'uid' => $user_data_id), WHIZZ_USERS_LIST_PLUGIN_URL)); ?>				
		
										<a href="<?php echo $delete_user; ?>"><?php _e("Delete", 'whizz'); ?></a> &nbsp;	
							 <?php
								}						
							?>
							</strong>
						</div>
						 <input type="hidden" name="user_id_h" class="user_id_h" value="<?php echo $user_data_id; ?>" />
						
				</li>
				<?php
				$i++;			
			}
		}
		$list .= ob_get_clean();
		$list .="</ul>";
		return $list;
	}
}
function user_grid_view_role_wise($user_role)
{
	if(current_user_can('manage_options'))
	{
		$list = "<ul id='list_view_user' class='grid-view-class'>";
		$args = array(
			'role'         => $user_role,
		 );
		$user_list_temp = get_users( $args );	
		$user_order = get_option(get_current_user_id().'_user_order_whizz_'.$user_role);
		$ordered_appended_array = array();
		if(isset($user_order) && !empty($user_order))
		{
			foreach($user_order as $order_key => $order_value)
			{
				foreach($user_list_temp as $ult_key => $user)
				{
					if(isset($order_key) && !empty($order_key) && intval($user->data->ID) && $order_key == $user->data->ID."ID")
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
		$page_size = get_option(get_current_user_id().'_item_per_page');
		if(empty($page_size))
		{
			$page_size = 5;
		}
		ob_start();
		$i=1;
		foreach($ordered_appended_array as $user)
		{
			if(isset($i) && !empty($i) && intval($i) && $i > $page_size)
			{
				break;
			}
			elseif(current_user_can('manage_options'))
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
								<strong>
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
								</strong>
							</div>
							 <input type="hidden" name="user_id_h" class="user_id_h" value="<?php echo $user_data_id; ?>" />
					 
			<div class="clear"></div>	</li>
				<?php
				$i++;		
			}
		}
		$list .= ob_get_clean();
		$list .="</ul>";
		return $list;
	}
}
function user_list_view_role_wise($user_role)
{
	if(current_user_can('manage_options'))	
	{
		$list = "<ul id='list_view_user' class='list-view-class'>";
		$args = array(
			'role'         => $user_role,
		 );
		$user_list_temp = get_users( $args );
		$user_order = get_option(get_current_user_id().'_user_order_whizz_'.$user_role);
		$ordered_appended_array = array();
		if(isset($user_order) && !empty($user_order))
		{
			foreach($user_order as $order_key => $order_value)
			{
				foreach($user_list_temp as $ult_key => $user)
				{
					if(isset($order_key) && !empty($order_key) && intval($user->data->ID) && $order_key == $user->data->ID."ID")
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
		$page_size = get_option(get_current_user_id().'_item_per_page');
		if(empty($page_size))
		{
			$page_size = 5;
		}
		ob_start();
		$i=1;
		foreach($ordered_appended_array as $user)
		{
			if(isset($i) && !empty($i) && intval($i) > $page_size)
			{
				break;
			}
			elseif(current_user_can('manage_options'))
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
					<a href="<?php echo esc_url($user_edit); ?>"><?php echo sanitize_text_field($user->data->display_name); ?></a>
					</div>
					<div class="user_email"><?php echo sanitize_email($user->data->user_email); ?></div>
					<div class="user_description-new">
						<?php echo sanitize_text_field(get_the_author_meta( 'description', $user->data->ID)); ?>
					</div>
					
							<div class="user_delete-user-button">
								<strong>
								<a href="<?php echo esc_url($user_edit); ?>"><?php _e("Edit", 'whizz'); ?></a>&nbsp;
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
								<a href="<?php echo $delete_user; ?>"><?php _e("Delete", 'whizz'); ?></a> &nbsp; 
								 <?php
									}
								?>
								</strong>
							</div>
							 <input type="hidden" name="user_id_h" class="user_id_h" value="<?php echo $user_data_id; ?>" />
							
				<div class="clear"></div>  </li> 
				<?php
				$i++;
			}
		}
		$list .= ob_get_clean();
		$list .="</ul>";
		return $list;
	}
}
function whizz_check_user_authenticattion_status_admin($user_id)
{
	$status = get_user_meta($user_id, 'user_authenticattion_status', true); 
	if(empty($status))
	{
		return 'not_exists';
	}
	else
	{
		return $status;
	}
}
?>