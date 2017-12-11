<?php

namespace Ajax\common\traits;

use Ajax\service\AjaxTransition;
use Ajax\service\Javascript;
use Ajax\service\JString;

/**
 * @author jc
 * @property array $jquery_code_for_compile
 */
trait JsUtilsAjaxTrait {

	protected $ajaxTransition;
	protected $ajaxLoader='<span></span><span></span><span></span><span></span><span></span>';

	abstract public function getUrl($url);
	abstract public function _add_event($element, $js, $event, $preventDefault=false, $stopPropagation=false,$immediatly=true);

	protected function _ajax($method,$url,$responseElement="",$parameters=[]) {
		$jsCallback=null;
		$attr="id";
		$hasLoader=true;
		$immediatly=false;
		$jqueryDone="html";
		$ajaxTransition=null;
		$async=true;
		$params=null;
		$headers=null;
		$jsCondition=null;
		extract($parameters);

		$jsCallback=isset($jsCallback) ? $jsCallback : "";
		$retour=$this->_getAjaxUrl($url, $attr);
		$responseElement=$this->_getResponseElement($responseElement);
		$retour.="var self=this;\n";
		if($hasLoader===true && JString::isNotNull($responseElement)){
			$this->addLoading($retour, $responseElement);
		}
		$ajaxParameters=["url"=>"url","method"=>"'".\strtoupper($method)."'"];
		if(!$async){
			$ajaxParameters["async"]="false";
		}
		if(isset($params)){
			$ajaxParameters["data"]=self::_correctParams($params);
		}
		if(isset($headers)){
			$ajaxParameters["headers"]=$headers;
		}
		$this->createAjaxParameters($ajaxParameters, $parameters);
		$retour.="$.ajax({".$this->implodeAjaxParameters($ajaxParameters)."}).done(function( data, textStatus, jqXHR ) {\n";
		$retour.=$this->_getOnAjaxDone($responseElement, $jqueryDone,$ajaxTransition,$jsCallback)."});\n";
		$retour=$this->_addJsCondition($jsCondition,$retour);
		if ($immediatly)
			$this->jquery_code_for_compile[]=$retour;
		return $retour;
	}

	protected function createAjaxParameters(&$original,$parameters){
		$validParameters=["dataType"=>"'%value%'","beforeSend"=>"function(jqXHR,settings){%value%}","complete"=>"function(jqXHR){%value%}"];
		foreach ($validParameters as $param=>$mask){
			if(isset($parameters[$param])){
				$original[$param]=\str_replace("%value%", $parameters[$param], $mask);
			}
		}
	}

	protected function implodeAjaxParameters($ajaxParameters){
		$s = ''; foreach ($ajaxParameters as $k=>$v) { if ($s !== '') { $s .= ','; } $s .= "'{$k}':{$v}"; }
		return $s;
	}

	protected function _addJsCondition($jsCondition,$jsSource){
		if(isset($jsCondition)){
			return "if(".$jsCondition."){\n".$jsSource."\n}";
		}
		return $jsSource;
	}


	protected function _getAjaxUrl($url,$attr){
		$url=$this->_correctAjaxUrl($url);
		$retour="url='".$url."';";
		$slash="/";
		if(JString::endswith($url, "/")===true){
			$slash="";
		}
		if(JString::isNotNull($attr)){
			if ($attr==="value"){
				$retour.="url=url+'".$slash."'+$(this).val();\n";
			}elseif ($attr==="html"){
				$retour.="url=url+'".$slash."'+$(this).html();\n";
			}elseif(\substr($attr, 0,3)==="js:"){
				$retour.="url=url+'".$slash."'+".\substr($attr, 3).";\n";
			}elseif($attr!==null && $attr!=="")
				$retour.="url=url+'".$slash."'+($(this).attr('".$attr."')||'');\n";
		}
		return $retour;
	}

	protected function _getOnAjaxDone($responseElement,$jqueryDone,$ajaxTransition,$jsCallback){
		$retour="";$call=null;
		if (JString::isNotNull($responseElement)) {
			if(isset($ajaxTransition)){
				$call=$this->setAjaxDataCall($ajaxTransition);
			}elseif(isset($this->ajaxTransition)){
				$call=$this->ajaxTransition;
			}
			if(\is_callable($call))
				$retour="\t".$call($responseElement,$jqueryDone).";\n";
			else
				$retour="\t{$responseElement}.{$jqueryDone}( data );\n";
		}
		$retour.="\t".$jsCallback."\n";
		return $retour;
	}

	protected function _getResponseElement($responseElement){
		if (JString::isNotNull($responseElement)) {
			$responseElement=Javascript::prep_value($responseElement);
			$responseElement=Javascript::prep_jquery_selector($responseElement);
		}
		return $responseElement;
	}

	protected function _correctAjaxUrl($url) {
		if ($url!=="/" && JString::endsWith($url, "/")===true)
			$url=substr($url, 0, strlen($url)-1);
			if (strncmp($url, 'http://', 7)!=0&&strncmp($url, 'https://', 8)!=0) {
				$url=$this->getUrl($url);
			}
			return $url;
	}

	public static function _correctParams($params){
		if(JString::isNull($params)){
			return "";
		}
		if(\preg_match("@^\{.*?\}$@", $params)){
			return '$.param('.$params.')';
		}
		return $params;
	}

	public static function _implodeParams($parameters){
		$allParameters=[];
		foreach ($parameters as $params){
			if(isset($params))
				$allParameters[]=self::_correctParams($params);
		}
		return \implode("+'&'+", $allParameters);
	}

	protected function addLoading(&$retour, $responseElement) {
		$loading_notifier='<div class="ajax-loader">';
		if ($this->ajaxLoader==='') {
			$loading_notifier.="Loading...";
		} else {
			$loading_notifier.=$this->ajaxLoader;
		}
		$loading_notifier.='</div>';
		$retour.="{$responseElement}.empty();\n";
		$retour.="\t\t{$responseElement}.prepend('{$loading_notifier}');\n";
	}

	protected function setAjaxDataCall($params){
		$result=null;
		if(!\is_callable($params)){
			$result=function ($responseElement,$jqueryDone="html") use($params){
				return AjaxTransition::{$params}($responseElement,$jqueryDone);
			};
		}
		return $result;
	}

	protected function setDefaultParameters(&$parameters,$default){
		foreach ($default as $k=>$v){
			if(!isset($parameters[$k]))
				$parameters[$k]=$v;
		}
	}

	public function setAjaxLoader($loader) {
		$this->ajaxLoader=$loader;
	}

	/**
	 * Performs an ajax GET request
	 * @param string $url The url of the request
	 * @param string $responseElement selector of the HTML element displaying the answer
	 */
	private function _get($url, $responseElement="",$parameters=[]) {
		return $this->_ajax("get", $url,$responseElement,$parameters);
	}

	/**
	 * Performs an ajax GET request
	 * @param string $url The url of the request
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null)
	 */
	public function get($url, $responseElement="",$parameters=[]) {
		$parameters["immediatly"]=true;
		return $this->_get($url,$responseElement,$parameters);
	}

	/**
	 * Performs an ajax request
	 * @param string $method The http method (get, post, delete, put, head)
	 * @param string $url The url of the request
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null)
	 */
	public function ajax($method,$url, $responseElement="", $parameters=[]) {
		$parameters["immediatly"]=true;
		return $this->_ajax($method,$url,$responseElement,$parameters);
	}

	/**
	 * Performs a deferred ajax request
	 * @param string $method The http method (get, post, delete, put, head)
	 * @param string $url The url of the request
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null)
	 */
	public function ajaxDeferred($method,$url, $responseElement="", $parameters=[]) {
		$parameters["immediatly"]=false;
		return $this->_ajax($method,$url,$responseElement,$parameters);
	}

	/**
	 * Performs an ajax request and receives the JSON data types by assigning DOM elements with the same name
	 * @param string $url the request url
	 * @param string $method Method used
	 * @param array $parameters default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","context"=>"document","jsCondition"=>NULL,"headers"=>null,"immediatly"=>false)
	 */
	private function _json($url, $method="get",$parameters=[]) {
		$parameters=\array_merge($parameters,["hasLoader"=>false]);
		$jsCallback=isset($parameters['jsCallback']) ? $parameters['jsCallback'] : "";
		$context=isset($parameters['context']) ? $parameters['context'] : "document";
		$retour="\tdata=($.isPlainObject(data))?data:JSON.parse(data);\t".$jsCallback.";\n\tfor(var key in data){"
				."if($('#'+key,".$context.").length){ if($('#'+key,".$context.").is('[value]')) { $('#'+key,".$context.").val(data[key]);} else { $('#'+key,".$context.").html(data[key]); }}};\n";
				$retour.="\t$(document).trigger('jsonReady',[data]);\n";
		$parameters["jsCallback"]=$retour;
		return $this->_ajax($method, $url,null,$parameters);
	}

	/**
	 * Performs an ajax request and receives the JSON data types by assigning DOM elements with the same name
	 * @param string $url the request url
	 * @param string $method Method used
	 * @param array $parameters default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","context"=>"document","jsCondition"=>NULL,"headers"=>null,"immediatly"=>false)
	 */
	public function json($url, $method="get", $parameters=[]) {
		return $this->_json($url,$method,$parameters);
	}

	/**
	 * Makes an ajax request and receives the JSON data types by assigning DOM elements with the same name when $event fired on $element
	 * @param string $element
	 * @param string $event
	 * @param string $url the request address
	 * @param string $method default get
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","params"=>"{}","method"=>"get","immediatly"=>true)
	 */
	public function jsonOn($event,$element, $url,$method="get",$parameters=array()) {
		$this->setDefaultParameters($parameters, ["preventDefault"=>true,"stopPropagation"=>true,"immediatly"=>true]);
		return $this->_add_event($element, $this->jsonDeferred($url,$method, $parameters), $event, $parameters["preventDefault"], $parameters["stopPropagation"],$parameters["immediatly"]);
	}

	/**
	 * Prepares an ajax request delayed and receives the JSON data types by assigning DOM elements with the same name
	 * @param string $url the request url
	 * @param string $method Method used
	 * @param array $parameters default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","context"=>"document","jsCondition"=>NULL,"headers"=>null,"immediatly"=>false)
	 */
	public function jsonDeferred($url, $method="get", $parameters=[]) {
		$parameters["immediatly"]=false;
		return $this->_json($url,$method,$parameters);
	}

	/**
	 * Performs an ajax request and receives the JSON array data types by assigning DOM elements with the same name
	 * @param string $maskSelector
	 * @param string $url the request url
	 * @param string $method Method used, default : get
	 * @param array $parameters default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","context"=>null,"jsCondition"=>NULL,"headers"=>null,"immediatly"=>false,"rowClass"=>"_json")
	 */
	private function _jsonArray($maskSelector, $url, $method="get", $parameters=[]) {
		$parameters=\array_merge($parameters,["hasLoader"=>false]);
		$rowClass=isset($parameters['rowClass']) ? $parameters['rowClass'] : "_json";
		$jsCallback=isset($parameters['jsCallback']) ? $parameters['jsCallback'] : "";
		$context=isset($parameters['context']) ? $parameters['context'] : null;
		if($context===null){
			$parent="$('".$maskSelector."').parent()";
			$newElm = "$('#'+newId)";
		}else{
			$parent=$context;
			$newElm = $context.".find('#'+newId)";
		}
		$appendTo="\t\tnewElm.appendTo(".$parent.");\n";
		$retour=$parent.".find('.{$rowClass}').remove();";
		$retour.="\tdata=($.isPlainObject(data))?data:JSON.parse(data);\t".$jsCallback.";\n$.each(data, function(index, value) {\n"."\tvar created=false;var maskElm=$('".$maskSelector."').first();maskElm.hide();"."\tvar newId=(maskElm.attr('id') || 'mask')+'-'+index;"."\tvar newElm=".$newElm.";\n"."\tif(!newElm.length){\n"."\t\tnewElm=maskElm.clone();
		newElm.attr('id',newId);\n;newElm.addClass('{$rowClass}').removeClass('_jsonArrayModel');\nnewElm.find('[id]').each(function(){ var newId=$(this).attr('id')+'-'+index;$(this).attr('id',newId).removeClass('_jsonArrayChecked');});\n";
		$retour.= $appendTo;
		$retour.="\t}\n"."\tfor(var key in value){\n"."\t\t\tvar html = $('<div />').append($(newElm).clone()).html();\n"."\t\t\tif(html.indexOf('__'+key+'__')>-1){\n"."\t\t\t\tcontent=$(html.split('__'+key+'__').join(value[key]));\n"."\t\t\t\t$(newElm).replaceWith(content);newElm=content;\n"."\t\t\t}\n"."\t\tvar sel='[data-id=\"'+key+'\"]';if($(sel,newElm).length){\n"."\t\t\tvar selElm=$(sel,newElm);\n"."\t\t\t if(selElm.is('[value]')) { selElm.attr('value',value[key]);selElm.val(value[key]);} else { selElm.html(value[key]); }\n"."\t\t}\n"."}\n"."\t$(newElm).show(true);"."\n"."\t$(newElm).removeClass('hide');"."});\n";
		$retour.="\t$(document).trigger('jsonReady',[data]);\n";
		$parameters["jsCallback"]=$retour;
		return $this->_ajax($method, $url,null,$parameters);
	}

	/**
	 * Performs an ajax request and receives the JSON array data types by assigning DOM elements with the same name
	 * @param string $maskSelector
	 * @param string $url the request url
	 * @param string $method Method used, default : get
	 * @param array $parameters default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","context"=>null,"jsCondition"=>NULL,"headers"=>null,"immediatly"=>false,"rowClass"=>"_json")
	 */
	public function jsonArray($maskSelector, $url, $method="get", $parameters=[]) {
		return $this->_jsonArray($maskSelector, $url,$method,$parameters);
	}

	/**
	 * Peforms an ajax request delayed and receives a JSON array data types by copying and assigning them to the DOM elements with the same name
	 * @param string $maskSelector
	 * @param string $url the request url
	 * @param string $method Method used, default : get
	 * @param array $parameters default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","context"=>null,"jsCondition"=>NULL,"headers"=>null,"rowClass"=>"_json")
	 */
	public function jsonArrayDeferred($maskSelector, $url, $method="get", $parameters) {
		$parameters["immediatly"]=false;
		return $this->jsonArray($maskSelector, $url, $method, $parameters);
	}

	/**
	 * Performs an ajax request and receives the JSON array data types by assigning DOM elements with the same name when $event fired on $element
	 * @param string $element
	 * @param string $event
	 * @param string $url the request url
	 * @param string $method Method used, default : get
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","params"=>"{}","method"=>"get","rowClass"=>"_json","immediatly"=>true)
	 */
	public function jsonArrayOn($event,$element,$maskSelector, $url,$method="get",$parameters=array()) {
		$this->setDefaultParameters($parameters, ["preventDefault"=>true,"stopPropagation"=>true,"immediatly"=>true]);
		return $this->_add_event($element, $this->jsonArrayDeferred($maskSelector,$url,$method, $parameters), $event, $parameters["preventDefault"], $parameters["stopPropagation"],$parameters["immediatly"]);
	}

	/**
	 * Prepares a Get ajax request
	 * To use on an event
	 * @param string $url The url of the request
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null)
	 */
	public function getDeferred($url, $responseElement="", $parameters=[]) {
		$parameters["immediatly"]=false;
		return $this->_get($url, $responseElement,$parameters);
	}

	/**
	 * Performs a get to $url on the event $event on $element
	 * and display it in $responseElement
	 * @param string $event
	 * @param string $element
	 * @param string $url The url of the request
	 * @param string $responseElement The selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>null,"headers"=>null)
	 */
	public function getOn($event, $element, $url, $responseElement="", $parameters=array()) {
		$this->setDefaultParameters($parameters, ["preventDefault"=>true,"stopPropagation"=>true,"immediatly"=>true]);
		return $this->_add_event($element, $this->getDeferred($url,$responseElement,$parameters), $event, $parameters["preventDefault"], $parameters["stopPropagation"],$parameters["immediatly"]);
	}

	/**
	 * Performs an ajax request to $url on the event $event on $element
	 * and display it in $responseElement
	 * @param string $event
	 * @param string $element
	 * @param string $url The url of the request
	 * @param string $responseElement The selector of the HTML element displaying the answer
	 * @param array $parameters default : array("method"=>"get","preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html","jsCondition"=>NULL,"headers"=>null)
	 */
	public function ajaxOn($event, $element, $url, $responseElement="", $parameters=array()) {
		$this->setDefaultParameters($parameters, ["preventDefault"=>true,"stopPropagation"=>true,"immediatly"=>true,"method"=>"get"]);
		return $this->_add_event($element, $this->ajaxDeferred($parameters["method"],$url,$responseElement,$parameters), $event, $parameters["preventDefault"], $parameters["stopPropagation"],$parameters["immediatly"]);
	}

	/**
	 * Performs a get to $url on the click event on $element
	 * and display it in $responseElement
	 * @param string $element
	 * @param string $url The url of the request
	 * @param string $responseElement The selector of the HTML element displaying the answer
	 * @param array $parameters default : array("method"=>"get","preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html","jsCondition"=>NULL,"headers"=>null)
	 */
	public function ajaxOnClick($element, $url, $responseElement="", $parameters=array()) {
		return $this->ajaxOn("click", $element, $url, $responseElement, $parameters);
	}

	/**
	 * Performs a get to $url on the click event on $element
	 * and display it in $responseElement
	 * @param string $element
	 * @param string $url The url of the request
	 * @param string $responseElement The selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html","jsCondition"=>NULL,"headers"=>null)
	 */
	public function getOnClick($element, $url, $responseElement="", $parameters=array()) {
		return $this->getOn("click", $element, $url, $responseElement, $parameters);
	}

	private function _post($url, $params="{}",$responseElement="", $parameters=[]) {
		$parameters["params"]=$params;
		return $this->_ajax("POST", $url,$responseElement,$parameters);
	}

	/**
	 * Makes an ajax post
	 * @param string $url the request url
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param string $params JSON parameters
	 * @param array $parameters default : array("jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null)
	 */
	public function post($url, $params="{}",$responseElement="", $parameters=[]) {
		return $this->_post($url, $params,$responseElement, $parameters);
	}

	/**
	 * Prepares a delayed ajax POST
	 * to use on an event
	 * @param string $url the request url
	 * @param string $params JSON parameters
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null)
	 */
	public function postDeferred($url, $params="{}",$responseElement="", $parameters=[]) {
		$parameters["immediatly"]=false;
		return $this->_post($url, $params, $responseElement, $parameters);
	}

	/**
	 * Performs a post to $url on the event $event fired on $element and pass the parameters $params
	 * Display the result in $responseElement
	 * @param string $event
	 * @param string $element
	 * @param string $url The url of the request
	 * @param string $params The parameters to send
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null)
	 */
	public function postOn($event, $element, $url, $params="{}", $responseElement="", $parameters=array()) {
		$this->setDefaultParameters($parameters, ["preventDefault"=>true,"stopPropagation"=>true,"immediatly"=>true]);
		return $this->_add_event($element, $this->postDeferred($url, $params, $responseElement, $parameters), $event, $parameters["preventDefault"], $parameters["stopPropagation"],$parameters["immediatly"]);
	}

	/**
	 * Performs a post to $url on the click event fired on $element and pass the parameters $params
	 * Display the result in $responseElement
	 * @param string $element
	 * @param string $url The url of the request
	 * @param string $params The parameters to send
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null)
	 */
	public function postOnClick($element, $url, $params="{}", $responseElement="", $parameters=array()) {
		return $this->postOn("click", $element, $url, $params, $responseElement, $parameters);
	}

	private function _postForm($url, $form, $responseElement, $parameters=[]) {
		$params="{}";$validation=false;$jsCallback=NULL;$attr="id";$hasLoader=true;$jqueryDone="html";$ajaxTransition=null;$immediatly=false;$jsCondition=NULL;$headers=NULL;$async=true;
		\extract($parameters);
		$async=($async)?"true":"false";
		$jsCallback=isset($jsCallback) ? $jsCallback : "";
		$retour=$this->_getAjaxUrl($url, $attr);
		$retour.="\nvar params=$('#".$form."').serialize();\n";
		if(isset($params)){
			$retour.="params+='&'+".self::_correctParams($params).";\n";
		}
		$responseElement=$this->_getResponseElement($responseElement);
		$retour.="var self=this;\n";
		if($hasLoader===true){
			$this->addLoading($retour, $responseElement);
		}
		$ajaxParameters=["url"=>"url","method"=>"'POST'","data"=>"params","async"=>$async];
		if(isset($headers)){
			$ajaxParameters["headers"]=$headers;
		}
		$this->createAjaxParameters($ajaxParameters, $parameters);
		$retour.="$.ajax({".$this->implodeAjaxParameters($ajaxParameters)."}).done(function( data ) {\n";
		$retour.=$this->_getOnAjaxDone($responseElement, $jqueryDone,$ajaxTransition,$jsCallback)."});\n";

		if ($validation) {
			$retour="$('#".$form."').validate({submitHandler: function(form) {
			".$retour."
			}});\n";
			$retour.="$('#".$form."').submit();\n";
		}
		$retour=$this->_addJsCondition($jsCondition, $retour);
		if ($immediatly)
			$this->jquery_code_for_compile[]=$retour;
		return $retour;
	}

	/**
	 * Performs a post form with ajax
	 * @param string $url The url of the request
	 * @param string $form The form HTML id
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null)
	 */
	public function postForm($url, $form, $responseElement, $parameters=[]) {
		$parameters["immediatly"]=true;
		return $this->_postForm($url, $form, $responseElement, $parameters);
	}

	/**
	 * Performs a delayed post form with ajax
	 * For use on an event
	 * @param string $url The url of the request
	 * @param string $form The form HTML id
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null)
	 */
	public function postFormDeferred($url, $form, $responseElement, $parameters=[]) {
		$parameters["immediatly"]=false;
		return $this->_postForm($url, $form, $responseElement, $parameters);
	}

	/**
	 * Performs a post form with ajax in response to an event $event on $element
	 * display the result in $responseElement
	 * @param string $event
	 * @param string $element
	 * @param string $url
	 * @param string $form
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"validation"=>false,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>null,"headers"=>null)
	 */
	public function postFormOn($event, $element, $url, $form, $responseElement="", $parameters=array()) {
		$this->setDefaultParameters($parameters, ["preventDefault"=>true,"stopPropagation"=>true,"immediatly"=>true]);
		return $this->_add_event($element, $this->postFormDeferred($url, $form, $responseElement,$parameters), $event, $parameters["preventDefault"], $parameters["stopPropagation"],$parameters["immediatly"]);
	}

	/**
	 * Performs a post form with ajax in response to the click event on $element
	 * display the result in $responseElement
	 * @param string $element
	 * @param string $url
	 * @param string $form
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"validation"=>false,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>null,"headers"=>null)
	 */
	public function postFormOnClick($element, $url, $form, $responseElement="", $parameters=array()) {
		return $this->postFormOn("click", $element, $url, $form, $responseElement, $parameters);
	}
}
