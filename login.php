<?php
	include_once("classes/init.php");
	$auth->loginPage();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Log In - <?php echo $settings["title"]; ?></title>
		<?php include_once('includes/sources.php') ?>
	</head>
	<body>
        <?php include_once('includes/header.php'); ?>

        <form id="loginForm" action="/action.php?a=login" method="post">
            <fieldset>
                <legend>Log In</legend>
                <div id="loginResult"></div>
                <div>
                    <label for="email-input">Email</label><br>
                    <input name="email" type="text" autofocus="autofocus" id="email-input" placeholder="Email">
                </div>
                <div>
                    <label for="inputPassword10" class="control-label">Password</label><br>
                    <input name="password" type="password" class="form-control" id="inputPassword10" placeholder="Password">
                </div>
                <div>
                    <!--<a href="/forgot-password.php">Forgot Password?</a>-->
                    <button id="infoButton" data-loading-text="Loading..." type="submit" class="pull-right btn btn-primary">Log In</button>
                </div>
            </fieldset>
        </form>

	    <?php include_once('includes/footer.php'); ?>

        <script>
        jQuery(function($) {
        $(document).ready(function() {
            $('#loginForm').submit(function(e) {
                var $btn = $('#infoButton').button('loading');
                e.preventDefault();
                var that = $(this),
                    contents = that.serialize();

                $('#infoButton').button('loading');

                $.ajax({
                    url: '/action.php?a=login',
                    dataType: 'json',
                    type: 'post',
                    data: contents,
                    success: function(data) {
                        if(data.success) {
                            $('#loginResult').html(data.result);
                            $('#infoButton').button('reset');
                            window.location.href = "/action.php?a=redirectAdmin";
                        } else {
                            $('#loginResult').html(data.result);
                            $('#infoButton').button('reset');
                        }
                    }
                });
            });


        });

        });
        </script>
</body>
</html>
