var order_set="no";


var current_order="";


var page_no=1;


var search_set_save="no";


var search_term_save="";


(function($){


	$(document).ready(function(e) {		


		$(document).keypress(function(e) {


			if(e.which == 13) {


				if($("#search_users").val() && $("#search_users").val() != "")


				{


					e.preventDefault();


					$("#search_users_submit").click();


				}


			}


		});


		


		$("#list_view_user").sortable({


			stop: function(event, ui)


			{


				whizz_save_new_user_order();


			}


		});


		$("#ascending_order").click(function(e) {


            $(this).hide();


			$("#descending_order").show();


			order_by('ascending');


			order_set="yes";


			current_order="ascending";


			page_no=1;


        });


		$("#descending_order").click(function(e) {


            $(this).hide();


			$("#ascending_order").show();


			order_by('descending');


			order_set="yes";


			current_order="descending";


			page_no=1;


        });


		/* paging START */


		$("#paging_loader").hide();


		$("#loader_top").hide();


		$("#previous_links").click(function(e) {


			if(page_no > 1)


			{


				var position = 'previous';


				whizz_paging_users(page_no, position);


			}


        });


		$("#next_links").click(function(e) {


				var position = 'next';


        		whizz_paging_users(page_no, position);


        });

		var nonce_paging =  $("#wpnonce_itemper_page").val();
		
		$("#item_per_page").change(function(e){


            $.ajax({


				url: main_js_obj_user.admin_ajax_url,


				type: "POST",


				data:{


					action: 'whizz_save_item_per_page',


					item_per_page :$("#item_per_page").val(),
					
					nonce_paging_item_per_page : nonce_paging,


				},


			}).done(function( r ) {	


				if( r.success )


				{


					/*console.log('success');*/	


					location.href=location.href;	


				}


				else


				{


					/*console.log('fail');*/


				}


			}).fail(function( jqXHR, textStatus ) {


				/*console.log('fail');*/


			});	


        });


		/* paging END */


		$("#search_users_submit").click(function(e) {


			search_set_save="yes";


			search_term_save=$("#search_users").val();


			$("#loader_top").show();
			
			var nonce_user_search = $("#wpnonce_search_users_submit").val();


            $.ajax({


			url: main_js_obj_user.admin_ajax_url,


			type: "POST",


			data:{


				action: 'whizz_search_users_admin',


				search_term :$("#search_users").val(),


				view:whizz_getParameterByNameu('view'),


				list_of:whizz_getParameterByNameu('list_of'),
				
				nonce_user_search_submit : nonce_user_search,


			},


			}).done(function( r ) {	


				if( r.success )


				{


					$("#users_list_admin").html(r.data);


					$("#loader_top").hide();


				}


				else


				{


					/*console.log('fail');*/


					$("#loader_top").hide();


				}


			}).fail(function( jqXHR, textStatus ) {


				/*console.log('fail');*/


				$("#loader_top").hide();


			});


        });


		$("#reset_to_default_order").click(function(e) {


			$("#loader_top").show();


			var list_ofc = whizz_getParameterByNameu('list_of');


			if(list_ofc)


			{


			}


			else


			{


				list_ofc = "all_users";


			}


			
			var nonce_reset_defaultorder = $("#wpnonce_reset_to_default_order").val();

            $.ajax({


			url: main_js_obj_user.admin_ajax_url,


			type: "POST",


			data:{


				action: 'whizz_reset_to_default_order',


				list_of :list_ofc,
				
				nonce_user_reset : nonce_reset_defaultorder,


			},


			}).done(function( r ) {	


				if( r.success )


				{


					$("#loader_top").hide();


					location.href=location.href;


				}


				else


				{


					/*console.log('fail');*/


					$("#loader_top").hide();


				}


			}).fail(function( jqXHR, textStatus ) {


				/*console.log('fail');*/


				$("#loader_top").hide();


			});


        });


		


		$("#select_all").click(function(e) {


            if($("#select_all").is(":checked") == true)


			{


				$('#users_list_admin input[type=checkbox]').each(function() {


				  $(this).attr('checked','checked');


				});


			}


			else


			{


				$('#users_list_admin input[type=checkbox]').each(function() {


				  $(this).removeAttr('checked');


				});


			}


        });


		


	});


	/*ajax call function to save and retrieve users START */


	function whizz_save_new_user_order()


	{


		new_order_user = {};


		jQuery("#list_view_user li").each(function(ind,ele){


			var mkey = $(this).find('.user_id_h').val();


			new_order_user[mkey+"ID"]={


									'id':mkey


								}


		});


		var list_of = whizz_getParameterByNameu('list_of');


		if(list_of && list_of == "all_users")


		{


			list_of = "all_users";


		}


		else if(list_of)


		{


		}


		else 


		{


			list_of = "all_users";


		}	
		
		var user_reordering = $('#wpnonce_user_list_reordering').val();

		$.ajax({


			url: main_js_obj_user.admin_ajax_url,


			type: "POST",


			data:{


				action: 'whizz_save_new_user_order_custom',


				user_order: JSON.stringify(new_order_user),


				list_of_u :list_of,
				
				nonce_user_reorder : user_reordering,


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


	function whizz_getParameterByNameu(name) 


	{


		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");


		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),


			results = regex.exec(location.search);


		return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));


	}


	function order_by(order)


	{


		$("#users_list_admin").html("<img src='"+main_js_obj_user.for_plugin_url+"img/ajax-loader.gif'>");


		var cview = whizz_getParameterByNameu('view');


		if(!cview && cview == "")


		{


			cview = "list_view";


		}


		var clist_of = whizz_getParameterByNameu('list_of');


		if(clist_of && clist_of == "all_users")


		{


			clist_of = "all_users";


		}


		else if(clist_of)


		{


		}


		else 


		{


			clist_of = "all_users";


		}


		


		if(clist_of == "my_list")


		{

			var nonce_my_list = $("#wpnonce_user_roles").val();
			
			$.ajax({


				url: main_js_obj_user.admin_ajax_url,


				type: "POST",


				data:{


					action: 'whizz_order_by_users_my_list',


					orderby :order,


					view:cview,


					list_of:clist_of,


					search_set:search_set_save,


					search_term:search_term_save,
					
					wpnonce_user_cap : nonce_my_list,


				},


				}).done(function( r ) {	


					if( r.success )


					{


						$("#users_list_admin").html(r.data);


					}


					else


					{


						/*console.log('fail');*/


					}


				}).fail(function( jqXHR, textStatus ) {


				/*console.log('fail');*/


			});


		}


		else


		{
			
			var nonce_my_list = $("#wpnonce_user_roles").val();
			
			$.ajax({


				url: main_js_obj_user.admin_ajax_url,


				type: "POST",


				data:{


					action: 'whizz_order_by_users',


					orderby :order,


					view:cview,


					list_of:clist_of,


					search_set:search_set_save,


					search_term:search_term_save,
					
					wpnonce_user_cap : nonce_my_list,


				},


				}).done(function( r ) {	


					if( r.success )


					{


						$("#users_list_admin").html(r.data);


					}


					else


					{


						/*console.log('fail');*/


					}


				}).fail(function( jqXHR, textStatus ) {


				/*console.log('fail');*/


			});


		}


	}


	function whizz_paging_users(cpage_no, position_d)


	{


		$("#no_more_records").html('');


		$("#paging_loader").show();


		var cview = whizz_getParameterByNameu('view');


		if(!cview && cview == "")


		{


			cview = "list_view";


		}


		var clist_of = whizz_getParameterByNameu('list_of');


		if(clist_of && clist_of == "all_users")


		{


			clist_of = "all_users";


		}


		else if(clist_of)


		{


		}


		else 


		{


			clist_of = "all_users";


		}


		var order = "ascending";


		if(order_set && order_set=="yes")


		{


			order=current_order;


		}


		if(clist_of == "my_list")


		{

			var nonce_paging =  $("#wpnonce_itemper_page").val();

			$.ajax({


				url: main_js_obj_user.admin_ajax_url,


				type: "POST",


				data:{


					action: 'whizz_paging_users_my_list',


					orderby :order,


					view:cview,


					list_of:clist_of,


					page_no:cpage_no,


					orderd:order_set,


					position:position_d,


					search_set:search_set_save,


					search_term:search_term_save,
					
					nonce_paging_item_per_page : nonce_paging,

				},


			}).done(function( r ) {	


				if( r.success )


				{


					$("#users_list_admin").html(r.data);


					if(position_d == 'previous')


					{


						page_no--;


					}


					else


					{


						page_no++;


					}


					$("#paging_loader").hide();	


				}


				else


				{


					/*console.log('fail');*/


					$("#no_more_records").html('No more records.');


					$("#paging_loader").hide();	


				}


			}).fail(function( jqXHR, textStatus ) {


				/*console.log('fail');*/


				$("#paging_loader").hide();	


			});	


		}


		else


		{

			var nonce_paging_user = $("#wpnonce_user_next_prev_link").val();

			$.ajax({


			url: main_js_obj_user.admin_ajax_url,


			type: "POST",


			data:{


				action: 'whizz_paging_users',


				orderby :order,


				view:cview,


				list_of:clist_of,


				page_no:cpage_no,


				orderd:order_set,


				position:position_d,


				search_set:search_set_save,


				search_term:search_term_save,
				
				nonce_next_prev_paging : nonce_paging_user,


			},


			}).done(function( r ) {	


				if( r.success )


				{


					$("#users_list_admin").html(r.data);


					if(position_d == 'previous')


					{


						page_no--;


					}


					else


					{


						page_no++;


					}


					$("#paging_loader").hide();	


				}


				else


				{


					/*console.log('fail');*/


					$("#no_more_records").html('No more records.');


					$("#paging_loader").hide();	


				}


			}).fail(function( jqXHR, textStatus ) {


				/*console.log('fail');*/


				$("#paging_loader").hide();	


			});	


		}


	}


})(jQuery)