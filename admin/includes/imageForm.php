<div id="dialog-form_<?php echo $model; ?>">
              <div id="xhr_status"></div>
            <div id="xhr_result"></div>

            <div id="ajaxForm" class="ajaxForm">
                <div class="form-group">
									<label>Title</label>
									<input class="input-large" type="text" id="imageTitle">

                		</div>
						 <div class="form-group">
								<label class="imageInput">Image File</label><br class="imageInput">
								<input class="imageInput" accept="image/*" type="file" size="32" id="xhr_field" /><br class="imageInput">
						</div>

						<i id="loaderIcon" style="display: none;" class="fa fa-2x fa-spinner fa-spin"></i>
						<p class="button">
						  <button class="btn btn-sm btn-info pull-right" id="xhr_upload">Submit</button>
            </p>
					</div>
        </div>

					<script type="text/javascript">


                        var action = 'newImage';


                        var parameters;

                        function beforeSubmit() {
                        	var imageTitle = $("#imageTitle").val();
	                        parameters = {"title": imageTitle};

                        }




                        function ajaxSuccess(){
              							if($("#notification").data('success') == true) {
                                $('#ajaxForm input').val('');
                                var model = '<?php echo $model; ?>';
                                var parent = jQuery("#dialog-form_" + model).parent().parent().parent().attr("data-parent");

                                closeNewForm(model, parent);
              							}
                          }


                        <?php include_once('ajaxUpload.php'); ?>



      </script>
