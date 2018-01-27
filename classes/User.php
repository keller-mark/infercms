<?php

class User {

	private $id 			= '';
	private $email	 		= '';
	private $password	 	= '';
	private $userType 		= '';
	private $firstname 		= ' ';
	private $lastname 		= ' ';
	private $tmpPassword 	= '';
	private $created_at = '';
	private $last_login = '';
	private $active;

	public function __construct() {
		global $accountType;
		$num_args = func_num_args();

		if($num_args == 1) {
			//id was passed as parameter
			$id = func_get_arg(0);
			$this->id = $id;
			$this->update();

			$this->active = true;
		} else {
			//id was NOT passed, assume it is new user
			$this->tmpPassword = '0';
			$this->userType = $accountType['admin'];

			$this->active = false;
		}
	}

	public function update() {
		$row = DB::arr("SELECT * FROM users WHERE id = " . $this->id . " LIMIT 1");
		$this->email	 		= $row['email'];
		$this->password	 		= $row['password'];
		$this->userType			= $row['userType'];
		$this->firstname		= $row['firstname'];
		$this->lastname 		= $row['lastname'];
		$this->tmpPassword		= $row['tmpPassword'];
		$this->created_at		= $row['created_at'];
		$this->last_login 		= $row['last_login'];
	}

	public function setEmail($email) {
		$row = DB::arr("SELECT * FROM users WHERE email = '" . DB::escape($email) . "' AND id != '" . DB::escape($this->id) . "' LIMIT 1");
		$dbEmail = $row['email'];

		if ($dbEmail == $email) {
			return false;

		} else {
			$email = strtolower($email);
			if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$this->email = $email;
				return true;
			} else {
				return false;
			}
		}
	}
	public function setPassword($password) {
		global $accountType;
		if($this->userType == $accountType['admin']) {
			$this->password = passwordHash($password);
		}

	}
	public function setPasswordRandom() {
		$this->password = passwordHash( tmpPasswordGen() );
	}

	public function setUserType($value) {
		$this->userType = $value;
	}


	public function setFirstname($value) {
		$this->firstname = $value;
	}
	public function setLastname($value) {
		$this->lastname = $value;
	}
	public function setTmpPassword($tmpPassword) {
		$this->tmpPassword = $tmpPassword;
	}

	public function setLast_Login() {
		$this->last_login = date('Y-m-d H:i:s');
		if($this->isActive()) {
			$query = DB::sql("UPDATE users SET last_login = '" . $this->last_login . "' WHERE id = '" . DB::escape($this->id) . "'");
		}
	}



	public function getId() {
		return $this->id;
	}


	public function getFirstname() {
		return $this->firstname;
	}

	public function getLastname() {
		return $this->lastname;
	}
	public function getFullname() {
		return $this->firstname . " " . $this->lastname;
	}
	public function getEmail() {
		return $this->email;
	}
	public function getPassword() {
		return $this->password;
	}
	public function getUserType() {
		return $this->userType;
	}
	public function getUserTypeName() {
		global $accountType;
		$temp = array();
		foreach($accountType as $type => $num) {
			$temp[$num] = $type;
		}
		return $temp[$this->userType];
	}
	public function getTmpPassword() {
		return $this->tmpPassword;
	}

	public function getCreated_At() {
		return $this->created_at;
	}

	public function getLast_Login() {
		return $this->last_login;
	}

	public function isAdmin() {
		global $accountType;
		return ($this->getUserType() == $accountType['admin']);
	}



	public function passwordMatch($pass) {
		if(password_verify($pass,$this->password)) {
			return true;
		} else{
			return false;
		}
	}

	public function save() {
		global $auth;

		if($this->isActive()) {
			$query = DB::sql("UPDATE users SET email = '" . DB::escape($this->email) . "', password = '" . DB::escape($this->password) . "', userType = '" . $this->userType . "', firstname = '" . DB::escape($this->firstname) . "', lastname = '" . DB::escape($this->lastname) . "', created_at = '" . DB::escape($this->created_at) . "', last_login = '" . DB::escape($this->last_login) . "', tmpPassword = '" . $this->tmpPassword . "' WHERE id = '" . DB::escape($this->id) . "'");
			if($auth->user->getId() == $this->id) {
				$auth->user->update();
			}
		} else {
			$query = DB::sql("INSERT INTO users (email, password, userType, firstname, lastname, tmpPassword, created_at, last_login)
				VALUES (
					'" . DB::escape($this->email) . "',
					'" . $this->password . "',
					'" . $this->userType . "',

					'" . DB::escape($this->firstname) . "',
					'" . DB::escape($this->lastname) . "',
					'" . $this->tmpPassword . "',
					'" . DB::escape($this->created_at) . "',
					'" . DB::escape($this->last_login) . "'
					)");
			$this->active = true;
			$this->id = DB::arr("SELECT * FROM users WHERE email = '" . DB::escape($this->email) . "'")['id'];
		}

	}


	public function isActive() {
		return $this->active;
	}


}
