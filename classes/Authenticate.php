<?php

class Authenticate {

	private $id;
	public $user;

	public function __construct() {
		session_start();
		if ($this->loggedIn()) {

			$this->id = $_SESSION['id'];
			$this->user = Users::find($this->id);


			if($this->user->getTmpPassword() == '1' && !(basename($_SERVER['PHP_SELF']) == 'reset-password.php' || basename($_SERVER['PHP_SELF']) == 'action.php')) {
				header('Location: /reset-password');
			}
		}
	}

	public function loggedIn() {
		if(isset($_SESSION['id'])) {
			return true;
		} else {
			return false;
		}
	}

	public function publicPage() {

	}

	public function adminPage() {
		if (!$this->isAdmin()) {
			header('Location: /login');
		}
	}

	public function isAdmin() {
		global $accountType;
		if($this->loggedIn() && ($this->user->getUserType() == $accountType['admin']) ) {
			return true;
		} else {
			return false;
		}
	}



	public function resetPasswordPage() {
		if ( $this->loggedIn() ) {
			if($this->user->getTmpPassword() != '1') {
				header('Location: /');
			}
		} else {
			header('Location: /login');
		}
	}

	public function loginPage() {
		if ( $this->loggedIn() ) {
			header('Location: /');
		}
	}

	public function forgotPasswordPage() {
		if ( $this->loggedIn() ) {
			header('Location: /');
		}
	}

	public function redirectAdmin() {
		if($this->loggedIn()) {
			global $accountType;
			if ($this->user->getUserType() == $accountType['admin']) {
				header('Location: /admin/');
			}
		} else {
			header('Location: /login');
		}

	}

	public function logOut() {
		session_destroy();

		$this->user = '';

		header('Location: /login');
	}


}
