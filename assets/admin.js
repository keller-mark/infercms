function getNewForm(model) {
  jQuery("#dialog-choose_" + model).hide();
  jQuery("#dialog-new_" + model).show();
  jQuery("#dialog-new-inner_" + model).load("/admin/partial_new.php?model=" + model + "&dialog=1");
}
function closeNewForm(model, parent) {
  jQuery("#dialog-choose_" + model).show();
  jQuery("#dialog-new_" + model).hide();
  jQuery("#dialog-inner_" + model).load('/admin/partial_choose.php?model=' + model + '&parent=' + parent);
}


function submitNewForm(model, dialog) {
		console.log('submitted');
		jQuery('#dialog-submit-button_' + model).button('loading');

		var theData = jQuery('#dialog-form_' + model + ' :input').serialize();

		jQuery.ajax({
				url: '/admin/action.php?a=new&model=' + model,
				dataType: 'json',
				type: 'post',
				data: theData,
				success: function(data) {
						console.log('success');
						if(data.success) {
							jQuery('#dialog-form_' + model + ' .form-control').val('');
							if(!dialog) {
								window.location = "/admin/edit_all.php?model=" + model;
						
							} else {
							  var parent = jQuery("#dialog-form_" + model).parent().parent().parent().attr("data-parent");

							  closeNewForm(model, parent);
							}
						}
						jQuery('#dialog-submit-button_' + model).button('reset');
						jQuery('#dialog-form-result_' + model + '').html(data.result);
						
				}
		});
}

function chooseRow(model, id, parent, itemString) {
	jQuery('#dialog-form_' + parent + ' #input_' + model).val(id);
	jQuery('#dialog-form_' + parent + ' #dialog-trigger_' + model).html('Chosen: ' + itemString);
	jQuery('#dialog-form_' + parent + ' #dialog-trigger_' + model).css('background-color', 'green');

	jQuery( '#dialog_' + model ).dialog( "close" );
}



function submitEditForm(model, object_id) {
		console.log('submitted');
		jQuery('#dialog-submit-button_' + model).button('loading');

		var theData = jQuery('#dialog-form_' + model + ' :input').serialize();

		jQuery.ajax({
				url: '/admin/action.php?a=edit&model=' + model + '&id=' + object_id + '',
				dataType: 'json',
				type: 'post',
				data: theData,
				success: function(data) {
						console.log('success');
						if(data.success) {
								window.location = "/admin/edit_all.php?model=" + model;

						}
						jQuery('#dialog-form-result_' + model + '').html(data.result);
						jQuery('#dialog-submit-button_' + model).button("reset");
				}
		});
}



function setRow(model, id, parent, itemString) {
	var decoded = jQuery("<div/>").html(itemString).text();

	jQuery('#dialog-form_' + parent + ' #input_' + model).val(id);
	jQuery('#dialog-form_' + parent + ' #dialog-trigger_' + model).html('Chosen: ' + decoded);
	jQuery('#dialog-form_' + parent + ' #dialog-trigger_' + model).css('background-color', 'green');
}
