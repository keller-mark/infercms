<?php
include_once("../classes/init.php");

if($auth->isAdmin()) {

if(!empty($_GET['model']) && !empty($_GET['parent'])) {
  $model = $_GET['model'];
  $parent = $_GET['parent'];

  $parent_row = Inflector::singularize($model) . '_id';


  $model = preg_replace('/[0-9]+/', '', $model);
  $table_name = Inflector::delimit($model);
  $pluralModel = ucfirst($model);
  $singleModel = Inflector::singularize($pluralModel);



  if(!class_exists($singleModel) || !class_exists($pluralModel)) {
    echo '<p>An error occurred.</p>';
  }

  $modelObject = new $pluralModel();

} else {
  echo '<p>An error occurred.</p>';
}


?>
<div data-parent="<?php echo $parent; ?>" id="dialog-inner_<?php echo $model; ?>">
  <div id="dialog-new_<?php echo $model; ?>">
    <p onclick="closeNewForm('<?php echo $model; ?>', '<?php echo $parent; ?>');" class="admin-back-button">< Back to choose</p>
    <div id="dialog-new-inner_<?php echo $model; ?>"></div>
  </div>
  <div id="dialog-choose_<?php echo $model; ?>">
    <button class="btn btn-block btn-sm btn-info" onclick="getNewForm('<?php echo $model; ?>');">New</button>
    <br>
    <table class="table table-condensed admin-choose-table" id="dialog-table_<?php echo $model; ?>">
    <thead>
      <tr>
        <?php
          foreach(DB::getColumnsHuman($table_name) as $column) {
            echo '<th>' . Inflector::humanize($column) . '</th>';
          }
         ?>
      </tr>
    </thead>
    <tbody>
      <?php
        foreach($modelObject::findAll() as $row) {
            echo '<tr onclick="chooseRow(\'' . $parent_row . '\', \'' . $row->get('id') . '\', \'' . $parent . '\', \'' . htmlspecialchars($row->toString()) . '\');">';
          foreach(DB::getColumnsHuman($table_name) as $column) {
            if($table_name == 'images' && $column == 'link') {
              echo '<td><img height="100" src="' . $settings['upload_root'] . $row->getString($column) . '"></td>';
            } else {
              echo '<td>' . $row->getString($column) . '</td>';
            }

          }
          echo '</tr>';
        }

       ?>
     </tbody>
    </table>
  </div>
  <script>
  jQuery(document).ready(function() {
    jQuery("#dialog-new_<?php echo $model; ?>").hide();
    jQuery( "#dialog-table_<?php echo $model; ?>" ).DataTable();
  });


  </script>
</div>

<?php } else { // end if is admin
  echo 'Please <a href="/login">log in</a> to view this page.';
} ?>
