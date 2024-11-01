jQuery(document).ready(function(e) {

	get_seperator();

	jQuery("#seperator_to_select").sortable({

		start:function( event, ui )

		{

			(ui.item).removeClass('seperator_to_select_li');

			(ui.item).addClass('seperator_to_select_li_after_drag wp-has-submenu');

		},

		stop:function( event, ui )

		{

			jQuery("#seperator_to_select").html('<li class="seperator_to_select_li"></li>');

		},

		connectWith: "#adminmenu",

		helper: "clone",

		revert: true

	});

	jQuery("#reset_separators").click(function(e) {

        whizz_reset_separators();

    });

	jQuery("#whizz_latest_update_head").click(function(e) {

		jQuery("#whizz_latest_update_head").removeClass('whizz_latest_support_tab_inactive').addClass('whizz_latest_support_tab_active');

		jQuery("#whizz_update_history_head").removeClass('whizz_latest_support_tab_active').addClass('whizz_latest_support_tab_inactive');

        jQuery("#whizz_latest_update").show();

		jQuery("#whizz_update_history").hide();

    });

	jQuery("#whizz_update_history_head").click(function(e) {

		jQuery("#whizz_update_history_head").removeClass('whizz_latest_support_tab_inactive').addClass('whizz_latest_support_tab_active');

		jQuery("#whizz_latest_update_head").removeClass('whizz_latest_support_tab_active').addClass('whizz_latest_support_tab_inactive');

        jQuery("#whizz_latest_update").hide();

		jQuery("#whizz_update_history").show();

    });

});

function get_seperator()

{

	jQuery.ajax({

		url: main_js_obj_horizontal.admin_ajax_url,

		type: "POST",

		data:{

			action: 'whizz_get_separators'

		},

		dataType: "json"

	}).done(function( r ) {	

		if( r.success )

		{

			if(r.data)

			{

				var i=0;

				jQuery("#adminmenu > li").each(function(ind, ele) {

					var found = 0;

					jQuery.each(r.data, function(index, element){

						if(element == i)

						{

							found = 1;

						}

					});

					if(found == 1)

					{

						jQuery("<li id='sep_"+i+"' class='seperator_to_select_li_after_drag wp-has-submenu sep_"+i+"s' style='display: list-item;'></li>").insertBefore(this);

					}

					i++;
					jQuery(window).resize();
				});

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

function whizz_reset_separators()

{

	jQuery.ajax({

		url: main_js_obj_horizontal.admin_ajax_url,

		type: "POST",

		data:{

			action: 'whizz_reset_separators'

		},

		dataType: "json"

	}).done(function( r ) {	

		if( r.success )

		{

			location.href = location.href;

		}

		else 

		{

			/*console.log('Not Successful.');*/

		}

	}).fail(function( jqXHR, textStatus ) {

		/*console.log('Not Successful.');*/

	});

}