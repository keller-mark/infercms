<?php require("classes/init.php"); ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $settings["title"]; ?></title>
		<?php require('includes/sources.php'); ?>

	</head>
	<body>
		<?php require('includes/header.php'); ?>
		<h3>Home Page</h3>
		<p>List of MyItems:</p>
		<ul>
		<?php
			foreach(MyItems::findAll() as $item) {
				echo '
					<li>
						<a href="/items?id=' . $item->get('id') . '" style="background-image:url(\'' . $settings['upload_root'] . $item->get('image')->get('link') . '\');">
							<h2 class="title">' . $item->get('title') . '</h2>
							<span class="category">' . $item->get('subtitle') . '</span>
						</a>
					</li>
				';
			}
		?>
		</ul>

		<?php require('includes/footer.php'); ?>
	</body>
</html>
