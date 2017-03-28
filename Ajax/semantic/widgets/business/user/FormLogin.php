<?php
namespace Ajax\semantic\widgets\business\user;
use Ajax\semantic\widgets\business\BusinessForm;
/**
 * Form for user login
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
				["message","login","password","remember","forget","submit","error"],
				["message"=>[["icon"=>"sign in"]],"input0"=>[["rules"=>"empty"]],"input1"=>[["inputType"=>"password","rules"=>"empty"]],"checkbox","link","submit"=>["green fluid"],"message2"=>[["error"=>true]]],
				["Connection","login","password","remember","forget","submit","error"],
				["Please enter login and password to connect","Login","Password","Remember me.","Forgot your password?","Connection"],
				[0,2,4,5,6]);
	}

	public static function smallInline($identifier,$modelInstance=null){
		$result=new FormLogin($identifier,$modelInstance,
				["login","password","submit"],
				["input0"=>[["rules"=>"empty"]],"input1"=>[["inputType"=>"password","rules"=>"empty"]],"submit"=>["green basic"]],
				["login","password","submit"],
				["","","Connection"],
				[2]);
			$result->addDividerBefore(0, "Connection");
		return $result;
	}

	public static function small($identifier,$modelInstance=null){
		$result=new FormLogin($identifier,$modelInstance,
				["login","password","submit"],
				["input0"=>[["rules"=>"empty"]],"input1"=>[["inputType"=>"password","rules"=>"empty"]],"submit"=>["green basic"]],
				["login","password","submit"],
				["Login","Password","Connection"],
				[1,2]);
		$result->addDividerBefore(0, "Connection");
		return $result;
	}

	public static function attachedSegment($identifier,$modelInstance=null){
		$result=self::regular($identifier,$modelInstance);
		$result->fieldAsMessage("message",["icon"=>"sign in","attached"=>true]);
		$result->addWrapper("message",null,"<div class='ui attached segment'>");
		$result->addWrapper("error", null,"</div>");
		return $result;
	}
}
