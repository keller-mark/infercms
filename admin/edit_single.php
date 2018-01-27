<?php
	include_once("../classes/init.php");
	$auth->adminPage();

	if(!empty($_GET['model']) && !empty($_GET['id'])) {

		$model = $_GET['model'];
		$table_name = Inflector::delimit($model);
		$pluralModel = ucfirst($model);
		$singleModel = Inflector::singularize($pluralModel);

		if(!class_exists($singleModel) || !class_exists($pluralModel)) {
			header('Location: index.php');
		}

		$modelObject = new $pluralModel();
		$object_id = intval($_GET['id']);

		$object = $modelObject::find($object_id);


	} else {
		header('Location: index.php');
	}

 ?>
 <!DOCTYPE html>
 <html lang="en">

 <head>
 	 <?php include_once('includes/sources.php'); ?>

	<link rel='stylesheet' href='/assets/js/jquery-ui-1.11.4.custom/jquery-ui.min.css' type='text/css'/>
	<link rel='stylesheet' href='/assets/js/jquery-ui-1.11.4.custom/jquery-ui.theme.min.css' type='text/css'/>
	<!--<link rel='stylesheet' href='/assets/js/pacejs/theme.css' type='text/css'/>-->
	<link rel="stylesheet" type="text/css" href="/assets/js/datatables/datatables.jqueryui.min.css"/>
	<link rel="stylesheet" type="text/css" href="/assets/admin.css"/>
	<link rel="stylesheet" type="text/css" href="/assets/js/simplemde/simplemde.min.css"/>



</head>

<body class="page-body">
    <div class="page-container">
        <?php include_once('includes/sidebar.php'); ?>
        <div class="main-content">
            <div class="row">
                <div class="col-xs-12 clearfix">

								<h3 class="center">Edit <?php echo Inflector::humanize(Inflector::delimit($singleModel)); ?></h3>
							</div>
					</div>
					<hr />
					<div class="row">

						<div class="col-xs-12 col-sm-8 col-sm-offset-2" id="editForm">



          	</div>
					</div>

			</div>


		</div>


		</div>







<script src="/assets/js/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
<?php include_once("includes/footer.php"); ?>


<!--<script src="/assets/js/pacejs/pace.min.js"></script>-->
<script type="text/javascript" src="/assets/js/datatables/jquery.datatables.min.js"></script>
<script type="text/javascript" src="/assets/js/datatables/datatables.jqueryui.min.js"></script>
<script type="text/javascript" src="/assets/js/simplemde/simplemde.min.js"></script>
<script>

jQuery(document).ready(function() {
	jQuery("#editForm").load("/admin/partial_edit.php?model=<?php echo $model; ?>&id=<?php echo $object_id; ?>");

});

</script>
<script type="text/javascript" src="/assets/admin.js"></script>

</body>
</html>
