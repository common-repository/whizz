<?php
	function whizz_plugin_list_view()
	{
		if(current_user_can('manage_options')) 
			{
				$list = "<ul id='list_view_plugin'>";
				$plugin_list_temp = get_plugins();
				$plugin_order = get_option(get_current_user_id().'_plugin_order_whizz_all_plugins');
				$ordered_appended_array = array();
				if(isset($plugin_order) && !empty($plugin_order))
				{
					foreach($plugin_order as $order_key => $order_value)
					{
						if(array_key_exists($order_key,$plugin_list_temp))
						{
							$ordered_appended_array[$order_key] = $order_value;
							unset($plugin_list_temp[$order_key]);
						}
					}
				}
				if(isset($plugin_list_temp) && !empty($plugin_list_temp))
				{
					foreach($plugin_list_temp as $remaining_key => $remaining_value)
					{
						$ordered_appended_array[$remaining_key] = $remaining_value;
					}
				}
				ob_start();
		
				foreach($ordered_appended_array as $plugin_key => $plugin_value)
				{
					$plugin_key_s = sanitize_text_field($plugin_key);
					$plugin_name_s = trim(sanitize_text_field($plugin_value['Name']));
					?>
		  
					<li class="li_inside_ul_list" id="<?php echo $plugin_key_s; ?>">
						<div class="main_div_inside_li_list">
							<div class="span6 col-md-6">
							<div class="plugin_name_list">
							<input type="checkbox" name="selected_checkboxes[]" value="<?php echo $plugin_key_s; ?>" />
							 <input type="hidden" name="selected_<?php echo $plugin_key_s; ?>" value="<?php echo $plugin_name_s; ?>" />
							<?php 
								echo $plugin_name_s;
							?>
							</div>
							<ul>
							<li>
									<div class="plugin_activate_list">
										<?php 
										$get_value = "";
										if(isset($_GET['list_of']) && !empty($_GET['list_of']))
										{
											$get_value = sanitize_text_field($_GET['list_of']);
										}
		
										if ( is_plugin_active($plugin_key) ) 
										{
											$is_plugin_active =  wp_nonce_url(add_query_arg( array( 'action' => 'deactivatep', 'ppath' => $plugin_key, 'view'=>'list_view', 'list_of'=>$get_value), WHIZZ_PLUGINS_LIST_PLUGIN_URL), 'nonce_deactivatep', 'nonce_is_plugin_active');
											echo "<a href='".$is_plugin_active."'>".__("Deactivate",'whizz')."</a>";
										} 
										else
										{
											$is_plugin_deactive =  wp_nonce_url(add_query_arg( array( 'action' => 'activatep', 'ppath' => $plugin_key, 'view'=>'list_view', 'list_of'=>$get_value), WHIZZ_PLUGINS_LIST_PLUGIN_URL), 'nonce_activatep', 'nonce_is_plugin_deactive');
											echo "<a href='".$is_plugin_deactive."'>".__("Activate",'whizz')."</a>";
										}
										
										?>
									</div>
								</li>						
								<li>
									<?php
										$plugin_edit = esc_url("plugin-editor.php?file=".$plugin_key); 
										echo "<a href='".$plugin_edit."'>".__("Edit",'whizz')."</a>";
									?>
								</li>
								<li>                        	
									<?php
									if( strstr($plugin_key, 'whizz-plugin') == false)
									{
										$plugin_delete =  esc_url(add_query_arg( array( 'action' => 'deletep', 'ppath' => $plugin_key, 'plugin'=>$plugin_value['Name'], 'view'=>'list_view', 'list_of'=>$get_value), WHIZZ_PLUGINS_LIST_PLUGIN_URL));
										echo "<a href='".$plugin_delete."'>".__("Delete",'whizz')."</a>";
									}
									?> 
								</li>
							   </ul>
							</div>
								<div class="span6 col-md-6">
							<div class="plugin_desc_list">
							<?php 
								echo sanitize_text_field($plugin_value['Description']);
							?>
							</div>
							<ul>
							<li>
							<div class="plugin_version_list"> 
							<?php
								echo sanitize_text_field($plugin_value['Version']);
							?>
							</div>
							</li> | <li>
							<div class="plugin_author_name_list">
							<?php 
								echo sanitize_text_field($plugin_value['AuthorName']);
							?>
							</div>
							</li> | <li>
							<div class="plugin_uri_list">
							<?php 
								$more_detail = esc_url($plugin_value['PluginURI']);
								echo "<a href='".$more_detail."'>".__("More Details",'whizz')."</a>";
							?>
							</div>
							</li>
							</ul>
							</div>
						</div>
						<div class="clear"></div>
					</li>
					<?php
				}
				$list .= ob_get_clean();
				$list .="</ul>";
				return $list;
			}
		}
	function whizz_plugin_list_view_active_plugins()
		{
			if(current_user_can('manage_options')) 
			{
				$list = "<ul id='list_view_plugin'>";
				
				$plugin_list_temp = get_plugins();
				$plugin_order = get_option(get_current_user_id().'_plugin_order_whizz_active_plugins');
				$ordered_appended_array = array();
				if(isset($plugin_order) && !empty($plugin_order))
				{
					foreach($plugin_order as $order_key => $order_value)
					{
						if(array_key_exists($order_key,$plugin_list_temp))
						{
							$ordered_appended_array[$order_key] = $order_value;
							unset($plugin_list_temp[$order_key]);
						}
					}
				}
				if(isset($plugin_list_temp) && !empty($plugin_list_temp))
				{
					foreach($plugin_list_temp as $remaining_key => $remaining_value)
					{
						$ordered_appended_array[$remaining_key] = $remaining_value;
					}
				}
				ob_start();
				foreach($ordered_appended_array as $plugin_key => $plugin_value)
				{
					if ( is_plugin_active( $plugin_key) )
					{ 
						?>
		
						<li class="li_inside_ul_list" id="<?php echo sanitize_text_field($plugin_key); ?>">
							<div class="main_div_inside_li_list">
								<div class="span6 col-md-6">
								<div class="plugin_name_list">
							<input type="checkbox" name="selected_checkboxes[]" value="<?php echo sanitize_text_field($plugin_key); ?>" />
							 <input type="hidden" name="selected_<?php echo str_replace('.','_', sanitize_text_field($plugin_key)); ?>" value="<?php echo trim(sanitize_text_field($plugin_value['Name'])); ?>" />
							 <?php //wp_nonce_field( 'is_plugin_active', 'nonce_is_plugin_active' ); ?>
								<?php 
									echo sanitize_text_field($plugin_value['Name']);
								?>
							</div>
								<ul>
								<li>
									<div class="plugin_activate_list">
										<?php 
										$get_value = "";
										if(isset($_GET['list_of']) && !empty($_GET['list_of']))
										{
											$get_value = sanitize_text_field($_GET['list_of']);
										}
										if ( is_plugin_active( $plugin_key) ) 
										{
										   $plugin_deactivate = wp_nonce_url(add_query_arg( array( 'action' => 'deactivatep', 'ppath' => $plugin_key, 'view'=>'list_view', 'list_of'=>$get_value), WHIZZ_PLUGINS_LIST_PLUGIN_URL), 'nonce_deactivatep', 'nonce_is_plugin_active');
											echo "<a href='".$plugin_deactivate."'>".__("Deactivate",'whizz')."</a>";
										}
										else
										{
										$plugin_activate =  wp_nonce_url(add_query_arg( array( 'action' => 'activatep', 'ppath' => $plugin_key, 'view'=>'list_view', 'list_of'=>$get_value), WHIZZ_PLUGINS_LIST_PLUGIN_URL), 'nonce_activatep', 'nonce_is_plugin_deactive');	
											echo "<a href='".$plugin_activate."'>".__("Activate",'whizz')."</a>";
										}
										
										?>
									</div>
								</li>							
									<li>
										<?php
										$plugin_editor = esc_url("plugin-editor.php?file=".$plugin_key);
											echo "<a href='".$plugin_editor."'>".__("Edit",'whizz')."</a>";
										?>
									</li>
									<li>
										<?php
										if( strstr($plugin_key, 'whizz-plugin') == false)
										{
										$plugin_delete =  esc_url(add_query_arg( array( 'action' => 'deletep', 'ppath' => $plugin_key, 'plugin'=>$plugin_value['Name'], 'view'=>'list_view', 'list_of'=>$get_value), WHIZZ_PLUGINS_LIST_PLUGIN_URL));		
											echo "<a href='".$plugin_delete."'>".__("Delete",'whizz')."</a>";
										}
										?>
									</li>                            
								</ul>
								</div>
									<div class="span6 col-md-6"	>
								<div class="plugin_desc_list">
								<?php 
									echo sanitize_text_field($plugin_value['Description']);
								?>
								</div>
								<ul>
								<li>
								
								<div class="plugin_version_list">
								Version 
								<?php 
								echo sanitize_text_field($plugin_value['Version']);
								?>
								</div>
								</li> | <li>
								<div class="plugin_author_name_list">
								<?php 
									echo sanitize_text_field($plugin_value['AuthorName']);
								?>
								</div>
								</li> | <li>
								<div class="plugin_uri_list">
								<?php 
									$plugin_more_detail = esc_url($plugin_value['PluginURI']); 
									echo "<a href='".$plugin_more_detail."'>".__("More Details",'whizz')."</a>";
								?>
								</div>
								</li>
								</ul>
								</div>
							</div>
							<div class="clear"></div>
						</li>
						<?php
					}
				}
		
				$list .= ob_get_clean();
				$list .="</ul>";
				return $list;
			}
	}	
	function whizz_plugin_list_view_inactive_plugins()
	{
		if(current_user_can('manage_options')) 
		{
			$list = "<ul id='list_view_plugin'>";
			$plugin_list_temp = get_plugins();
			$plugin_order = get_option(get_current_user_id().'_plugin_order_whizz_inactive_plugins');
			$ordered_appended_array = array();
			if(isset($plugin_order) && !empty($plugin_order))
			{
				foreach($plugin_order as $order_key => $order_value)
				{
					if(array_key_exists($order_key,$plugin_list_temp))
					{
						$ordered_appended_array[$order_key] = $order_value;
						unset($plugin_list_temp[$order_key]);
					}
				}
			}
			if(isset($plugin_list_temp) && !empty($plugin_list_temp))
			{
				foreach($plugin_list_temp as $remaining_key => $remaining_value)
				{
					$ordered_appended_array[$remaining_key] = $remaining_value;
				}
			}
			ob_start();
			foreach($ordered_appended_array as $plugin_key => $plugin_value)
			{
				if ( is_plugin_active( $plugin_key ) )
				{ 
				}
				else
				{
					?>
					<li class="li_inside_ul_list" id="<?php echo sanitize_text_field($plugin_key); ?>">
						<div class="main_div_inside_li_list">
							<div class="span6 col-md-6">
							<div class="plugin_name_list">
							<input type="checkbox" name="selected_checkboxes[]" value="<?php echo sanitize_text_field($plugin_key); ?>" />
							 <input type="hidden" name="selected_<?php echo sanitize_text_field($plugin_key); ?>" value="<?php echo trim(sanitize_text_field($plugin_value['Name'])); ?>" />                       
							<?php 
								echo sanitize_text_field($plugin_value['Name']);
							?>
							</div>
							<ul>		
							<li>
								<div class="plugin_activate_list">
									<?php 
									$get_value = "";
									if(isset($_GET['list_of']) && !empty($_GET['list_of']))
									{
										$get_value = sanitize_text_field($_GET['list_of']);
									}
									if ( is_plugin_active( $plugin_key ) ) 
									{
									 $is_plugin_active = wp_nonce_url(add_query_arg( array( 'action' => 'deactivatep', 'ppath' => $plugin_key, 'view'=>'list_view', 'list_of'=>$get_value), WHIZZ_PLUGINS_LIST_PLUGIN_URL), 'nonce_deactivatep', 'nonce_is_plugin_active');   
										echo "<a href='".$is_plugin_active."'>".__("Deactivate",'whizz')."</a>";
									}
									else
									{
									$plugin_deactive = wp_nonce_url(add_query_arg( array( 'action' => 'activatep', 'ppath' => $plugin_key, 'view'=>'list_view', 'list_of'=>$get_value), WHIZZ_PLUGINS_LIST_PLUGIN_URL), 'nonce_activatep', 'nonce_is_plugin_deactive');	
										echo "<a href='".$plugin_deactive."'>".__("Activate",'whizz')."</a>";
									}
									?>
								</div>
							</li>					
								<li>
									<?php
										$plugin_edit = esc_url("plugin-editor.php?file=".$plugin_key);
										echo "<a href='".$plugin_edit."'>".__("Edit",'whizz')."</a>";
									?>
								</li>
								<li>
									<?php
									if( strstr($plugin_key, 'whizz-plugin') == false)
									{
									$plugin_delete = esc_url(add_query_arg( array( 'action' => 'deletep', 'ppath' => $plugin_key, 'plugin'=>$plugin_value['Name'], 'view'=>'list_view', 'list_of'=>$get_value), WHIZZ_PLUGINS_LIST_PLUGIN_URL));		
										echo "<a href='".$plugin_delete."'>".__("Delete",'whizz')."</a>";
									}
									?> 
								</li>                             
							</ul>
							</div>
								<div class="span6 col-md-6">
							<div class="plugin_desc_list">
							<?php 
								echo sanitize_text_field($plugin_value['Description']);
							?>
							</div>
							<ul>
							<li>
							<div class="plugin_version_list">
							Version 
							<?php 
								echo sanitize_text_field($plugin_value['Version']);
							?>
							</div>
							</li> | <li>
							<div class="plugin_author_name_list">
							<?php 
								echo sanitize_text_field($plugin_value['AuthorName']);
							?>
							</div>
							</li> | <li>
							<div class="plugin_uri_list">
							<?php 
								$more_detail = esc_url($plugin_value['PluginURI']);
								echo "<a href='".$more_detail."'>".__("More Details",'whizz')."</a>";
							?>
							</div>
							</li>
							</ul>
							</div>
						</div>
						<div class="clear"></div>
					</li>
					<?php
				}
			}
			$list .= ob_get_clean();
			$list .="</ul>";
			return $list;
		}
	}
	function whizz_plugin_list_view_active_update_available_plugins()
	{
		if(current_user_can('manage_options')) 
		{
			$current = get_site_transient( 'update_plugins' );
			$list = "<ul id='list_view_plugin'>";
			$plugin_list_temp = get_plugins();
			$plugin_order = get_option(get_current_user_id().'_plugin_order_whizz_active_update_available');		
			$ordered_appended_array = array();		
			if(isset($plugin_order) && !empty($plugin_order))
			{
				foreach($plugin_order as $order_key => $order_value)
				{
					if(array_key_exists($order_key,$plugin_list_temp))
					{
						$ordered_appended_array[$order_key] = $order_value;
						unset($plugin_list_temp[$order_key]);
					}
				}
			}
			if(isset($plugin_list_temp) && !empty($plugin_list_temp) )
			{
				foreach($plugin_list_temp as $remaining_key => $remaining_value)
				{
					/* To check, is update available then add else don't*/
					foreach($current->response as $upkey => $upval)
					{
						if($upkey == $remaining_key)
						{
							$ordered_appended_array[$remaining_key] = $remaining_value;
						}
					}
				}
			}
			ob_start();
			foreach($ordered_appended_array as $plugin_key => $plugin_value)
			{
				if ( is_plugin_active( $plugin_key ) )
				{ 
					?>
					<li class="li_inside_ul_list" id="<?php echo sanitize_text_field($plugin_key); ?>">
						<div class="main_div_inside_li_list">
							<div class="span6 col-md-6">
							<div class="plugin_name_list">
							<input type="checkbox" name="selected_checkboxes[]" value="<?php echo sanitize_text_field($plugin_key); ?>" />
							 <input type="hidden" name="selected_<?php echo sanitize_text_field($plugin_key); ?>" value="<?php echo trim(sanitize_text_field($plugin_value['Name'])); ?>" />
							 <?php //wp_nonce_field( 'active_update_available', 'nonce_active_update_available' ); ?>  
							<?php 
								echo sanitize_text_field($plugin_value['Name']);
							?>
							</div>
							<ul>
							<li>
								<div class="plugin_activate_list">
									<?php 
									$get_value = "";
									if(isset($_GET['list_of']) && !empty($_GET['list_of'])) 
									{
										$get_value = sanitize_text_field($_GET['list_of']);
									}
									if ( is_plugin_active( $plugin_key ) ) 
									{
									 $is_plugin_active = wp_nonce_url(add_query_arg( array( 'action' => 'deactivatep', 'ppath' => $plugin_key, 'view'=>'list_view', 'list_of'=>$get_value), WHIZZ_PLUGINS_LIST_PLUGIN_URL), 'nonce_deactivatep', 'nonce_is_plugin_active');   
									 echo "<a href='".$is_plugin_active."'>".__("Deactivate",'whizz')."</a>";
									}
									else
									{
									$plugin_activate = wp_nonce_url(add_query_arg( array( 'action' => 'activatep', 'ppath' => $plugin_key, 'view'=>'list_view', 'list_of'=>$get_value), WHIZZ_PLUGINS_LIST_PLUGIN_URL), 'nonce_activatep', 'nonce_is_plugin_deactive');   
									echo "<a href='".$plugin_activate."'>".__("Activate",'whizz')."</a>";
									}
									?>
								</div>
							</li>					
								<li>
									<?php
									$plugin_edit = esc_url("plugin-editor.php?file=".$plugin_key);
										echo "<a href='".$plugin_edit."'>".__("Edit",'whizz')."</a>";
									?>
								</li>
								<li>
								<?php
									if( strstr($plugin_key, 'whizz-plugin') == false)
									{
									$plugin_delete = esc_url(add_query_arg( array( 'action' => 'deletep', 'ppath' => $plugin_key, 'plugin'=>$plugin_value['Name'], 'view'=>'list_view', 'list_of'=>$get_value), WHIZZ_PLUGINS_LIST_PLUGIN_URL));   	
										echo "<a href='".$plugin_delete."'>".__("Delete",'whizz')."</a>";
									}
									?> 
								</li>
							</ul>
							</div>
								<div class="span6 col-md-6">
							<div class="plugin_desc_list">
							<?php 
								echo sanitize_text_field($plugin_value['Description']);
							?>
							</div>
							<ul>
							<li>
							<div class="plugin_version_list">
							Version 
							<?php 
								echo sanitize_text_field($plugin_value['Version']);
							?>
							</div>
							</li> | <li>
							<div class="plugin_author_name_list">
							<?php 
								echo sanitize_text_field($plugin_value['AuthorName']);
							?>
							</div>
							</li> | <li>
							<div class="plugin_uri_list">
							<?php 
								$more_details = esc_url($plugin_value['PluginURI']);
								echo "<a href='".$more_details."'>".__("More Details",'whizz')."</a>";
							?>
							</div>
							</li>
							</ul>
							</div>
						</div>
						<div class="clear"></div>
					</li>
					<?php
				}
			}
			$list .= ob_get_clean();
			$list .="</ul>";
			return $list;
		}
	}	
	function whizz_plugin_list_view_inactive_update_available_plugins()
	{
		if(current_user_can('manage_options')) 
		{
			$current = get_site_transient( 'update_plugins' );
			$list = "<ul id='list_view_plugin'>";
			$plugin_list_temp = get_plugins();
			$plugin_order = get_option(get_current_user_id().'_plugin_order_whizz_inactive_update_available');		
			$ordered_appended_array = array();
			if(isset($plugin_order) && !empty($plugin_order))
			{
				foreach($plugin_order as $order_key => $order_value)
				{
					if(array_key_exists($order_key,$plugin_list_temp))
					{
						$ordered_appended_array[$order_key] = $order_value;
						unset($plugin_list_temp[$order_key]);
					}
				}
			}
			if(isset($plugin_list_temp) && !empty($plugin_list_temp) )
			{
				foreach($plugin_list_temp as $remaining_key => $remaining_value)
				{
					/* To check, is update available then add else don't*/
					foreach($current->response as $upkey => $upval)
					{
						if($upkey == $remaining_key)
						{
							$ordered_appended_array[$remaining_key] = $remaining_value;
						}
					}
				}
			}
			ob_start();
			foreach($ordered_appended_array as $plugin_key => $plugin_value)
			{
				if ( is_plugin_active( $plugin_key ) )
				{ 
				}
				else
				{
					?>
					<li class="li_inside_ul_list" id="<?php echo sanitize_text_field($plugin_key); ?>">
						<div class="main_div_inside_li_list">
							<div class="span6 col-md-6">
							<div class="plugin_name_list">
							<input type="checkbox" name="selected_checkboxes[]" value="<?php echo sanitize_text_field($plugin_key); ?>" />
							 <input type="hidden" name="selected_<?php echo $plugin_key; ?>" value="<?php echo trim(sanitize_text_field($plugin_value['Name'])); ?>" />
							 <?php //wp_nonce_field( 'inactive_update_available', 'nonce_inactive_update_available' ); ?>                          
							<?php  
								echo sanitize_text_field($plugin_value['Name']);
							?>
						</div>
							<ul>
							<li>
								<div class="plugin_activate_list">
									<?php 
									$get_value = "";
									if(isset($_GET['list_of']) && !empty($_GET['list_of']))
									{
										$get_value = sanitize_text_field($_GET['list_of']);
									}
									if ( is_plugin_active( $plugin_key ) ) 
									{
									 $plugin_deactivate = wp_nonce_url(add_query_arg( array( 'action' => 'deactivatep', 'ppath' => $plugin_key, 'view'=>'list_view', 'list_of'=>$get_value), WHIZZ_PLUGINS_LIST_PLUGIN_URL), 'nonce_deactivatep', 'nonce_is_plugin_active');    
										echo "<a href='".$plugin_deactivate."'>".__("Deactivate",'whizz')."</a>";
									}
									else
									{
									$plugin_activate = wp_nonce_url(add_query_arg( array( 'action' => 'activatep', 'ppath' => $plugin_key, 'view'=>'list_view', 'list_of'=>$get_value), WHIZZ_PLUGINS_LIST_PLUGIN_URL), 'nonce_activatep', 'nonce_is_plugin_deactive');
										echo "<a href='".$plugin_activate."'>".__("Activate",'whizz')."</a>";
									}
									?>
								</div>
							</li>               	
								<li>
									<?php
										$plugin_edit = esc_url("plugin-editor.php?file=".$plugin_key);
										echo "<a href='".$plugin_edit."'>".__("Edit",'whizz')."</a>";
									?>                    	
								</li>
								<li>
									<?php
									if( strstr($plugin_key, 'whizz-plugin') == false)
									{
									$plugin_delete = esc_url(add_query_arg( array( 'action' => 'deletep', 'ppath' => $plugin_key, 'plugin'=>$plugin_value['Name'], 'view'=>'list_view', 'list_of'=>$get_value), WHIZZ_PLUGINS_LIST_PLUGIN_URL)); 	
										echo "<a href='".$plugin_delete."'>".__("Delete",'whizz')."</a>";
									}
									?> 
								</li>
								 <?php 
									//echo whizz_save_remove_my_list_func($plugin_key);
								?>
							</ul>
							</div>
								<div class="span6 col-md-6">
							<div class="plugin_desc_list">
							<?php 
								echo sanitize_text_field($plugin_value['Description']);
							?>
							</div>
							<ul>
							<li>
							<div class="plugin_version_list">
							Version 
							<?php 
								echo sanitize_text_field($plugin_value['Version']);
							?>
							</div>
							</li> | <li>
							<div class="plugin_author_name_list">
							<?php 
								echo sanitize_text_field($plugin_value['AuthorName']);
							?>
						</div>
						</li> | <li>
							<div class="plugin_uri_list">
							<?php 
								$more_details = esc_url($plugin_value['PluginURI']);
								echo "<a href='".$more_details."'>".__("More Details",'whizz')."</a>";
							?>
							</div>
							</li>
							</ul>
							</div>
						</div>
						<div class="clear"></div>
					</li>
					<?php
				}
			}
			$list .= ob_get_clean();
			$list .="</ul>";
			return $list;
		}
	}
	function whizz_plugin_list_view_my_list()
	{
		if(current_user_can('manage_options'))
		{
			$plugin_list_temp = get_plugins();
			$plugin_order = get_option(get_current_user_id().'_plugin_order_whizz_my_preferences');
			$ordered_appended_array = array();
			if(isset($plugin_order) && !empty($plugin_order))
			{
				foreach($plugin_order as $order_key => $order_value)
				{
					if(array_key_exists($order_key,$plugin_list_temp))
					{
						$ordered_appended_array[$order_key] = $order_value;
						unset($plugin_list_temp[$order_key]);
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
						 <input type="hidden" name="selected_<?php echo $plugin_key; ?>" value="<?php echo trim(sanitize_text_field($plugin_value['Name'])); ?>" />
						<?php 
							echo sanitize_text_field($plugin_value['Name']);
						?>
						</div>
						<ul>
						<li>
								<div class="plugin_activate_list">
									<?php 
									$get_value = "";
									if(isset($_GET['list_of']) && !empty($_GET['list_of']))
									{
										$get_value = sanitize_text_field($_GET['list_of']);
									}
									if ( is_plugin_active( $plugin_key ) ) 
									{
									 $is_plugin_active = wp_nonce_url(add_query_arg( array( 'action' => 'deactivatep', 'ppath' => $plugin_key, 'view'=>'list_view', 'list_of'=>$get_value), WHIZZ_PLUGINS_LIST_PLUGIN_URL), 'nonce_deactivatep', 'nonce_is_plugin_active');    
										echo "<a href='".$is_plugin_active."'>".__("Deactivate",'whizz')."</a>";
									}
									else
									{
									 $plugin_active = wp_nonce_url(add_query_arg( array( 'action' => 'activatep', 'ppath' => $plugin_key, 'view'=>'list_view', 'list_of'=>$get_value), WHIZZ_PLUGINS_LIST_PLUGIN_URL), 'nonce_activatep', 'nonce_is_plugin_deactive');
									 echo "<a href='".$plugin_active."'>".__("Activate",'whizz')."</a>";
									}
									?>
								</div>
							</li>				
							<li>
								<?php
									$plugin_edit = esc_url("plugin-editor.php?file=".$plugin_key);
									echo "<a href='".$plugin_edit."'>".__("Edit",'whizz')."</a>";
								?>
							</li>
							<li>
								<?php
								if( strstr($plugin_key, 'whizz-plugin') == false)
								{
								$plugin_delete = esc_url(add_query_arg( array( 'action' => 'deletep', 'ppath' => $plugin_key, 'plugin'=>$plugin_value['Name'], 'view'=>'list_view', 'list_of'=>$get_value), WHIZZ_PLUGINS_LIST_PLUGIN_URL));	
									echo "<a href='".$plugin_delete."'>".__("Delete",'whizz')."</a>";
								}
								?> 
							</li>
							<?php
								//echo whizz_save_remove_my_list_func($plugin_key);
							?>
						</ul>
						</div>
							<div class="span6 col-md-6">
						<div class="plugin_desc_list">
						<?php 
							echo sanitize_text_field($plugin_value['Description']);
						?>
						</div>
						<ul>
						<li>
						<div class="plugin_version_list">Version 
						<?php
							echo sanitize_text_field($plugin_value['Version']);
						?>
						</div>
						</li> | <li>
						<div class="plugin_author_name_list">
						<?php 
							echo sanitize_text_field($plugin_value['AuthorName']);
						?>
						</div>
						</li> | <li>
						<div class="plugin_uri_list">
						<?php 
							$more_details = esc_url($plugin_value['PluginURI']);
							echo "<a href='".$more_details."'>".__("More Details",'whizz')."</a>";
						?>
						</div>
						</li>
						</ul>
						</div>
					</div>
					<div class="clear"></div>
				</li>
				<?php
			}
			$list .= ob_get_clean();
			$list .="</ul>";
			return $list;
		}
	}	
?>