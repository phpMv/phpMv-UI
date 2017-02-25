<?php

namespace Ajax\semantic\widgets\business\user;

use Ajax\semantic\widgets\business\BusinessForm;

/**
 * @author jc
 */
class FormLogin extends BusinessForm {

	/**
	 * @param string $identifier
	 * @param object $modelInstance
	 */
	public function __construct($identifier,$modelInstance=null) {
		if(!isset($modelInstance))
			$modelInstance=new UserModel();
			$this->_fieldsOrder=["message","login","password","remember","forget","submit"];
			$this->_fieldsDefinition=["message"=>["icon"=>"sign in"],"input0"=>["rules"=>"empty"],"input1"=>["inputType"=>"password","rules"=>"empty"],"checkbox","link","submit"=>"green fluid"];
		parent::__construct($identifier,$modelInstance,["Connection","login","password","remember","forget","submit"],
				["Please enter login and password to connect","Login","Password","Remember me.","Forgot your password?","Connection"],
				[0,2,4,5]);
	}
}