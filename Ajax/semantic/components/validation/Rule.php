<?php
namespace Ajax\semantic\components\validation;
use Ajax\service\AjaxCall;
use Ajax\JsUtils;

/**
 * @author jc
 * @version 1.001
 * Generates a JSON Rule for the validation of a field
 */
class Rule implements \JsonSerializable{
	/**
	 * @var string
	 */
	private $type;
	/**
	 * @var string
	 */
	private $prompt;

	/**
	 * @var string
	 */
	private $value;

	public function __construct($type,$prompt=NULL,$value=NULL){
		$this->type=$type;
		$this->prompt=$prompt;
		$this->value=$value;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type=$type;
		return $this;
	}

	public function getPrompt() {
		return $this->prompt;
	}

	public function setPrompt($prompt) {
		$this->prompt=$prompt;
		return $this;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue($value) {
		$this->value=$value;
		return $this;
	}

	public function jsonSerialize() {
		$result= ["type"=>$this->type];
		if(isset($this->prompt))
			$result["prompt"]=$this->prompt;
		if(isset($this->value))
			$result["value"]=$this->value;
		return $result;
	}

	/**
	 * A field should match the value of another validation field, for example to confirm passwords
	 * @param string $name
	 * @param string $prompt
	 * @return \Ajax\semantic\components\validation\Rule
	 */
	public static function match($name,$prompt=NULL){
		return new Rule("match[".$name."]",$prompt);
	}

	/**
	 * A field should be different than another specified field
	 * @param string $name
	 * @param string $prompt
	 * @return \Ajax\semantic\components\validation\Rule
	 */
	public static function different($name,$prompt=NULL){
		return new Rule("different[".$name."]",$prompt);
	}

	/**
	 * A field is an integer value, or matches an integer range
	 * @param int|NULL $min
	 * @param int|NULL $max
	 * @param string $prompt
	 * @return \Ajax\semantic\components\validation\Rule
	 */
	public static function integer($min=NULL,$max=NULL,$prompt=NULL){
		if(\is_int($min) && \is_int($max))
			return new Rule("integer[{$min}..{$max}]",$prompt);
		return new Rule("integer",$prompt);
	}

	public static function decimal($prompt=NULL){
		return new Rule("decimal",$prompt);
	}

	public static function number($prompt=NULL){
		return new Rule("number",$prompt);
	}

	public static function is($value,$prompt=NULL){
		return new Rule("is[".$value."]",$prompt);
	}

	public static function isExactly($value,$prompt=NULL){
		return new Rule("isExactly[".$value."]",$prompt);
	}

	public static function not($value,$prompt=NULL){
		return new Rule("not[".$value."]",$prompt);
	}

	public static function notExactly($value,$prompt=NULL){
		return new Rule("notExactly[".$value."]",$prompt);
	}

	public static function contains($value,$prompt=NULL){
		return new Rule("contains[".$value."]",$prompt);
	}

	public static function containsExactly($value,$prompt=NULL){
		return new Rule("containsExactly[".$value."]",$prompt);
	}

	public static function doesntContain($value,$prompt=NULL){
		return new Rule("doesntContain[".$value."]",$prompt);
	}

	public static function doesntContainExactly($value,$prompt=NULL){
		return new Rule("doesntContainExactly[".$value."]",$prompt);
	}

	public static function minCount($value,$prompt=NULL){
		return new Rule("minCount[".$value."]",$prompt);
	}

	public static function maxCount($value,$prompt=NULL){
		return new Rule("maxCount[".$value."]",$prompt);
	}

	public static function exactCount($value,$prompt=NULL){
		return new Rule("exactCount[".$value."]",$prompt);
	}

	public static function email($prompt=NULL){
		return new Rule("email",$prompt);
	}

	public static function url($prompt=NULL){
		return new Rule("url",$prompt);
	}

	public static function regExp($value,$prompt=NULL){
		return new Rule("regExp",$prompt,$value);
	}

	public static function custom($name,$jsFunction){
		return "$.fn.form.settings.rules.".$name." =".$jsFunction ;
	}

	public static function ajax(JsUtils $js,$name,$url,$params,$jsCallback,$method="post",$parameters=[]){
		$parameters=\array_merge(["async"=>false,"url"=>$url,"params"=>$params,"hasLoader"=>false,"jsCallback"=>$jsCallback,"dataType"=>"json","stopPropagation"=>false,"preventDefault"=>false,"responseElement"=>null],$parameters);
		$ajax=new AjaxCall($method, $parameters);
		return self::custom($name, "function(value,ruleValue){var result=true;".$ajax->compile($js)."return result;}");
	}

}
