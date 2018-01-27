<?php
	include_once("../classes/init.php");

  if($auth->isAdmin()) {

	if(!empty($_GET['model']) && !empty($_GET['id'])) {

		$model = $_GET['model'];
		$model = preg_replace('/[0-9]+/', '', $model);
		$table_name = Inflector::delimit($model);
		$pluralModel = ucfirst($model);
		$singleModel = Inflector::singularize($pluralModel);

    $error = false;
    $is_dialog = false;
    if(!empty($_GET['dialog']) && $_GET['dialog'] == 1) {
      $is_dialog = true;
    }

		if(!class_exists($singleModel) || !class_exists($pluralModel)) {
      $error = true;
		}

		$modelObject = new $pluralModel();

		$object_id = intval($_GET['id']);

		$object = $modelObject::find($object_id);

	} else {
    $error = true;
  }

if($error == false) {

  if($table_name != 'images') {


    $script='';
    echo '<div id="dialog-form_' . $model . '">
            <div id="dialog-form-result_' . $model . '"></div>
    ';
      foreach(DB::getColumns($table_name) as $column) {

        $column_name = $column["COLUMN_NAME"];

        if($column_name != "id") {
          echo '<div class="form-group">';
          if(InferCMS::endsWith($column_name, '_id')) {
            $column_item = Inflector::humanize(str_replace('_id', '', $column_name));
            $column_item_table = Inflector::pluralize(str_replace('_id', '', $column_name));
            echo '
              <label>' . $column_item . '</label><br>
              <button class="btn btn-info btn-xs" id="dialog-trigger_' . $column_name . '">Choose ' . (InferCMS::startsWithVowel($column_item) ? 'an' : 'a') . ' ' . strtolower($column_item) . '</button>
              <input type="hidden" name="' . $column_name . '" id="input_' . $column_name . '">
              <div class="jqueryui_dialog" id="dialog_' . $column_name . '" title="Choose ' . (InferCMS::startsWithVowel($column_item) ? 'an' : 'a') . ' ' . strtolower($column_item) . '">
                <div class="dialog-loader" id="dialog-loader_' . $column_name . '"></div>
              </div>';


            $script .= '

                  jQuery( "#dialog_' . $column_name . '" ).dialog({
                    autoOpen: false,
                    height: 650,
                    width: 850,
                    modal: true
                  });
                  jQuery( "#dialog-trigger_' . $column_name . '" ).click(function() {
                    jQuery( "#dialog_' . $column_name . '" ).load( "/admin/partial_choose.php?model=' . $column_item_table . '&parent=' . $model . '" );
                    jQuery( "#dialog_' . $column_name . '" ).dialog( "open" );
                  });

									setRow(\'' . $column_name . '\', \'' . $object->get($column_name) . '\', \'' . $model . '\', \'' . htmlspecialchars($object->getString(str_replace('_id', '', $column_name))) . '\');

              ';

          } else {
						echo '<label>' . Inflector::humanize($column_name) . '</label><br>';
						if($column['DATA_TYPE'] == 'tinyint') {
							echo '<input class="input-large" value="1" type="checkbox" id="input_' . $column_name . '" name="' . $column_name . '" ' . ($object->get($column_name) != 0 ? 'checked="checked"' : '') . '>';
						} elseif($column['DATA_TYPE'] == 'longtext') {
							echo '<textarea class="input-large" id="input_' . $column_name . '" name="' . $column_name . '"></textarea>';
							$script .= '
								var simplemde = new SimpleMDE({
									hideIcons: ["fullscreen", "side-by-side"],
									spellChecker: false,
									forceSync: true,
									element: document.getElementById("input_' . $column_name . '")
								});

								simplemde.value(' . json_encode($object->get($column_name)) . ');
							';
						} elseif($column['DATA_TYPE'] == 'datetime') {
							echo '<input type="text" id="input_' . $column_name . '" name="' . $column_name . '" value="' . date("j F Y", strtotime($object->get($column_name))) . '">';
							$script .= '
								jQuery( "#input_' . $column_name . '" ).datepicker({
									dateFormat: "d MM yy"

								});
							';
						} else {
							echo '<input class="input-large" type="text" id="input_' . $column_name . '" name="' . $column_name . '" value="' . $object->get($column_name) . '">';
						}
          }
          echo '</div>';


        }



      }


      ?>
      <button name="submit" onclick="submitEditForm('<?php echo $model; ?>', <?php echo $object_id; ?>);" id="dialog-submit-button_<?php echo $model; ?>" type="submit" class="btn btn-sm btn-info pull-right" data-loading-text="Loading...">Save</button>
    </div>


    <script>

jQuery(function($) {
    	<?php echo $script; ?>
});

    </script>

  <?php } else { // is form for images ?>




  <?php } // end form for images ?>



<?php } else { // error is true
  echo '<p>An error has occurred.</p>';
}
?>

<?php } else { // end if is admin
  echo 'Please <a href="/login">log in</a> to view this page.';
} ?>
