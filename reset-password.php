<?php
	include_once("classes/init.php");
	$auth->resetPasswordPage();
 ?>
<!DOCTYPE html>
<html lang="en-US">
<head>
	<title>Reset Password - <?php echo $settings["title"]; ?></title>

	<?php include_once("includes/sources.php"); ?>

</head>
<body>

<?php include_once("includes/header.php"); ?>

<div class="clear"></div>
<div id="lambda-featured-header-wrap" class="">
  <div id="lambda-featured-header">
    <figure class="lambda-featured-header-image">
      <div class="page-banner" style="background-image: url('/assets/img/wfp_login_banner.png');"></div>
      <div class="container">
        <div class="row-fluid">
          <figcaption class="lambda-featured-header-caption"><span>Reset Password</span></figcaption>
        </div>
      </div>
    </figure>
  </div>
</div>


<div class="clear"></div>



<div id="content-wrap" class="clearfix" data-content="content"><!-- content-wrap -->
  <div class="container">
    <div class="row">
      <section id="content" class="span12 site-content clearfix" role="main">


				<div class="row-fluid">

          <div class="span6 offset3">
            <div class="row-fluid">
							<form id="loginForm" action="/action.php?a=resetPassword" method="post" role="form">
															 <fieldset>

																	 <div id="loginResultBad"></div>
																	 <div class="form-group">
																			 <label for="field2" class="control-label">New Password</label>
																			 <input id="field2" type="password" name="newPassword" maxlength="128" autofocus="autofocus" class="form-control" placeholder="New Password">
																	 </div>
																	 <div class="form-group">
																			 <label for="field3" class="control-label">Repeat New Password</label>
																			 <input id="field3" type="password" name="repeatNewPassword" maxlength="128" class="form-control" placeholder="Repeat New Password">
																	 </div>
																	 <div class="form-group">

																			 <button id="forgotButton" data-complete-text="Success!" data-loading-text="Loading..." type="submit" class="pull-right btn btn-primary">Reset</button>

																	 </div>
															 </fieldset>
													 </form>
            </div>
          </div>
        </div>




        <div class="clear"></div>

      </section>
      <!-- /#content-wrap -->

    </div>
    <!-- /#content-wrap --></div>
</div>


<?php include_once("includes/footer.php"); ?>
<script>
jQuery(function($) {
$(document).ready(function() {
	$('#loginForm').submit(function(e) {
		var $btn = $('#forgotButton').button('loading');
		e.preventDefault();
		var that = $(this),
			contents = that.serialize();

		$('#forgotButton').button('loading');

		$.ajax({
			url: '/action.php?a=resetPassword',
			dataType: 'json',
			type: 'post',
			data: contents,
			success: function(data) {
				if(data.success) {
					$('#loginResultBad').html(data.result);
					document.getElementById('field2').value = "";
					document.getElementById('field3').value = "";
					$('#forgotButton').button('reset');
					window.location.href = "/action.php?a=redirectAdmin";
				} else {
					$('#loginResultBad').html(data.result);
					$('#forgotButton').button('reset');
				}
			}
		});
	});
    });


});
</script>
</body>
</html>
