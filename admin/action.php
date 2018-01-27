<?php
	include_once('../classes/init.php');
	$auth->adminPage();

	if(isset($_GET['a'])) {
		$action = $_GET['a'];

		switch($action){
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
			case 'new':

					if(!empty($_GET['model'])) {

						$model = $_GET['model'];
						$table_name = Inflector::delimit($model);
						$pluralModel = ucfirst($model);
						$singleModel = Inflector::singularize($pluralModel);

						$error = false;
						$is_dialog = false;

						if(!class_exists($singleModel) || !class_exists($pluralModel)) {
							$error = true;
						}

						$modelObject = new $singleModel();


					} else {
						$error = true;
					}

				if($error == false) {
					foreach(DB::getColumns($table_name) as $column) {
						$column_name = $column["COLUMN_NAME"];

						if($column_name != "id") {
							if($column['DATA_TYPE'] == 'tinyint') {
								if(!empty($_POST[$column_name])) {
									$modelObject->set($column_name, $_POST[$column_name]);
								} else {
									$modelObject->set($column_name, 0);
								}

							} elseif(empty($_POST[$column_name])) {
								$error = true;
								$notification = adminNotification(false, "Please fill all fields.");
							} else {
								if($column['DATA_TYPE'] == 'datetime') {
									$modelObject->set( $column_name, date("Y-m-d H:i:s", strtotime($_POST[$column_name])) );
								} else {
									$modelObject->set($column_name, $_POST[$column_name]);
								}
							}
						}

					}

				} else {
					$notification = adminNotification(false, "An error has occurred.");
				}

				if($error == false) {
					$modelObject->save();
					$notification = adminNotification(true, Inflector::humanize(Inflector::delimit($singleModel)) . " successfully created.");
					$notification['id'] = $modelObject->get('id');
					$notification['itemString'] = $modelObject->toString();
					$notification['row'] = strtolower($singleModel . '_id');
				}


				echo json_encode($notification);

				break;
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
			case 'edit':

					if(!empty($_GET['model']) && !empty($_GET['id'])) {

						$model = $_GET['model'];
						$table_name = Inflector::delimit($model);
						$pluralModel = ucfirst($model);
						$singleModel = Inflector::singularize($pluralModel);

						$error = false;
						$is_dialog = false;

						if(!class_exists($singleModel) || !class_exists($pluralModel)) {
							$error = true;
						}

						$object_id = intval($_GET['id']);

						$modelObjectPlural = new $pluralModel();
						$modelObject = $modelObjectPlural::find($object_id);

					} else {
						$error = true;
					}

				if($error == false) {
					foreach(DB::getColumns($table_name) as $column) {
						$column_name = $column["COLUMN_NAME"];

						if($column_name != "id") {
							if($column['DATA_TYPE'] == 'tinyint') {
								if(!empty($_POST[$column_name])) {
									$modelObject->set($column_name, $_POST[$column_name]);
								} else {
									$modelObject->set($column_name, 0);
								}

							} elseif(empty($_POST[$column_name])) {
								$error = true;
								$notification = adminNotification(false, "Please fill all fields.");
							} else {
								if($column['DATA_TYPE'] == 'datetime') {
									$modelObject->set( $column_name, date("Y-m-d H:i:s", strtotime($_POST[$column_name])) );
								} else {
									$modelObject->set($column_name, $_POST[$column_name]);
								}
							}
						}

					}

				} else {
					$notification = adminNotification(false, "An error has occurred.");
				}

				if($error == false) {
					$modelObject->save();
					$notification = adminNotification(true, Inflector::humanize(Inflector::delimit($singleModel)) . " successfully edited.");
				}


				echo json_encode($notification);

				break;
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
			case 'delete':

				if(!empty($_GET['model']) && !empty($_GET['id'])) {

					$model = $_GET['model'];
					$table_name = Inflector::delimit($model);
					$pluralModel = ucfirst($model);

					$error = false;

					if(!class_exists($pluralModel)) {
						$error = true;
					}

					$modelObject = new $pluralModel();

				} else {
					$error = true;
				}

				if($error == false) {
					$modelObject::delete(intval($_GET['id']));
					header('Location: edit_all.php?model=' . $model);
				}

				break;

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
			case 'newImage':

				if(!empty($_FILES['File'])) {
					$image = new Image();
					$notification = $image->upload($_FILES['File'], '');

					if($notification['success']) {
						if(!empty($_POST['title'])) {
							$image->set('title', $_POST['title'] );
						} else {
							$image->set('title', ' ' );
						}

						$image->save();

					}
				} else {
					$notification = adminNotification(true, "No image file sent. Try again.");
				}

				echo $notification['result'];

				break;

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
		default:
				header('Location: /');
				break;
		}
	} else {
		header('Location: /');
	}

?>
