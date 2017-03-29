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

	protected function _ajax($method,$url, $params="{}", $responseElement="", $jsCallback=NULL, $attr="id", $hasLoader=true,$jqueryDone="html",$ajaxTransition=null,$immediatly=false) {
		if(JString::isNull($params)){$params="{}";}
		$jsCallback=isset($jsCallback) ? $jsCallback : "";
		$retour=$this->_getAjaxUrl($url, $attr);
		$responseElement=$this->_getResponseElement($responseElement);
		$retour.="var self=this;\n";
		if($hasLoader===true){
			$this->addLoading($retour, $responseElement);
		}
		$retour.="$.".$method."(url,".$params.").done(function( data ) {\n";
		$retour.=$this->_getOnAjaxDone($responseElement, $jqueryDone,$ajaxTransition,$jsCallback)."});\n";
		if ($immediatly)
			$this->jquery_code_for_compile[]=$retour;
			return $retour;
	}



	protected function _getAjaxUrl($url,$attr){
		$url=$this->_correctAjaxUrl($url);
		$retour="url='".$url."';";
		$slash="/";
		if(JString::endswith($url, "/")===true)
			$slash="";
			if(JString::isNotNull($attr)){
				if ($attr==="value")
					$retour.="url=url+'".$slash."'+$(this).val();\n";
				elseif ($attr==="html")
					$retour.="url=url+'".$slash."'+$(this).html();\n";
				elseif($attr!==null && $attr!=="")
					$retour.="url=url+'".$slash."'+($(this).attr('".$attr."')||'');\n";
			}
			return $retour;
	}

	protected function _getOnAjaxDone($responseElement,$jqueryDone,$ajaxTransition,$jsCallback){
		$retour="";$call=null;
		if ($responseElement!=="") {
			if(isset($ajaxTransition)){
				$call=$this->setAjaxDataCall($ajaxTransition);
			}elseif(isset($this->ajaxTransition)){
				$call=$this->ajaxTransition;
			}
			if(\is_callable($call))
				$retour="\t".$call($responseElement,$jqueryDone).";\n";
				else
					$retour="\t$({$responseElement}).{$jqueryDone}( data );\n";
		}
		$retour.="\t".$jsCallback."\n";
		return $retour;
	}

	protected function _getResponseElement($responseElement){
		if ($responseElement!=="") {
			$responseElement=Javascript::prep_value($responseElement);
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

	protected function addLoading(&$retour, $responseElement) {
		$loading_notifier='<div class="ajax-loader">';
		if ($this->ajaxLoader=='') {
			$loading_notifier.="Loading...";
		} else {
			$loading_notifier.=$this->ajaxLoader;
		}
		$loading_notifier.='</div>';
		$retour.="$({$responseElement}).empty();\n";
		$retour.="\t\t$({$responseElement}).prepend('{$loading_notifier}');\n";
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

	public function setAjaxLoader($loader) {
		$this->ajaxLoader=$loader;
	}

	/**
	 * Performs an ajax GET request
	 * @param string $url The url of the request
	 * @param string $params JSON parameters
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param string $jsCallback javascript code to execute after the request
	 * @param boolean $hasLoader true for showing ajax loader. default : true
	 * @param string $jqueryDone the jquery function call on ajax data. default:html
	 * @param string|callable $ajaxTransition
	 */
	private function _get($url, $params="{}", $responseElement="", $jsCallback=NULL, $attr="id", $hasLoader=true,$jqueryDone="html",$ajaxTransition=null,$immediatly=false) {
		return $this->_ajax("get", $url,$params,$responseElement,$jsCallback,$attr,$hasLoader,$jqueryDone,$ajaxTransition,$immediatly);
	}

	/**
	 * Performs an ajax GET request
	 * @param string $url The url of the request
	 * @param string $params JSON parameters
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param string $jsCallback javascript code to execute after the request
	 * @param boolean $hasLoader true for showing ajax loader. default : true
	 * @param string $jqueryDone the jquery function call on ajax data. default:html
	 * @param string|callable $ajaxTransition
	 */
	public function get($url, $responseElement="", $params="{}", $jsCallback=NULL,$hasLoader=true,$jqueryDone="html",$ajaxTransition=null) {
		return $this->_get($url,$params,$responseElement,$jsCallback,null,$hasLoader,$jqueryDone,$ajaxTransition,true);
	}

	/**
	 * Performs an ajax request and receives the JSON data types by assigning DOM elements with the same name
	 * @param string $url the request url
	 * @param string $params JSON parameters
	 * @param string $method Method used
	 * @param string $jsCallback javascript code to execute after the request
	 * @param string $attr
	 * @param string $context
	 * @param boolean $immediatly
	 */
	private function _json($url, $method="get", $params="{}", $jsCallback=NULL, $attr="id", $context="document",$immediatly=false) {
		$jsCallback=isset($jsCallback) ? $jsCallback : "";
		$retour=$this->_getAjaxUrl($url, $attr);
		$retour.="$.{$method}(url,".$params.").done(function( data ) {\n";
		$retour.="\tdata=$.parseJSON(data);for(var key in data){"
				."if($('#'+key,".$context.").length){ if($('#'+key,".$context.").is('[value]')) { $('#'+key,".$context.").val(data[key]);} else { $('#'+key,".$context.").html(data[key]); }}};\n";
				$retour.="\t".$jsCallback."\n".
						"\t$(document).trigger('jsonReady',[data]);\n".
						"});\n";
				if ($immediatly)
					$this->jquery_code_for_compile[]=$retour;
		return $retour;
	}

	/**
	 * Performs an ajax request and receives the JSON data types by assigning DOM elements with the same name
	 * @param string $url the request url
	 * @param string $params JSON parameters
	 * @param string $method Method used
	 * @param string $jsCallback javascript code to execute after the request
	 * @param string $context
	 * @param boolean $immediatly
	 */
	public function json($url, $method="get", $params="{}", $jsCallback=NULL,$context="document",$immediatly=false) {
		return $this->_json($url,$method,$params,$jsCallback,NULL,$context,$immediatly);
	}

	/**
	 * Makes an ajax request and receives the JSON data types by assigning DOM elements with the same name when $event fired on $element
	 * @param string $element
	 * @param string $event
	 * @param string $url the request address
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","params"=>"{}","method"=>"get","immediatly"=>true)
	 */
	public function jsonOn($event,$element, $url,$parameters=array()) {
		$preventDefault=true;
		$stopPropagation=true;
		$jsCallback=null;
		$attr="id";
		$method="get";
		$context="document";
		$params="{}";
		$immediatly=true;
		extract($parameters);
		return $this->_add_event($element, $this->_json($url,$method, $params,$jsCallback, $attr,$context), $event, $preventDefault, $stopPropagation,$immediatly);
	}

	/**
	 * Prepares an ajax request delayed and receives the JSON data types by assigning DOM elements with the same name
	 * @param string $url the request url
	 * @param string $params Paramètres passés au format JSON
	 * @param string $method Method used
	 * @param string $jsCallback javascript code to execute after the request
	 * @param string $context jquery DOM element, array container.
	 */
	public function jsonDeferred($url, $method="get", $params="{}", $jsCallback=NULL,$context=NULL) {
		return $this->json($url, $method, $params, $jsCallback, $context,false);
	}

	/**
	 * Performs an ajax request and receives the JSON array data types by assigning DOM elements with the same name
	 * @param string $url the request url
	 * @param string $params The JSON parameters
	 * @param string $method Method used
	 * @param string $jsCallback javascript code to execute after the request
	 * @param string $context jquery DOM element, array container.
	 * @param string $rowClass the css class for the new element
	 * @param boolean $immediatly
	 */
	private function _jsonArray($maskSelector, $url, $method="get", $params="{}", $jsCallback=NULL,$rowClass="_json",$context=NULL,$attr="id",$immediatly=false) {
		$jsCallback=isset($jsCallback) ? $jsCallback : "";
		$retour=$this->_getAjaxUrl($url, $attr);
		if($context===null){
			$parent="$('".$maskSelector."').parent()";
			$newElm = "$('#'+newId)";
		}else{
			$parent=$context;
			$newElm = $context.".find('#'+newId)";
		}
		$appendTo="\t\tnewElm.appendTo(".$parent.");\n";
		$retour.="var self = $(this);\n$.{$method}(url,".$params.").done(function( data ) {\n";
		$retour.=$parent.".find('._json').remove();";
		$retour.="\tdata=$.parseJSON(data);$.each(data, function(index, value) {\n"."\tvar created=false;var maskElm=$('".$maskSelector."').first();maskElm.hide();"."\tvar newId=(maskElm.attr('id') || 'mask')+'-'+index;"."\tvar newElm=".$newElm.";\n"."\tif(!newElm.length){\n"."\t\tnewElm=maskElm.clone();
		newElm.attr('id',newId);\n;newElm.addClass('{$rowClass}').removeClass('_jsonArrayModel');\nnewElm.find('[id]').each(function(){ var newId=$(this).attr('id')+'-'+index;$(this).attr('id',newId).removeClass('_jsonArrayChecked');});\n";
		$retour.= $appendTo;
		$retour.="\t}\n"."\tfor(var key in value){\n"."\t\t\tvar html = $('<div />').append($(newElm).clone()).html();\n"."\t\t\tif(html.indexOf('__'+key+'__')>-1){\n"."\t\t\t\tcontent=$(html.split('__'+key+'__').join(value[key]));\n"."\t\t\t\t$(newElm).replaceWith(content);newElm=content;\n"."\t\t\t}\n"."\t\tvar sel='[data-id=\"'+key+'\"]';if($(sel,newElm).length){\n"."\t\t\tvar selElm=$(sel,newElm);\n"."\t\t\t if(selElm.is('[value]')) { selElm.attr('value',value[key]);selElm.val(value[key]);} else { selElm.html(value[key]); }\n"."\t\t}\n"."}\n"."\t$(newElm).show(true);"."\n"."\t$(newElm).removeClass('hide');"."});\n";
		$retour.="\t$(document).trigger('jsonReady',[data]);\n";
		$retour.="\t".$jsCallback."\n"."});\n";
		if ($immediatly)
			$this->jquery_code_for_compile[]=$retour;
		return $retour;
	}

	/**
	 * Performs an ajax request and receives the JSON array data types by assigning DOM elements with the same name
	 * @param string $url the request url
	 * @param string $params The JSON parameters
	 * @param string $method Method used
	 * @param string $jsCallback javascript code to execute after the request
	 * @param string $rowClass the css class for the new element
	 * @param string $context jquery DOM element, array container.
	 * @param boolean $immediatly
	 */
	public function jsonArray($maskSelector, $url, $method="get", $params="{}", $jsCallback=NULL,$rowClass="_json",$context=NULL,$immediatly=false) {
		return $this->_jsonArray($maskSelector, $url,$method,$params,$jsCallback,$rowClass,$context,NULL,$immediatly);
	}

	/**
	 * Peforms an ajax request delayed and receives a JSON array data types by copying and assigning them to the DOM elements with the same name
	 * @param string $maskSelector the selector of the element to clone
	 * @param string $url the request url
	 * @param string $params JSON parameters
	 * @param string $method Method used
	 * @param string $jsCallback javascript code to execute after the request
	 * @param string $rowClass the css class for the new element
	 * @param string $context jquery DOM element, array container.
	 */
	public function jsonArrayDeferred($maskSelector, $url, $method="get", $params="{}", $jsCallback=NULL,$rowClass="_json",$context=NULL) {
		return $this->jsonArray($maskSelector, $url, $method, $params, $jsCallback,$rowClass,$context,false);
	}

	/**
	 * Performs an ajax request and receives the JSON array data types by assigning DOM elements with the same name when $event fired on $element
	 * @param string $element
	 * @param string $event
	 * @param string $url the request url
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","params"=>"{}","method"=>"get","rowClass"=>"_json","immediatly"=>true)
	 */
	public function jsonArrayOn($event,$element,$maskSelector, $url,$parameters=array()) {
		$preventDefault=true;
		$stopPropagation=true;
		$jsCallback=null;
		$attr="id";
		$method="get";
		$context = null;
		$params="{}";
		$immediatly=true;
		$rowClass="_json";
		extract($parameters);
		return $this->_add_event($element, $this->_jsonArray($maskSelector,$url,$method, $params,$jsCallback, $rowClass, $context,$attr), $event, $preventDefault, $stopPropagation,$immediatly);
	}

	/**
	 * Prepares a Get ajax request
	 * To use on an event
	 * @param string $url The url of the request
	 * @param string $params JSON parameters
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param string $jsCallback javascript code to execute after the request
	 * @param string $attr the html attribute added to the request
	 * @param string $jqueryDone the jquery function call on ajax data. default:html
	 * @param string|callable $ajaxTransition
	 */
	public function getDeferred($url, $responseElement="", $params="{}", $jsCallback=NULL,$attr="id",$jqueryDone="html",$ajaxTransition=null) {
		return $this->_get($url, $params,$responseElement,$jsCallback,$attr,false,$jqueryDone,$ajaxTransition);
	}

	/**
	 * Performs a get to $url on the event $event on $element
	 * and display it in $responseElement
	 * @param string $event
	 * @param string $element
	 * @param string $url The url of the request
	 * @param string $responseElement The selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html")
	 */
	public function getOn($event, $element, $url, $responseElement="", $parameters=array()) {
		$preventDefault=true;
		$stopPropagation=true;
		$jsCallback=null;
		$attr="id";
		$hasLoader=true;
		$immediatly=true;
		$jqueryDone="html";
		$ajaxTransition=null;
		$params="{}";
		extract($parameters);
		return $this->_add_event($element, $this->_get($url, $params,$responseElement,$jsCallback,$attr, $hasLoader,$jqueryDone,$ajaxTransition), $event, $preventDefault, $stopPropagation,$immediatly);
	}

	/**
	 * Performs a get to $url on the click event on $element
	 * and display it in $responseElement
	 * @param string $element
	 * @param string $url The url of the request
	 * @param string $responseElement The selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html")
	 */
	public function getOnClick($element, $url, $responseElement="", $parameters=array()) {
		return $this->getOn("click", $element, $url, $responseElement, $parameters);
	}

	private function _post($url, $params="{}", $responseElement="", $jsCallback=NULL, $attr="id", $hasLoader=true,$jqueryDone="html",$ajaxTransition=null,$immediatly=false) {
		return $this->_ajax("post", $url,$params,$responseElement,$jsCallback,$attr,$hasLoader,$jqueryDone,$ajaxTransition,$immediatly);
	}

	/**
	 * Makes an ajax post
	 * @param string $url the request url
	 * @param string $params JSON parameters
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param string $jsCallback javascript code to execute after the request
	 * @param boolean $hasLoader true for showing ajax loader. default : true
	 * @param string $jqueryDone the jquery function call on ajax data. default:html
	 * @param string|callable $ajaxTransition
	 */
	public function post($url, $responseElement="", $params="{}", $jsCallback=NULL,$hasLoader=true,$jqueryDone="html",$ajaxTransition=null) {
		return $this->_post($url, $params, $responseElement, $jsCallback, NULL, $hasLoader,$jqueryDone,$ajaxTransition,true);
	}

	/**
	 * Prepares a delayed ajax POST
	 * to use on an event
	 * @param string $url the request url
	 * @param string $params JSON parameters
	 * @param string $attr the html attribute added to the request
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param string $jsCallback javascript code to execute after the request
	 * @param boolean $hasLoader true for showing ajax loader. default : true
	 * @param string $jqueryDone the jquery function call on ajax data. default:html
	 * @param string|callable $ajaxTransition
	 */
	public function postDeferred($url, $responseElement="", $params="{}", $jsCallback=NULL, $attr="id",$hasLoader=true,$jqueryDone="html",$ajaxTransition=null) {
		return $this->_post($url, $params, $responseElement, $jsCallback, $attr, $hasLoader,$jqueryDone,$ajaxTransition,false);
	}

	/**
	 * Performs a post to $url on the event $event fired on $element and pass the parameters $params
	 * Display the result in $responseElement
	 * @param string $event
	 * @param string $element
	 * @param string $url The url of the request
	 * @param string $params The parameters to send
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null)
	 */
	public function postOn($event, $element, $url, $params="{}", $responseElement="", $parameters=array()) {
		$preventDefault=true;
		$stopPropagation=true;
		$jsCallback=null;
		$attr="id";
		$hasLoader=true;
		$immediatly=true;
		$jqueryDone="html";
		$ajaxTransition=null;
		extract($parameters);
		return $this->_add_event($element, $this->_post($url, $params, $responseElement, $jsCallback, $attr,$hasLoader,$jqueryDone,$ajaxTransition), $event, $preventDefault, $stopPropagation,$immediatly);
	}

	/**
	 * Performs a post to $url on the click event fired on $element and pass the parameters $params
	 * Display the result in $responseElement
	 * @param string $element
	 * @param string $url The url of the request
	 * @param string $params The parameters to send
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null)
	 */
	public function postOnClick($element, $url, $params="{}", $responseElement="", $parameters=array()) {
		return $this->postOn("click", $element, $url, $params, $responseElement, $parameters);
	}

	private function _postForm($url, $form, $responseElement, $validation=false, $jsCallback=NULL, $attr="id", $hasLoader=true,$jqueryDone="html",$ajaxTransition=null,$immediatly=false) {
		$jsCallback=isset($jsCallback) ? $jsCallback : "";
		$retour=$this->_getAjaxUrl($url, $attr);
		$retour.="\nvar params=$('#".$form."').serialize();\n";
		$responseElement=$this->_getResponseElement($responseElement);
		$retour.="var self=this;\n";
		if($hasLoader===true){
			$this->addLoading($retour, $responseElement);
		}
		$retour.="$.post(url,params).done(function( data ) {\n";
		$retour.=$this->_getOnAjaxDone($responseElement, $jqueryDone,$ajaxTransition,$jsCallback)."});\n";

		if ($validation) {
			$retour="$('#".$form."').validate({submitHandler: function(form) {
			".$retour."
			}});\n";
			$retour.="$('#".$form."').submit();\n";
		}
		if ($immediatly)
			$this->jquery_code_for_compile[]=$retour;
		return $retour;
	}

	/**
	 * Performs a post form with ajax
	 * @param string $url The url of the request
	 * @param string $form The form HTML id
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param string $jsCallback javascript code to execute after the request
	 * @param boolean $hasLoader true for showing ajax loader. default : true
	 * @param string $jqueryDone the jquery function call on ajax data. default:html
	 * @param string|callable $ajaxTransition
	 */
	public function postForm($url, $form, $responseElement, $validation=false, $jsCallback=NULL,$hasLoader=true,$jqueryDone="html",$ajaxTransition=null) {
		return $this->_postForm($url, $form, $responseElement, $validation, $jsCallback, NULL, $hasLoader,$jqueryDone,$ajaxTransition,true);
	}

	/**
	 * Performs a delayed post form with ajax
	 * For use on an event
	 * @param string $url The url of the request
	 * @param string $form The form HTML id
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param string $jsCallback javascript code to execute after the request
	 * @param string $attr the html attribute added to the request
	 * @param boolean $hasLoader true for showing ajax loader. default : true
	 * @param string $jqueryDone the jquery function call on ajax data. default:html
	 * @param string|callable $ajaxTransition
	 */
	public function postFormDeferred($url, $form, $responseElement, $validation=false, $jsCallback=NULL,$attr="id",$hasLoader=true,$jqueryDone="html",$ajaxTransition=null) {
		return $this->_postForm($url, $form, $responseElement, $validation, $jsCallback, $attr, $hasLoader,$jqueryDone,$ajaxTransition,false);
	}

	/**
	 * Performs a post form with ajax in response to an event $event on $element
	 * display the result in $responseElement
	 * @param string $event
	 * @param string $element
	 * @param string $url
	 * @param string $form
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"validation"=>false,"jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null)
	 */
	public function postFormOn($event, $element, $url, $form, $responseElement="", $parameters=array()) {
		$preventDefault=true;
		$stopPropagation=true;
		$validation=false;
		$jsCallback=null;
		$attr="id";
		$hasLoader=true;
		$immediatly=true;
		$jqueryDone="html";
		$ajaxTransition=null;
		extract($parameters);
		return $this->_add_event($element, $this->_postForm($url, $form, $responseElement, $validation, $jsCallback, $attr,$hasLoader,$jqueryDone,$ajaxTransition), $event, $preventDefault, $stopPropagation,$immediatly);
	}

	/**
	 * Performs a post form with ajax in response to the click event on $element
	 * display the result in $responseElement
	 * @param string $element
	 * @param string $url
	 * @param string $form
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"validation"=>false,"jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null)
	 */
	public function postFormOnClick($element, $url, $form, $responseElement="", $parameters=array()) {
		return $this->postFormOn("click", $element, $url, $form, $responseElement, $parameters);
	}
}
