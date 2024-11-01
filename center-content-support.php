<?php echo wp_enqueue_style( 'my-style', plugins_url( '/css/support-style.css', __FILE__ ), false, '1.0' ); ?>
<h1 class="whizz-support"><?php _e( "WHIZZ Support", 'whizz' ); ?></h1>
<div class="whizz_support_tab_heading_wrapper plugin-detail-wrap">
<div class="whizz_support_tab_heading">
  <div id="whizz_latest_update_head" class="whizz_latest_support_tab_active">
    <div class="support-heading2">
      <h3><?php _e( "Latest Update", 'whizz' ); ?></h3>
    </div>
  </div>
  <div id="whizz_update_history_head" class="whizz_latest_support_tab_inactive">
    <div class="support-heading2 update">
      <h3><?php _e( "Update History", 'whizz' ); ?></h3>
    </div>
  </div>
</div>
<div class="plugin-detail-wrap reporting-wrap" id="whizz_latest_update">
  <div class="version-details">
    <h1>v. 1.1.8</h1>
    <p><span class="added"><?php _e( "Added:", 'whizz' ); ?></span>
    <div class="fixed-details"><?php _e( 'WHIZZ is now translation ready for the following 5 languages; Arabic, French, Hindi, Spanish, and Russian. Go to Settings/General and choose from the dropdown under "Site Language" to switch language.', 'whizz' ); ?></div>
    </p>
  </div>
  <div class="clear"></div>
</div>
<div class="plugin-detail-wrap reporting-wrap" id="whizz_update_history" style="display:none;">
	<div class="version-details">
		<h1>v. 1.1.7</h1>
		<p><span class="added"><?php _e( "Fixed:", 'whizz' ); ?></span>
		<div class="fixed-details"><?php _e( "JQuery bug fixed.", 'whizz' ); ?></div>
		</p>
	  </div>
	<div class="version-details">
		<h1>v. 1.1.6</h1>
		<p><span class="added"><?php _e( "Fixed:", 'whizz' ); ?></span>
		<div class="fixed-details"><?php _e( "Updated email templates.", 'whizz' ); ?></div>
    </p>
  </div>
     <div class="version-details">
        <h1>v. 1.1.5</h1>
        <p><span class="added"><?php _e( "Fixed:", 'whizz' ); ?></span>
        <div class="fixed-details"><?php _e( "Registration on mobile,GREEN for success messages and update reset password bug fixed.", 'whizz' ); ?></div>
        </p>
      </div>
    
    <div class="version-details">
        <h1>v. 1.1.4</h1>
        <p><span class="added"><?php _e( "Fixed:", 'whizz' ); ?></span>
        <div class="fixed-details"><?php _e( "Bridge theme and the wp-amp plugin bug fixed.", 'whizz' ); ?></div>
        </p>
      </div>
	<div class="version-details">
    <h1>v. 1.1.3</h1>
    <p><span class="added"><?php _e( "Added:", 'whizz' ); ?></span>
    <div class="fixed-details"><?php _e( "WHIZZ Modal is now included in the free version of WHIZZ.", 'whizz' ); ?></div>
    </p>
  </div>
  <div class="version-details">
    <h1>v. 1.1.2</h1>
    <p><span class="fixed"><?php _e( "Fixed:", 'whizz' ); ?></span>
    <div class="fixed-details"><?php _e( "Separator saving issue fixed.", 'whizz' ); ?>
      </p>
    </div>
    <div class="clear"></div>
  </div>
 
  <div class="version-details">
    <h1>v. 1.1.1</h1>
    <p><span class="fixed"><?php _e( "Fixed:", 'whizz' ); ?></span>
    <div class="fixed-details"><?php _e( "This is a major security update / patch for WHIZZ plugin.<br /><br />It is highly recommended that you update the WHIZZ plugin as soon as possible.  We have fixed compatibility with WordPress and updated the plugin as per WordPress guidelines.<br /><br />Specifically;<br />- WHIZZ Plugin now verifies nonce on user delete, Plugin delete and for form submissions.<br />- Plugin also validates content type before sending to database or before processing (e.g. validating integer data with intval()).", 'whizz' ); ?></div>
    </p>
  </div>
  <div class="version-details">
    <h1>v. 1.1</h1>
    <p><span class="fixed"><?php _e( "Fixed:", 'whizz' ); ?></span>
    <div class="fixed-details"><?php _e( "Fixed compatibility issue with Wordpress.", 'whizz' ); ?></div>
    </p>
  </div>
  <div class="version-details">
    <h1>v. 1.0.10</h1>
    <p><span class="fixed"><?php _e( "Fixed:", 'whizz' ); ?></span>
    <div class="fixed-details"><?php _e( "Updated Compatibility with Wordpress.", 'whizz' ); ?></div>
    </p>
  </div>
  <div class="version-details">
    <h1>v. 1.0.9</h1>
    <p><span class="fixed"><?php _e( "Fixed:", 'whizz' ); ?></span>
    <div class="fixed-details"><?php _e( "Resolved Compatibility issue with Wordpress.", 'whizz' ); ?></div>
    </p>
  </div>
  <div class="version-details">
    <h1>v. 1.0.8</h1>
    <p><span class="fixed"><?php _e( "Fixed:", 'whizz' ); ?></span>
    <div class="fixed-details"><?php _e( "XSS vulnerability issue fixed.", 'whizz' ); ?></div>
    </p>
  </div>
  <div class="version-details">
    <h1>v. 1.0.7</h1>
    <p><span class="fixed"><?php _e( "Clean:", 'whizz' ); ?></span>
    <div class="fixed-details"><?php _e( "CSS conflict with visual composer fixed.", 'whizz' ); ?></div>
    </p>
  </div>
  <div class="version-details">
    <h1>v. 1.0.6</h1>
    <p><span class="fixed"><?php _e( "Clean:", 'whizz' ); ?></span>
    <div class="fixed-details"><?php _e( "css issue resolved.", 'whizz' ); ?></div>
    </p>
  </div>
  <div class="version-details">
    <h1>v. 1.0.5</h1>
    <p><span class="fixed"><?php _e( "Clean:", 'whizz' ); ?></span>
    <div class="fixed-details"><?php _e( "Updated code for menu reorder.", 'whizz' ); ?></div>
    </p>
  </div>
  <div class="version-details">
    <h1>v. 1.0.4</h1>
    <p><span class="fixed"><?php _e( "Clean:", 'whizz' ); ?></span>
    <div class="fixed-details"><?php _e( "Menu reorder bug fixes.", 'whizz' ); ?></div>
    </p>
  </div>
  <div class="version-details">
    <h1>v. 1.0.3</h1>
    <p><span class="fixed"><?php _e( "Clean:", 'whizz' ); ?></span>
    <div class="fixed-details"><?php _e( "Jquery bug fixes.", 'whizz' ); ?></div>
    </p>
  </div>
  <div class="version-details">
    <h1>v. 1.0.2</h1>
    <p><span class="fixed"><?php _e( "Clean:", 'whizz' ); ?></span>
    <div class="fixed-details"><?php _e( "Menu Separator saved data is removed on plugin uninstall.", 'whizz' ); ?></div>
    </p>
  </div>
  <div class="version-details">
    <h1>v. 1.0.1</h1>
    <p><span class="added"><?php _e( "Added:", 'whizz' ); ?></span>
    <div class="added-details"><?php _e( "Menu Separator, it allows user to Select &amp; Drag Separator for WordPress menus.", 'whizz' ); ?></div>
    </p>
  </div>
  <div class="version-details">
    <h1>v. 1.0.0</h1>
    <p><span class="added"><?php _e( "Added:", 'whizz' ); ?></span>
    <div class="added-details"><?php _e( "Wordpress Menus can be rearranged.", 'whizz' ); ?></div>
    </p>
  </div>
  
</div>
</div>	

<div class="plugin-detail-wrap-second">
  <div class="support-heading">
    <h3><?php _e( "Support", 'whizz' ); ?></h3>
  </div>
  <p><?php _e( 'For support, please go to <a href="https://wordpress.org/plugins/whizz/" target="_blank">Wordpress</a> and submit a <a href="https://wordpress.org/support/plugin/whizz" target="_blank">support request</a>. If you are a subscriber to WHIZZ Ultimate, please <a href="https://browserweb.org/account/submitticket.php?step=2&deptid=6" target="_blank">submit a ticket</a> and/or login to <a href="https://browserweb.org/account/" target="_blank">your account</a> at Browserweb.org.<br/><br/><strong>If you wish to upgrade or Purchase our Premium Plugin versions, visit <a href="https://whizz.us.com/" target="_blank">https://whizz.us.com/</a> to learn more.</strong>', 'whizz' ); ?></p>
</div>