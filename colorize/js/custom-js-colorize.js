(function($){
	$(document).ready(function(e) {
		$("#colorpallete").wpColorPicker({hide:false});
		$("#colorpallete_hover").wpColorPicker({hide:false});
		whizz_reset_all_color_boxes();
		whizz_fill_ids_colors();
		whizz_fill_ids_colors_hover();
		$(document).on('click', '#adminmenu >li', function(e){
			var menu_id = $(this).attr('id');
		//jQuery("#adminmenu >li").click(function(e) {
			/* for background color START */
			/*
			Improved below code as jquery changed the resulten element so now using class.
			*/
			/* if($("#bgcolor_browserw .wp-picker-container > a").attr('style')) */
			if($("#bgcolor_browserw .wp-picker-container .wp-color-result").attr('style'))
			{
				var selected_color_css = $("#bgcolor_browserw .wp-picker-container .wp-color-result");
				e.preventDefault();
				var menu_color ="."+menu_id+"s {background-color: "+ selected_color_css.css('background-color')+" !important; }";
				 
				var save_color_nonce = $('#nonce_colorize').val();
						
				$(this).removeClass(menu_id+'s');
				var menu_color_sel = selected_color_css.css('background-color');
            	$(this).css({'background-color': menu_color_sel});
				whizz_save_id_color(menu_id, menu_color, save_color_nonce);
				selected_color_css.removeAttr('style');
				$('html,body').css('cursor','default');
				$('#DivToShow').removeAttr('style');
				whizz_reset_all_color_boxes();
			}
			/* for background color END */
			/* for hover color START */
			/*
			Improved below code as jquery changed the resulten element so now using class.
			*/
			/* if($("#hovercolor_browserw .wp-picker-container > a").attr('style')) */
			if($("#hovercolor_browserw .wp-picker-container .wp-color-result").attr('style'))
			{
				var selected_color_css = $("#hovercolor_browserw .wp-picker-container .wp-color-result");
				e.preventDefault();
				var menu_color ="."+menu_id+"hover a:hover {background-color: "+ selected_color_css.css('background-color')+" !important; }";
				var save_color_hover_nonce = $('#nonce_colorize_hover').val();
				whizz_save_id_color_hover(menu_id, menu_color, save_color_hover_nonce);
				$('body').append("<style>"+ menu_color +"</style>");
				$("#"+menu_id).addClass(menu_id+"hover");
				selected_color_css.removeAttr('style');
				$('html,body').css('cursor','default');
				$('#DivToShow').removeAttr('style');
				$("#adminmenu > li >a").css("cursor", 'auto');
				whizz_reset_all_color_boxes();
			}
			/* for hover color END */
        });
		/* Common for all color pickers START */
		var mouseX;
		var mouseY;
		$(document).mousemove( function(e) {
			var relativePosition = {
			  left: e.pageX - $(document).scrollLeft(),
			  top : e.pageY - $(document).scrollTop() 
			};
			/*
			Improved below code as jquery changed the resulten element so now using class.
			*/
		   /*
		   if($("#bgcolor_browserw .wp-picker-container > a").attr('style'))
		   {
			   var menu_color_selected = $("#bgcolor_browserw .wp-picker-container > a").css('background-color');
		   */
		   if($("#bgcolor_browserw .wp-picker-container .wp-color-result").attr('style'))
		   {
			   var menu_color_selected = $("#bgcolor_browserw .wp-picker-container .wp-color-result").css('background-color');
			    $('#DivToShow').css({'top':(relativePosition.top + 22),'left':(relativePosition.left + 22),'position':'fixed','z-index':'9999','width':'25px','height':'25px','border':'solid 1px','background-color':menu_color_selected });
				$('html,body').css("cursor", 'url('+ main_js_obj_color_picker.for_plugin_url +'/img/paint_bucket.svg), auto');
				$("#adminmenu li >a").hover(function()
				{
					$(this).css("cursor", 'url('+ main_js_obj_color_picker.for_plugin_url +'/img/paint_bucket.svg), auto');
				});
				$("#adminmenu li").hover(function()
				{
					$(this).css("cursor", 'url('+ main_js_obj_color_picker.for_plugin_url +'/img/paint_bucket.svg), auto');
				});
				$("#adminbarnew_bar li >a").hover(function()
				{
					$(this).css("cursor", 'url('+ main_js_obj_color_picker.for_plugin_url +'/img/paint_bucket.svg), auto');
				});
		   }
		   /*
			Improved below code as jquery changed the resulten element so now using class.
			*/
			/*
		   	if($("#hovercolor_browserw .wp-picker-container > a").attr('style'))
		   	{
			   var menu_color_selected =$("#hovercolor_browserw .wp-picker-container > a").css('background-color');
			*/
		   if($("#hovercolor_browserw .wp-picker-container .wp-color-result").attr('style'))
		   	{
			   var menu_color_selected =$("#hovercolor_browserw .wp-picker-container .wp-color-result").css('background-color');
			    $('#DivToShow').css({'top':(relativePosition.top + 22),'left':(relativePosition.left + 22),'position':'fixed','z-index':'9999','width':'25px','height':'25px','border':'solid 1px','background-color':menu_color_selected });
				$('html,body').css("cursor", 'url('+ main_js_obj_color_picker.for_plugin_url +'/img/paint_bucket.svg), auto');
				$("#adminmenu li >a").hover(function()
				{
					$(this).css("cursor", 'url('+ main_js_obj_color_picker.for_plugin_url +'/img/paint_bucket.svg), auto');
				});
				$("#adminbarnew_bar li >a").hover(function()
				{
					$(this).css("cursor", 'url('+ main_js_obj_color_picker.for_plugin_url +'/img/paint_bucket.svg), auto');
				});
		   }
		}); 
		/* Common for all color pickers END */
		$("#reset_all").click(function(e) {
            whizz_reset_all_color_boxes();
        });
		$("#choice_background_color").click(function(e) {
			$("#bgcolor_browserw").show(500);
			$("#hovercolor_browserw").hide(500);
        });
		$("#choice_hover_color").click(function(e) {
			$("#hovercolor_browserw").show(500);
			$("#bgcolor_browserw").hide(500);
        });
		$("#reset_menus_color").click(function(e) {
			var nonce_reset_colorize =$('#nonce_reset_colorize').val();
			$.ajax({
				url: main_js_obj_color_picker.admin_ajax_url,
				type: "POST",
				data:{
					action: 'whizz_reset_menus_color',
					task:'reset',
					wp_nonce_reset_color : nonce_reset_colorize,
				},
				dataType: "json"
				}).done(function( r ) {	
					if( r.success )
					{
						location.href = location.href;
					}
					else 
					{
						/*console.log('fail');*/
					}
				}).fail(function( jqXHR, textStatus ) {
					/*console.log('fail');*/
				});
		});
    });
	function whizz_reset_all_color_boxes()
	{
		/*
		Improved below code as jquery changed the resulten element so now using class.
		*/
		/* $(".whizz_color_container .wp-picker-container > a").removeAttr('style'); */
		$(".wp-picker-container .wp-color-result").removeAttr('style');
		$('html,body').css('cursor','default');
		$('#DivToShow').removeAttr('style');
		$("#adminmenu > li >a").css("cursor", 'auto');
		$("#adminmenu li >a").hover(function()
		{
			$(this).css("cursor", 'pointer');
		});
		$("#adminmenu li").hover(function()
		{
			$(this).css("cursor", 'pointer');
		});
		$("#adminbarnew_bar li >a").hover(function()
		{
			$(this).css("cursor", 'pointer');
		});
	}
	/*ajax call function to save and retrieve user color settings for background color START */
	
	function whizz_fill_ids_colors()
	{
		$.ajax({
			url: main_js_obj_color_picker.admin_ajax_url,
			type: "POST",
			data:{
				action: 'whizz_get_color_of_menu'
			},
			dataType: "json"
			}).done(function( r ) {	
				if( r.success )
				{
					var data_obj = r.data;
					var css_str = "";
					for( x in data_obj )
					{
						css_str += data_obj[x] +" ";
					}
					$('body').append("<style>"+ css_str +"</style>");
					for( x in data_obj )
					{
						$("#"+x).addClass(x+"s");
					}
				}
				else 
				{
					/*console.log('Not Successful.');*/
				}
			}).fail(function( jqXHR, textStatus ) {
				/*console.log('Not Successful.');*/
			});
	}
	/*ajax call function to save and retrieve user color settings for background color END */
	/*ajax call function to save and retrieve user color settings for Hover color START */	
	function whizz_save_id_color_hover(menu_id, menu_color, save_color_hover_nonce)
	{
		$.ajax({
			url: main_js_obj_color_picker.admin_ajax_url,
			type: "POST",
			data:{
				action: 'whizz_save_color_of_menu_hover',
				menu_id: menu_id,
				menu_color: menu_color,
				wp_nonce_hover : save_color_hover_nonce,
			},
			dataType: "json"
			}).done(function( r ) {	
				if( r.success )
				{
					/*console.log('success');*/
				}
				else 
				{
					/*console.log('fail');*/
				}
			}).fail(function( jqXHR, textStatus ) {
				/*console.log('fail');*/
			});
	}
	function whizz_fill_ids_colors_hover()
	{
		$.ajax({
			url: main_js_obj_color_picker.admin_ajax_url,
			type: "POST",
			data:{
				action: 'whizz_get_color_of_menu_hover'
			},
			dataType: "json"
			}).done(function( r ) {	
				if( r.success )
				{
					var data_obj = r.data;
					var css_str = "";
					for( x in data_obj )
					{
						css_str += data_obj[x] +" ";
					}
					$('body').append("<style>"+ css_str +"</style>");
					for( x in data_obj )
					{
						$("#"+x).addClass(x+"hover");
					}
				}
				else 
				{
					/*console.log('Not Successful.');*/
				}
			}).fail(function( jqXHR, textStatus ) {
				/*console.log('Not Successful.');*/
			});
	}
	/*ajax call function to save and retrieve user color settings for Hover color START */
})(jQuery)
function whizz_save_id_color(menu_id, menu_color, save_color_nonce)
{
	jQuery.ajax({
		url: main_js_obj_color_picker.admin_ajax_url,
		type: "POST",
		data:{
			action: 'whizz_save_color_of_menu',
			
			menu_id: menu_id,
			menu_color: menu_color,
			wp_nonce : save_color_nonce,
		},
		dataType: "json"
		}).done(function( r ) {	
			if( r.success )
			{
				/*console.log('success');*/ 
			}
			else 
			{
				/*console.log('fail');*/
			}
		}).fail(function( jqXHR, textStatus ) {
			/*console.log('fail');*/
		});
}