<?php
namespace Ajax\semantic\widgets\business\user;
use Ajax\semantic\widgets\business\BusinessForm;
/**
 * Form for user Account
 * @author jc
 */
class FormAccount extends BusinessForm {
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
		return new FormAccount($identifier,$modelInstance,
				["message","login","password","passwordConf","email","submit","error"],
				["message"=>[["icon"=>"sign in"]],
						"input0"=>[["rules"=>"empty"]],
						"input1"=>[["inputType"=>"password","rules"=> ['minLength[6]', 'empty']]],
						"input2"=>[["inputType"=>"password","rules"=> ['minLength[6]', 'empty', 'match[password]']]],
						"input3"=>[["rules"=>"email"]],
						"submit"=>["green fluid"],
						"message2"=>[["error"=>true]]],
				["Account","login","password","passwordConf","email","submit","error"],
				["Please enter your account informations","Login","Password","Password confirmation","Email address","Creation"],
				[0,1,3,4,5,6]);
	}

	public static function smallInline($identifier,$modelInstance=null){
		$result=new FormAccount($identifier,$modelInstance,
				["login","password","submit"],
				["input0"=>[["rules"=>"empty"]],"input1"=>[["inputType"=>"password","rules"=>"empty"]],"submit"=>["green basic"]],
				["login","password","submit"],
				["","","Connection"],
				[2]);
			$result->addDividerBefore(0, "Connection");
		return $result;
	}

	public static function small($identifier,$modelInstance=null){
		$result=new FormAccount($identifier,$modelInstance,
				["login","password","passwordConf","email","submit"],
				[
						"input0"=>[["rules"=>"empty"]],
						"input1"=>[["inputType"=>"password","rules"=>['minLength[6]', 'empty']]],
						"input2"=>[["inputType"=>"password","rules"=> ['minLength[6]', 'empty', 'match[password]']]],
						"input3"=>[["rules"=>"email"]],
						"submit"=>["green basic"]],
				["login","password","passwordConf","email","submit"],
				["Login","Password","Password confirmation","Email address","Creation"],
				[1,2]);
		$result->addDividerBefore(0, "Creation");
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
