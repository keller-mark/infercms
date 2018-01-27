

	  function xhr_send(f, e) {
		if (f) {
		  var formData = new FormData();
			formData.append("File", f);




		  xhr.onreadystatechange = function(){
			if(xhr.readyState == 4){
			  document.getElementById(e).innerHTML = xhr.responseText;
			   jQuery('#loaderIcon').hide();
			   jQuery('#xhr_upload').show();

			   ajaxSuccess();
			}
		  }

		  beforeSubmit();
		  jQuery.each(parameters, function(key, value) {
		    	formData.append(key,value);
		  });
		  var uploadURL = "action.php?a=" + action;

		  // jQuery('#xhr_status').html(uploadURL);

		  xhr.open("POST", uploadURL);
		  xhr.setRequestHeader("Cache-Control", "no-cache");
		  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		  xhr.setRequestHeader("X-File-Name", f.name);
		  xhr.send(formData);
		}
	  }

	  function xhr_parse(f, e) {
		if (f) {
		  //document.getElementById(e).innerHTML = "File selected: " + f.name + " (" + f.type + ")";
		} else {
		  document.getElementById(e).innerHTML = "No file selected!";
		}
	  }

	  var xhr = new XMLHttpRequest();

	  if (xhr && window.File && window.FileList) {

		// xhr example
		var xhr_file = null;
		jQuery('#xhr_upload').prop('disabled', true);
		document.getElementById("xhr_field").onchange = function () {
		  xhr_file = this.files[0];
		  if(xhr_file) {
		  	jQuery('#xhr_upload').prop('disabled', false);
		  }
		  xhr_parse(xhr_file, "xhr_status");
		}
		document.getElementById("xhr_upload").onclick = function () {
		  xhr_send(xhr_file, "xhr_result");
		  jQuery('#loaderIcon').show();
		  jQuery('#xhr_upload').hide();
		}



	  }
