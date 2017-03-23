<?php

/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 12/02/2017
 * Time: 13:10
 */
class Auth {
	public $errors;
	private $username;
	private $password;

	public function __construct() {

	}

	public function getUsername() {
		return $this->username;
	}

	public function setUsername($username) {
		$this->username = $username;
	}

	public function getPassword() {
		return $this->password;
	}

	public function setPassword($password) {
		$this->password = $password;
	}

	public function logout() {
		session_destroy();
		header('location: login.php');
	}

	public function isLoggedIn() {
		if (isset($_SESSION['userid']))
			return true;
		else
			return false;
	}

	public function getUserProfile() {
		global $db;
		$sql = "SELECT * FROM users LIMIT 1";
		if (!$records = $db->GetRow($sql)) {
			return false;
		}

		return $records;
	}

	public function loginUser() {
		if ($this->validateLogin()) {
			if ($this->login()) {
				return true;
			} else {
				return $this->errors;
			}
		} else {
			return $this->errors;
		}
	}

	public function validateLogin() {
		if (empty(trim($this->username))) {
			$this->errors = 'Please enter your username';

			return false;
		}
		if (empty(trim($this->password))) {
			$this->errors = 'Please enter your password';

			return false;
		}

		$this->password = md5($this->password);

		return true;
	}

	public function login() {
		global $db;
		$this->username = addslashes($this->username);
		$this->password = addslashes($this->password);
		$sql = "SELECT * FROM users WHERE username= '$this->username' AND password= '$this->password' LIMIT 1";
		$result = $db->GetRow($sql);
		if (!empty($result)) {
			session_destroy();
			session_start();
			$_SESSION['userid'] = $result['user_id'];
			$_SESSION['username'] = $result['username'];
			$_SESSION['password'] = $result['password'];
			$_SESSION['firstname'] = $result['first_name'];
			$_SESSION['lastname'] = $result['last_name'];
			$_SESSION['phone'] = $result['phone'];
			$_SESSION['email'] = $result['email'];
			$_SESSION['facebook_url'] = $result['facebook_page'];
			$_SESSION['address'] = $result['address'];

			return true;
		} else {
			$this->errors = 'Login Details Incorrect';

			return false;
		}
	}
}
