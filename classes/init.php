<?php
$development = true;

if($development) {
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

date_default_timezone_set('America/New_York');

$class_folder = dirname(__FILE__) . '/';

include_once($class_folder . 'PHPMailer/PHPMailerAutoload.php');
include_once($class_folder . 'class.upload.php');
spl_autoload_register(function ($class) {
	global $class_folder;
    include_once($class_folder . $class . '.php');
});

$auth = new Authenticate();
global $auth;

$accountType = array(
	'admin'					=> '0'
);

$settings = array(
	'title' => 'InferCMS Example Site',
	'domain' => 'example.com',
	'email' => 'example@example.com',
	'upload_root' => '/assets/uploads/'
);


function tmpPasswordGen() {
	return substr(md5(rand(999,99999)), 0, 10);
}
function passwordHash($pass) {
	return password_hash($pass, PASSWORD_BCRYPT, array('cost' => 8));
}

function isValidPassword($pass) {
	$originalPass = $pass;
	$pass = DB::sanitize($pass);

	if(strlen($pass) < 8) {
		return notification(false, "Password shorter than 8 characters.");

	} elseif(preg_match('/\s/', $pass)) {
		return notification(false, "Password contains spaces.");

	} elseif(!preg_match( '~[A-Z]~', $pass)) {
		return notification(false, "Password must contain at least 1 uppercase letter.");

	} elseif(!preg_match( '~[a-z]~', $pass)) {
		return notification(false, "Password must contain at least 1 lowercase letter.");

	} elseif(!preg_match( '~\d~', $pass)) {
		return notification(false, "Password must contain at least 1 digit.");

	} elseif($pass != $originalPass) {
		return notification(false, "Password invalid.");

	} else {
		return notification(true, "Password is valid.");

	}
}

function isValidEmail($email) {
	return (filter_var($email, FILTER_VALIDATE_EMAIL));
}

function notification($tf, $message) {
	return array(
		'success' => $tf,
		'result' => $message
		);
}
function jsonNotification($tf, $message) {
	$message = '<p id="notification" data-success="' . ($tf ? 'true' : 'false') . '" class="alert-' . ($tf ? 'success' : 'danger') . '" style="text-align: center; border-radius: 5px; color: ' . ($tf ? 'green' : 'red') . ';">' . $message . '</p>';
	return notification($tf, $message);
}

function adminNotification($tf, $message) {
	$message = '<p id="notification" data-success="' . ($tf ? 'true' : 'false') . '" class="alert alert-' . ($tf ? 'success' : 'danger') . '">' . $message . '</p>';
	return notification($tf, $message);
}

function redirect404() {
	header('Location: /404');
}

function get_current_uri($strip = true) {
    // filter function
    static $filter;
    if ($filter == null) {
        $filter = function($input) use($strip) {
            $input = str_ireplace(array(
                "\0", '%00', "\x0a", '%0a', "\x1a", '%1a'), '', urldecode($input));
            if ($strip) {
                $input = strip_tags($input);
            }
            // or any encoding you use instead of utf-8
            $input = htmlspecialchars($input, ENT_QUOTES, 'utf-8');
            return trim($input);
        };
    }

    return $filter($_SERVER['REQUEST_URI']);
}

function current_page_is($page_path) {
	$current_uri = get_current_uri();
	if($page_path == '/' && $current_uri == '/') {
		return true;
	} elseif($page_path == '/admin/' && $current_uri == '/admin/') {
		return true;
	} elseif($page_path != '/' && $page_path != '/admin/') {
		return (0 === strpos($current_uri, $page_path));
	}
}

function current_page_model_is($model) {
	return (
		current_page_is('/admin/edit_all.php?model=' . $model) ||
		current_page_is('/admin/new.php?model=' . $model) ||
		current_page_is('/admin/edit_single.php?model=' . $model)
	);

}

function slugify($text) {
  // replace non letter or digits by -
  $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

  $text = trim($text, '-');

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  $text = strtolower($text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}
