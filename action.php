<?php
	include_once('classes/init.php');

	if(isset($_GET['a'])) {
		$action = $_GET['a'];

		switch($action){
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
			case 'redirectAdmin':
				$auth->redirectAdmin();
				break;
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
			case 'logout':
				session_unset();
				session_destroy();
				header('Location: /login');
				break;
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
			case 'login';
				header('Content-Type: application/json');
				if (!empty($_POST['email']) && !empty($_POST['password'])) {

					$email = DB::escape($_POST['email']);
					$paswd = DB::escape($_POST['password']);

					$email = strtolower($email);

					$row = DB::arr("SELECT * FROM users WHERE email = '" . $email . "' LIMIT 1");
					$uid = $row['id'];
					$dbEmail = $row['email'];
					$dbPassword = $row['password'];
					$dbUserType = $row['userType'];

					if($email == $dbEmail && password_verify($paswd,$dbPassword)) {
						$_SESSION['id'] = $uid;
						Users::find($uid)->setLast_Login();

						$json = jsonNotification(true, 'Success. Redirecting...');

					} else {
						$json = jsonNotification(false, 'Invalid email or password.');
					}
				} else {
					$json = jsonNotification(false, 'All fields required.');
				}

				echo json_encode($json);

				break;
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
			case 'resetPassword';
			header('Content-Type: application/json');
				if (!empty($_POST['newPassword']) && !empty($_POST['repeatNewPassword'])) {

					if($_POST['newPassword'] == $_POST['repeatNewPassword']){

						$isValidPassword = isValidPassword($_POST['newPassword']);

						if($isValidPassword['success']) {

							$auth->user->setPassword($_POST['newPassword']);
							$auth->user->setTmpPassword(0);
							$auth->user->save();

							$auth->user->update();

							$json = jsonNotification(true, 'Password successfully reset');
						} else {
							$json = jsonNotification(false, $isValidPassword['result']);
						}


					} else {
						$json = jsonNotification(false, 'Passwords do not match.');
					}


				} else {
					$json = jsonNotification(false, 'All fields required.');
				}

				echo json_encode($json);

				break;
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
			case 'forgotPassword';
				header('Content-Type: application/json');
				$json = array(
						'success' => false,
						'result' => 0
					);

				if (!empty($_POST['email'])) {

					$email = DB::escape($_POST['email']);
					$email = strtolower($email);

					$row = DB::arr("SELECT * FROM users WHERE email = '$email' LIMIT 1");
					$uid			= $row['id'];
					$dbEmail 		= $row['email'];
					$dbUserType 	= $row['userType'];
					$dbFirstname 	= $row['firstname'];
					$dbLastname 	= $row['lastname'];
					$dbFullname 	= $dbFirstname . " " . $dbLastname;

					if ($email == $dbEmail && $dbUserType == $accountType['admin']) {

						$tmpPassword = tmpPasswordGen();
						$tmpPasswordHash = passwordHash($tmpPassword);

						$query2 = DB::sql("UPDATE users SET password='$tmpPasswordHash', tmpPassword='1' WHERE email='$dbEmail'");

						$email_contents = "Your temporary password is: $tmpPassword<br><br>Please log in using this password and you will be prompted to reset your password.<br><a href='http://" . $settings['domain'] . "/login.php'>Log in</a><br><br><br>-" . $settings['title'];
						$subject = "Reset Password - " . $settings['title'];

						$emailObject = new Email();
						$send = $emailObject->general($dbEmail, $subject, $email_contents);

						if ($send) {
							$json = jsonNotification(true, 'Your email has been sent.');
						} else {
							$json = jsonNotification(false, 'An error has occurred.');
						}

					} else {
						$json = jsonNotification(false, 'Invalid email.');

					}
				} else {
						$json = jsonNotification(false, 'Email required.');

				}

				echo json_encode($json);

				break;
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
			default:
				header('Location: /');
				break;
		}
	} else {
		header('Location: /');
	}
