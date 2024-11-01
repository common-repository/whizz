<?php
if( !defined('WHIZZ_PLUGIN_URL') ){
	echo 'Direct call denied.';
	exit;
}
?>
<h1>Delete Plugin</h1>
<p>Are you sure you want to delete this plugin?</p>
<strong>
<?php
$plugin_title = '';
if(isset($_GET['plugin']) && !empty($_GET['plugin']))
{	
	$plugin_title = sanitize_text_field($_GET['plugin']);
	echo $plugin_title;
}
?>
</strong>
<br /><br />
<?php if(current_user_can('manage_options')) 
{ 
?>
<?php
$ppath ='';
if(isset($_GET['ppath']) && !empty($_GET['ppath']))
{
	$ppath = sanitize_text_field($_GET['ppath']);
}
$view = '';
if(isset($_GET['view']) && !empty($_GET['view'])) {
	$view = sanitize_text_field($_GET['view']); 	
}
$list_of = '';
if(isset($_GET['list_of']) && !empty($_GET['list_of'])) {
	$list_of = 	sanitize_text_field($_GET['list_of']);
}

$delete_plugin_url = add_query_arg(
									array('ppath' => $ppath,
										'view' => $view,
										'deletec' => 'yes',
										'list_of' => $list_of,
									),
								WHIZZ_PLUGINS_LIST_PLUGIN_URL);
$plugin_list_url = add_query_arg(
									array('view' => $view,
										'list_of' => $list_of,
									),
								WHIZZ_PLUGINS_LIST_PLUGIN_URL);
?> 
<form action="<?php echo esc_url($delete_plugin_url); ?>" method="post">
<?php wp_nonce_field( 'delete_plugin_whizz', '_wpnonce' ); ?>
<input class='button' type="submit" value="Yes Delete the plugin">
</form>
<a class='button' href='<?php echo esc_url($plugin_list_url); ?>'>No Return me to the plugin list</a>
<?php } ?>