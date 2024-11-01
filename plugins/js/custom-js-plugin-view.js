(function($){


	$(document).ready(function(e) {		


		$(document).keypress(function(e) {


			if(e.which == 13) {


				if($("#search_term_plugin").val() && $("#search_term_plugin").val() != "")


				{

					e.preventDefault();


					$("#search_submit").click();


				}


			}


		});
	


		$("#ajax_loader").hide();


		$("#list_view_plugin").sortable({


			stop: function(event, ui)


			{


				whizz_save_new_plugin_order();


			}


		});	


		


		$("#search_submit").click(function(e){			


            var view_c= whizz_getParameterByName('view');
			
			


			if(!view_c)


			{


				view_c = "list_view";


			}


			if($("#search_term_plugin").val() != "")


			{


				jQuery("#ajax_loader").show();

				var nonce_plugin_order = $('#wpnonce_search_submit').val();
				
				console.log(nonce_plugin_order);
				
				$.ajax({


					url: main_js_obj_plugin.admin_ajax_url,		


					type: "POST",		


					data:{		


						action: 'whizz_search_plugin_wp_admin',		


						search_term: $("#search_term_plugin").val(),


						view :view_c,
									
						nonce_search_submit_plugin : nonce_plugin_order,
  
				},		


				}).done(function( r ) {			


					if( r.success )		


					{		


						$("#plugin_list_td").html(r.data);


						jQuery("#ajax_loader").hide();


					}		


					else 		


					{		


						/*console.log('fail');*/


						jQuery("#ajax_loader").hide();


					}		


				}).fail(function( jqXHR, textStatus ) {		


					/*console.log('fail');*/	


					jQuery("#ajax_loader").hide();	


				});	


			}			


        });


		


		$("#select_all").click(function(e) {


            if($("#select_all").is(":checked") == true)


			{


				$('#plugin_list_td input[type=checkbox]').each(function() {


				  $(this).attr('checked','checked');


				});


			}


			else


			{


				$('#plugin_list_td input[type=checkbox]').each(function() {


				  $(this).removeAttr('checked');


				});


			}


        });


		


	});


	/*ajax call function to save and retrieve user color settings for background color START */


	function whizz_save_new_plugin_order()


	{


		new_order_plugin = {};
		
		jQuery("#list_view_plugin >li").each(function(ind,ele){


			var mkey = $(ele).attr('id');


			var description;


			var version;


			var plugin_name;


			var aurl;


			var plugin_author_name;


			var str = location.href;
			
			if(str.search("grid_view") >= 0)


			{


				description = $(this).find('.plugin_desc_grid').text();


				version = $(this).find('.plugin_version_grid').text();


				plugin_author_name = $(this).find('.plugin_author_name_grid').text();


				aurl = $(this).find('.plugin_uri_grid a').attr('href');


				plugin_name = $(this).find('.plugin_name_grid').text();


			}


			else


			{


				description = $(this).find('.plugin_desc_list').text();


				version = $(this).find('.plugin_version_list').text();


				plugin_author_name = $(this).find('.plugin_author_name_list').text();


				aurl = $(this).find('.plugin_uri_list a').attr('href');


				plugin_name = $(this).find('.plugin_name_list').text();


			}


			new_order_plugin[mkey]={


										'Description':jQuery.trim(description),


										'Version':jQuery.trim(version),


										'AuthorName':jQuery.trim(plugin_author_name),


										'PluginURI':jQuery.trim(aurl),


										'Name':jQuery.trim(plugin_name),


									}


		});


		var list_of = whizz_getParameterByName('list_of');
		
		if(list_of == "active_plugins")


		{


			list_of = "active_plugins";


		}


		else if(list_of == "inactive_plugins")


		{


			list_of = "inactive_plugins";


		}


		else if(list_of == "active_update_available")


		{


			list_of = "active_update_available";


		}


		else if(list_of == "inactive_update_available")


		{


			list_of = "inactive_update_available";


		}


		else if(list_of == "my_preferences")


		{


			list_of = "my_preferences";


		}


		else


		{


			list_of = "all_plugins";


		}

			var plugin_reordering = $('#wpnonce_plugin_reordering').val();
			
		
		$.ajax({


			url: main_js_obj_plugin.admin_ajax_url,


			type: "POST",


			data:{


				action: 'whizz_save_new_plugin_order_custom',


				plugin_order: JSON.stringify(new_order_plugin),


				list_of_p :list_of,
				
				nonce_plugin_order1 : plugin_reordering,
				
		},


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


})(jQuery)


function whizz_getParameterByName(name)


{


	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");


	var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),


	results = regex.exec(location.search);


	return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));


}