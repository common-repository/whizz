<?php
if( !defined('WHIZZ_PLUGIN_URL') ){
	echo 'Direct call denied.';
	exit;
}
?>
<h1><?php _e("Delete User", 'whizz'); ?></h1>


<p><?php _e("Are you sure you want to delete this user?", 'whizz'); ?></p>

<?php if(current_user_can('manage_options')) 
{ 
?>

	<?php
    $uid = '';
    if(isset($_GET['uid']) && !empty($_GET['uid'])) 
        {
        $uid = intval($_GET['uid']);	
        }
    $view ='';
    if(isset($_GET['view']) && !empty($_GET['view'])) 
        {
        $view = sanitize_text_field($_GET['view']);
        }
    $list_of ='';
    if(isset($_GET['list_of']) && !empty($_GET['list_of'])) 
        {
        $list_of = sanitize_text_field($_GET['list_of']);
        }
        
    $delete_user = esc_url(add_query_arg( array( 'uid' => $uid, 'view' => $view, 'deletec' => 'yes', 'list_of' => $list_of), WHIZZ_USERS_LIST_PLUGIN_URL));	
    
    $return_me_users = esc_url(add_query_arg( array( 'view' => $view, 'list_of' => $list_of), WHIZZ_USERS_LIST_PLUGIN_URL));
    
    ?>
    <form action="<?php echo $delete_user; ?>" method="post">
    <?php wp_nonce_field( 'delete_single_user_whizz', 'wpnonceuser' ); ?>
    <input class='button' type="submit" value="<?php _e("Yes Delete this user", 'whizz'); ?>">
    </form>
    <a class='button' href='<?php echo $return_me_users; ?>'><?php _e("No Return me to the users list", 'whizz'); ?></a>
<?php 
} 
?>
