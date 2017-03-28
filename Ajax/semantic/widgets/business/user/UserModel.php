<?php

namespace Ajax\semantic\widgets\business\user;

class UserModel {
	protected $lastname;
	protected $firstname;
	protected $login;
	protected $password;
	protected $email;

	public function getLogin() {
		return $this->login;
	}

	public function setLogin($login) {
		$this->login=$login;
		return $this;
	}

	public function getPassword() {
		return $this->password;
	}

	public function setPassword($password) {
		$this->password=$password;
		return $this;
	}

}
