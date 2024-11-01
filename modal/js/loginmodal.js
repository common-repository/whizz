jQuery(document).ready(function($) {
	jQuery("#dont_bug_me_modal_login").click(function(e) {
			jQuery.ajax({
				url: main_js_obj_common.admin_ajax_url,
				type: "POST",
				data:{
					action: 'dont_bug_me_modal_login',
					messageb:'disable'
				},
			}).done(function( r ) {	
				if( r.success )
				{
					location.href = location.href;
				}
				else 
				{
					console.log('Not Successful.');
				}
			}).fail(function( jqXHR, textStatus ) {
				console.log('Not Successful.');
			});
   		});
    
	$(".login-modal").click(function(e) {
		$("#WhizzLogin-Modal-overlay-osx").fadeIn('slow',function(){
			$("#WhizzLogin-Modal-container-osx").slideDown('slow', function () {
			});
		});
		return false;
    });
	
	$(".simplemodal-close").click(function(e) {
		$("#dv_username").val('');
		$("#dv_password").val('');
		$("#WhizzLogin-Modal-container-osx").slideUp('slow', function () {
			$("#WhizzLogin-Modal-overlay-osx").fadeOut('slow',function(){ });
		});
    });
	$(".simplemodal-reset").click(function(e) {
		$("#dv_username").val('');
		$("#dv_password").val('');
		$("#WhizzLogin_Modal_register_username").val('');
		$("#WhizzLogin_Modal_register_useremail").val('');
		$("#WhizzLogin_Modal_register_userfirstname").val('');
		$("#WhizzLogin_Modal_register_userlastname").val('');
		$("#wp_submit_WhizzLogin_Modal_reset_text").val('');
		
	});
	
	$(".reg_simplemodal-close").click(function(e) {
		$("#dv_username").val('');
		$("#dv_password").val('');
		$("#WhizzLogin-Modal-container-osx").slideUp('slow', function () {
			$("#WhizzLogin-Modal-overlay-osx").fadeOut('slow',function(){
			});
		});
    });
	
	$('#WhizzLogin-Modal_login_form_main_div').keypress(function(event){
	  if(event.keyCode == 13){
		$('#dv-login-modal-wp-submit').click();
	  }
	});
	
	$("#simplemodal_login_save").click(function(e) {
		var login_modal_redirect = "";
		var wp_login_redirect_text_login_modal = "";
		var whizz_login_modal_redirect_afterlogin = "";
		var wp_user_login_redirect_text_afterlogin = "";
		var registration = "";
		var reset_chk = "";
        if($("#login_modal_redirect").is(":checked") == true)
		{
			login_modal_redirect = "on";
			if($("#wp_login_redirect_text_login_modal").val() == "" && !$("#wp_login_redirect_text_login_modal").val() > 0)
			{
				alert("Please select wp-login redirect page");
				return false;
			}
			wp_login_redirect_text_login_modal = $("#wp_login_redirect_text_login_modal").val();
		}
		if($("#whizz_login_modal_redirect_afterlogin").is(":checked") == true)
		{
			whizz_login_modal_redirect_afterlogin = "on";
			if($("#wp_user_login_redirect_text_afterlogin").val() == "" && !$("#wp_user_login_redirect_text_afterlogin").val() > 0)
			{
				alert("Please select user redirect page");
				return false;
			}
			wp_user_login_redirect_text_afterlogin = $("#wp_user_login_redirect_text_afterlogin").val();
		}
		
		if($("#registration").is(":checked") == true)
		{
			registration = "on";
		}
		if($("#reset").is(":checked") == true)
		{
			reset_chk = "on";
		}
		
		jQuery.ajax({
			url: login_modal_ajax_url.admin_ajax_url,
			type: "POST",
			data:{
				action: 'manage_page_redirects',
				'security': $( '#login_modal_options #whizz-main-ajax-nonce' ).val(),
				process:'savedata',
				registration_a: registration,
				reset_a: reset_chk,
				login_modal_redirect_a: login_modal_redirect,
				wp_login_redirect_text_login_modal_a: wp_login_redirect_text_login_modal,
				whizz_login_modal_redirect_afterlogin_a: whizz_login_modal_redirect_afterlogin,
				wp_user_login_redirect_text_afterlogin_a: wp_user_login_redirect_text_afterlogin
			},
		}).done(function( r ) {	
			if( r.success )
			{
				if(r.data.rcode == 1)
				{
					jQuery("#errormessage").html("<div class='updated'> Settings saved successfully.</div>");
					jQuery("#whizz_modal_page_redirect_settings_notice").hide();
					//var url_red = location.href;
					//location.href = url_red.replace('#','');
				}
				else
				{
					jQuery("#errormessage").html(r.data.message);
				}
			}
			else 
			{
				console.log('Not Successful.');
			}
		}).fail(function( jqXHR, textStatus ) {
			console.log('Not Successful.');
		});
    });
/*	$('#WhizzLogin-Modal-form').keypress(function(event){
	  if(event.keyCode == 13){
		$('#dv-login-modal-wp-submit').click();
	  }
	});*/
	$('#register_form_main_div').keypress(function(event){
	  if(event.keyCode == 13){
		$('#wp_submit_WhizzLogin_Modal_register_form').click();
	  }
	});
	$('#reset_form_main_div').keypress(function(event){
	  if(event.keyCode == 13){
		$('#wp_submit_WhizzLogin_Modal_reset_form').click();
	  }
	});
	$('#reset_confirm_form_main_div').keypress(function(event){
	  if(event.keyCode == 13){
		$('#wp_submit_WhizzLogin_Modal_reset_confirm_form').click();
	  }
	});
	/************************************user login with email and password start*************************************************************/
	$("#dv-login-modal-wp-submit").click(function(e) {
		$.ajax({
			'url': login_modal_ajax_url.admin_ajax_url,
			'method': 'POST',
			'data':{
				'action': 'dv_login_modal_action',
				'security': $( '#WhizzLogin-Modal_login_form_main_div #whizz-ajax-nonce' ).val(),
				'user': $("#dv_username").val(),
				'password': $("#dv_password").val(),
			},
			'dataType': 'json',
		}).done(function(r){
			if(r.success)
			{
				window.location.href = $("#login-modal-redirect-url").val();
			}
			else
			{
				$("#login_error").html(r.data.msg+'<br>');
				$("#login_error").css("display", "block");
				return false;	
			}
		}).fail(function(jqXHR, textStatus){
			return false;
		});
	});
	/******************************************user login with email and password end********************************************************/
	
	$("#wp_submit_WhizzLogin_Modal_register_form").click(function(e) {
		$.ajax({
			'url': login_modal_ajax_url.admin_ajax_url,
			'method': 'POST',
			'data':{
				'action': 'dv_login_modal_register_action',
				'security': $( '#register_form_main_div #whizz-ajax-nonce' ).val(),
				'user': $("#WhizzLogin_Modal_register_username").val(),
				'email': $("#WhizzLogin_Modal_register_useremail").val(),
				'firstname': $("#WhizzLogin_Modal_register_userfirstname").val(),
				'lastname': $("#WhizzLogin_Modal_register_userlastname").val(),				
			},
			'dataType': 'json',
		}).done(function(r){
			if(r.success)
			{
				$("#register_error").html('Registration complete. Please check your e-mail.<br>');
				$("#register_error").css("display", "block");
				$("#register_error").css("background-color", "#57c605");
				$("#register_error").css("border", "1px solid #57c605");
				$("#WhizzLogin_Modal_register_username").val('');
				$("#WhizzLogin_Modal_register_useremail").val('');
				$("#WhizzLogin_Modal_register_userfirstname").val('');
				$("#WhizzLogin_Modal_register_userlastname").val('');
				return false;
			}
			else
			{
				$("#register_error").html(r.data.msg+'<br>');
				$("#register_error").css("display", "block");
				//r.data.msg
				return false;	
			}
		}).fail(function(jqXHR, textStatus){
			return false;
		});
	});
	/******************************************user registertion  end********************************************************/
	
	$("#wp_submit_WhizzLogin_Modal_reset_form").click(function(e) {
		$.ajax({
			'url': login_modal_ajax_url.admin_ajax_url,
			'method': 'POST',
			'data':{
				'action': 'dv_login_modal_reset_action',
				'security': $( '#reset_form_main_div #whizz-ajax-nonce' ).val(),
				'user': $("#wp_submit_WhizzLogin_Modal_reset_text").val(),
			},
			'dataType': 'json',
		}).done(function(r){
			if(r.success)
			{
				$("#reset_error").html('Please check your e-mail.<br>');
				$("#reset_error").css("display", "block");
				$("#reset_error").css("background-color", "#57c605");
				$("#reset_error").css("border", "1px solid #57c605");
				$("#wp_submit_WhizzLogin_Modal_reset_text").val('');				
				return false;
			}
			else
			{
				$("#reset_error").html(r.data.msg+'<br>');
				$("#reset_error").css("display", "block");
				//r.data.msg
				return false;	
			}
		}).fail(function(jqXHR, textStatus){
			return false;
		});
	});
	/******************************************user reset password  end********************************************************/
	$("#wp_submit_WhizzLogin_Modal_reset_confirm_form").click(function(e) {
		$.ajax({
			'url': login_modal_ajax_url.admin_ajax_url,
			'method': 'POST',
			'data':{
				'action': 'dv_login_modal_reset_confirm_action',
				'security': $( '#reset_confirm_form_main_div #whizz-ajax-nonce' ).val(),
				'pass1': $("#WhizzLogin_Modal_reset_newpassword").val(),
				'pass2': $("#WhizzLogin_Modal_reset_confirm_newpassword").val(),
				'user': $("#reset_confirm_password_userid").val(),
			},
			'dataType': 'json',
		}).done(function(r){
			if(r.success)
			{
				$("#reset_confirm_error").html('Password reset successfully.<br>');
				$("#reset_confirm_error").css("display", "block");
				$("#reset_confirm_error").css("background-color", "#57c605");
				$("#reset_confirm_error").css("border", "1px solid #57c605");
				$("#WhizzLogin_Modal_reset_newpassword").val('');
				$("#WhizzLogin_Modal_reset_confirm_newpassword").val('');				
				return false;
			}
			else
			{
				$("#reset_confirm_error").html(r.data.msg+'<br>');
				$("#reset_confirm_error").css("display", "block");
				//r.data.msg
				return false;	
			}
		}).fail(function(jqXHR, textStatus){
			return false;
		});
	});
	
	$("#login_modal_redirect").click(function(e) {
        if($(this).is(':checked')){
			$("#wp_login_redirect_login_modal").slideDown('slow', function () {
			});
		}
		else
		{
			$("#wp_login_redirect_login_modal").slideUp('slow', function () {
				$("#wp_login_redirect_text_login_modal").val('');
			});
		}
    });
	$("#whizz_login_modal_redirect_afterlogin").click(function(e) {
        if($(this).is(':checked')){
			$("#user_login_redirect_afterlogin").slideDown('slow', function () {
			});
		}
		else
		{
			$("#user_login_redirect_afterlogin").slideUp('slow', function () {
				$("#wp_user_login_redirect_text_afterlogin").val('-1');
			});
		}
    });
	$(".simplemodal-forgotpw").click(function(e) {
		$("#WhizzLogin-Modal_login_form_main_div").slideUp('slow', function () {});
		$("#register_form_main_div").slideUp('slow', function () {});
		$("#reset_form_main_div").slideDown('slow', function () {});
		$("#model_login_close").css("display", "block");
        return false;
    });
	
	$(".WhizzLogin_Modal_login_form").click(function(e) {
		$("#register_form_main_div").slideUp('slow', function () {});
		$("#reset_form_main_div").slideUp('slow', function () {});
		$("#reset_confirm_form_main_div").slideUp('slow', function () {});
		$("#WhizzLogin-Modal_login_form_main_div").slideDown('slow', function () {});
		$("#model_login_close").css("display", "block");
        return false;
    });
	
	$(".simplemodal-register").click(function(e) {
		$("#model_login_close").css("display", "none");
		$("#WhizzLogin-Modal_login_form_main_div").slideUp('slow', function () {});
		$("#reset_form_main_div").slideUp('slow', function () {});	
		$("#register_form_main_div").slideDown('slow', function () {});
		
        return false;
    });
});