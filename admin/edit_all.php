<?php
	include_once("../classes/init.php");
	$auth->adminPage();

	if(!empty($_GET['model'])) {
		$model = $_GET['model'];
		$table_name = Inflector::delimit($model);
		$pluralModel = ucfirst($model);
		$singleModel = Inflector::singularize($pluralModel);

		if(!class_exists($singleModel) || !class_exists($pluralModel)) {
			header('Location: index.php');
		}

		$modelObject = new $pluralModel();

	} else {
		header('Location: index.php');
	}

 ?>
<!DOCTYPE html>
<html lang="en">

<head>
		<?php include_once('includes/sources.php'); ?>
		<link rel="stylesheet" type="text/css" href="/assets/admin.css"/>
		<link rel="stylesheet" type="text/css" href="/assets/js/datatables/datatables.jqueryui.min.css"/>
		<link rel='stylesheet' href='/assets/js/jquery-ui-1.11.4.custom/jquery-ui.min.css' type='text/css'/>

		<link rel='stylesheet' href='/assets/js/jquery-ui-1.11.4.custom/jquery-ui.theme.min.css' type='text/css'/>
</head>

<body class="page-body">
    <div class="page-container">
        <?php include_once('includes/sidebar.php'); ?>
        <div class="main-content">
            <div class="row">
                <div class="col-xs-12 clearfix">
									<h3 class="center">Edit <?php echo Inflector::humanize(Inflector::delimit($pluralModel)); ?>
										<a href="/admin/new.php?model=<?php echo $model; ?>" class="btn btn-success btn-sm pull-right">New <?php echo Inflector::humanize(Inflector::delimit($singleModel)); ?></a>
									</h3>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-xs-12">
							<table class="table table-bordered table-condensed admin-table" id="edit_all_table">



								<thead>
								<tr>
									<?php
										foreach(DB::getColumnsHuman($table_name) as $column) {
											echo '<th>' . Inflector::humanize($column) . '</th>';
										}
									 ?>
									<th>Options</th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach($modelObject::findAll() as $row) {
											echo '<tr>';
										foreach(DB::getColumnsHuman($table_name) as $column) {
											echo '<td>' . $row->getString($column) . '</td>';
										}
										echo '<td>';
										echo '<a class="btn btn-warning btn-xs" href="/admin/edit_single.php?model=' . $model . '&id=' . $row->get('id') . '">Edit</a> ';
										echo '<button class="btn btn-danger btn-xs" onclick="deleteEntry(\'' . $model . '\', ' . $row->get('id') . ');">Delete</button>';
										echo '</td>';
										echo '</tr>';
									}

								 ?>
							 </tbody>
							</table>
						</div>

				</div>


		</div>


	</div>





<!-- CONFIRM DELETE modal -->

	 <div id="confirmModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="ConfirmModal" aria-hidden="true">
		 <div class="modal-dialog">
			 <div class="modal-content">
					 <div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							 <h4 class="modal-title" id="myModalLabel">Are you sure?</h4>
					 </div>
					 <div class="modal-body">You are about to delete a <?php echo Inflector::humanize(Inflector::delimit($singleModel)); ?> entry. Continue?</div>
					 <div class="modal-footer">
							 <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
							 <a href="#" id="deleteLink" class="btn btn-danger">Yes</a>
					 </div>
			 </div>
		 </div>
	 </div>
<!-- END CONFIRM DELETE modal -->


	<?php include_once('includes/footer.php'); ?>

<script type="text/javascript" src="/assets/js/datatables/jquery.datatables.min.js"></script>
<script type="text/javascript" src="/assets/js/datatables/datatables.jqueryui.min.js"></script>

<script>

function deleteEntry(model, id) {
		var deleteLink = '/admin/action.php?a=delete&model=' + model + '&id=' + id;
		jQuery('#deleteLink').prop('href', deleteLink);
		jQuery('#confirmModal').modal('show');
}

jQuery(function($) {
  jQuery( "#edit_all_table" ).DataTable();


});
</script>
</body>
</html>
