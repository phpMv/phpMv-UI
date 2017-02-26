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
	public function __construct($identifier,$modelInstance=null,$fieldsOrder=[],$fieldsDefinition=[],$fields=[],$captions=[],$separators=[]) {
		parent::__construct($identifier,$modelInstance,$fieldsOrder,$fieldsDefinition,$fields,$captions,$separators);
	}

	protected function getDefaultModelInstance(){
		return new UserModel();
	}

	public static function regular($identifier,$modelInstance=null){
		return new FormLogin($identifier,$modelInstance,
				["message","login","password","remember","forget","submit"],
				["message"=>["icon"=>"sign in"],"input0"=>["rules"=>"empty"],"input1"=>["inputType"=>"password","rules"=>"empty"],"checkbox","link","submit"=>"green fluid"],
				["Connection","login","password","remember","forget","submit"],
				["Please enter login and password to connect","Login","Password","Remember me.","Forgot your password?","Connection"],
				[0,2,4,5]);
	}

	public static function smallInline($identifier,$modelInstance=null){
		$result=new FormLogin($identifier,$modelInstance,
				["login","password","submit"],
				["input0"=>["rules"=>"empty"],"input1"=>["inputType"=>"password","rules"=>"empty"],"submit"=>"green basic"],
				["login","password","submit"],
				["","","Connection"],
				[2]);
			$result->addDividerBefore(0, "Connection");
		return $result;
	}

	public static function small($identifier,$modelInstance=null){
		$result=new FormLogin($identifier,$modelInstance,
				["login","password","submit"],
				["input0"=>["rules"=>"empty"],"input1"=>["inputType"=>"password","rules"=>"empty"],"submit"=>"green basic"],
				["login","password","submit"],
				["Login","Password","Connection"],
				[1,2]);
		$result->addDividerBefore(0, "Connection");
		return $result;
	}
}