<?php

namespace Ajax\common\traits;

use Ajax\service\JString;
use Ajax\service\PhalconUtils;
use Symfony\Component\Config\Definition\Exception\Exception;
trait JqueryAjaxTrait {

	protected $ajaxLoader='<span></span><span></span><span></span><span></span><span></span>';

	public abstract function _prep_value($value);
	public abstract function _add_event($element, $js, $event, $preventDefault=false, $stopPropagation=false,$immediatly=true);
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

	public function _get($url, $params="{}", $responseElement="", $jsCallback=NULL, $attr="id", $hasLoader=true,$immediatly=false) {
		return $this->_ajax("get", $url,$params,$responseElement,$jsCallback,$attr,$hasLoader,$immediatly);
	}
	public function _post($url, $params="{}", $responseElement="", $jsCallback=NULL, $attr="id", $hasLoader=true,$immediatly=false) {
		return $this->_ajax("post", $url,$params,$responseElement,$jsCallback,$attr,$hasLoader,$immediatly);
	}

	protected function _ajax($method,$url, $params="{}", $responseElement="", $jsCallback=NULL, $attr="id", $hasLoader=true,$immediatly=false) {
		if(JString::isNull($params)){$params="{}";}
		$jsCallback=isset($jsCallback) ? $jsCallback : "";
		$retour=$this->_getAjaxUrl($url, $attr);
		$responseElement=$this->_getResponseElement($responseElement);
		$retour.="var self=this;\n";
		if($hasLoader===true){
			$this->addLoading($retour, $responseElement);
		}
		$retour.="$.".$method."(url,".$params.").done(function( data ) {\n";
		$retour.=$this->_getOnAjaxDone($responseElement, $jsCallback)."});\n";
		if ($immediatly)
			$this->jquery_code_for_compile[]=$retour;
			return $retour;
	}

	protected function _getAjaxUrl($url,$attr){
		$url=$this->_correctAjaxUrl($url);
		$retour="url='".$url."';\n";
		$slash="/";
		if(JString::endswith($url, "/")===true)
			$slash="";
		if(JString::isNotNull($attr)){
			if ($attr=="value")
				$retour.="url=url+'".$slash."'+$(this).val();\n";
				else if($attr!=null && $attr!=="")
					$retour.="url=url+'".$slash."'+($(this).attr('".$attr."')||'');\n";
		}
		return $retour;
	}

	protected function _getOnAjaxDone($responseElement,$jsCallback){
		$retour="";
		if ($responseElement!=="") {
			$retour="\t$({$responseElement}).html( data );\n";
		}
		$retour.="\t".$jsCallback."\n";
		return $retour;
	}

	protected function _getResponseElement($responseElement){
		if ($responseElement!=="") {
			$responseElement=$this->_prep_value($responseElement);
		}
		return $responseElement;
	}

	protected function _correctAjaxUrl($url) {
		if ($url!=="/" && JString::endsWith($url, "/")===true)
			$url=substr($url, 0, strlen($url)-1);
		if (strncmp($url, 'http://', 7)!=0&&strncmp($url, 'https://', 8)!=0) {
			$url=$this->jsUtils->getUrl($url);
		}
		return $url;
	}

	/**
	 * Makes an ajax request and receives the JSON data types by assigning DOM elements with the same name
	 * @param string $url the request address
	 * @param string $params Paramètres passés au format JSON
	 * @param string $method Method use
	 * @param string $jsCallback javascript code to execute after the request
	 * @param boolean $immediatly
	 */
	public function _json($url, $method="get", $params="{}", $jsCallback=NULL, $attr="id", $context="document",$immediatly=false) {
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
	 * Makes an ajax request and receives the JSON data types by assigning DOM elements with the same name when $event fired on $element
	 * @param string $element
	 * @param string $event
	 * @param string $url the request address
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","params"=>"{}","method"=>"get","immediatly"=>true)
	 */
	public function _jsonOn($event,$element, $url,$parameters=array()) {
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
	 * Makes an ajax request and receives a JSON array data types by copying and assigning them to the DOM elements with the same name
	 * @param string $url the request address
	 * @param string $params Paramètres passés au format JSON
	 * @param string $method Method use
	 * @param string $jsCallback javascript code to execute after the request
	 * @param string $context jquery DOM element, array container.
	 * @param boolean $immediatly
	 */
	public function _jsonArray($maskSelector, $url, $method="get", $params="{}", $jsCallback=NULL, $attr="id", $context=null,$immediatly=false) {
		$jsCallback=isset($jsCallback) ? $jsCallback : "";
		$retour=$this->_getAjaxUrl($url, $attr);
		if($context===null){
			$appendTo="\t\tnewElm.appendTo($('".$maskSelector."').parent());\n";
			$newElm = "$('#'+newId)";
		}else{
			$appendTo="\t\tnewElm.appendTo(".$context.");\n";
			$newElm = $context.".find('#'+newId)";
		}
		$retour.="var self = $(this);\n$.{$method}(url,".$params.").done(function( data ) {\n";
		$retour.="\tdata=$.parseJSON(data);$.each(data, function(index, value) {\n"."\tvar created=false;var maskElm=$('".$maskSelector."').first();maskElm.hide();"."\tvar newId=(maskElm.attr('id') || 'mask')+'-'+index;"."\tvar newElm=".$newElm.";\n"."\tif(!newElm.length){\n"."\t\tnewElm=maskElm.clone();newElm.attr('id',newId);\n";
		$retour.= $appendTo;
		$retour.="\t}\n"."\tfor(var key in value){\n"."\t\t\tvar html = $('<div />').append($(newElm).clone()).html();\n"."\t\t\tif(html.indexOf('[['+key+']]')>-1){\n"."\t\t\t\tcontent=$(html.split('[['+key+']]').join(value[key]));\n"."\t\t\t\t$(newElm).replaceWith(content);newElm=content;\n"."\t\t\t}\n"."\t\tvar sel='[data-id=\"'+key+'\"]';if($(sel,newElm).length){\n"."\t\t\tvar selElm=$(sel,newElm);\n"."\t\t\t if(selElm.is('[value]')) { selElm.attr('value',value[key]);selElm.val(value[key]);} else { selElm.html(value[key]); }\n"."\t\t}\n"."}\n"."\t$(newElm).show(true);"."\n"."\t$(newElm).removeClass('hide');"."});\n";
		$retour.="\t$(document).trigger('jsonReady',[data]);\n";
		$retour.="\t".$jsCallback."\n"."});\n";
		if ($immediatly)
			$this->jquery_code_for_compile[]=$retour;
			return $retour;
	}
	/**
	 * Makes an ajax request and receives the JSON data types by assigning DOM elements with the same name when $event fired on $element
	 * @param string $element
	 * @param string $event
	 * @param string $url the request address
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","params"=>"{}","method"=>"get", "context"=>null)
	 */
	public function _jsonArrayOn($event,$element, $maskSelector,$url,$parameters=array()) {
		$preventDefault=true;
		$stopPropagation=true;
		$jsCallback=null;
		$attr="id";
		$method="get";
		$context = null;
		$params="{}";
		$immediatly=true;
		extract($parameters);
		return $this->_add_event($element, $this->_jsonArray($maskSelector,$url,$method, $params,$jsCallback, $attr, $context), $event, $preventDefault, $stopPropagation,$immediatly);
	}

	public function _postForm($url, $form, $responseElement, $validation=false, $jsCallback=NULL, $attr="id", $hasLoader=true,$immediatly=false) {
		$jsCallback=isset($jsCallback) ? $jsCallback : "";
		$retour=$this->_getAjaxUrl($url, $attr);
		$retour.="\nvar params=$('#".$form."').serialize();\n";
		$responseElement=$this->_getResponseElement($responseElement);
		$retour.="var self=this;\n";
		if($hasLoader===true){
			$this->addLoading($retour, $responseElement);
		}
		$retour.="$.post(url,params).done(function( data ) {\n";
		$retour.=$this->_getOnAjaxDone($responseElement, $jsCallback)."});\n";

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
	 * Effectue un get vers $url sur l'évènement $event de $element en passant les paramètres $params
	 * puis affiche le résultat dans $responseElement
	 * @param string $element
	 * @param string $event
	 * @param string $url
	 * @param string $params queryString parameters (JSON format). default : {}
	 * @param string $responseElement
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true)
	 */
	public function _getOn($event,$element, $url, $params="{}", $responseElement="", $parameters=array()) {
		$preventDefault=true;
		$stopPropagation=true;
		$jsCallback=null;
		$attr="id";
		$hasLoader=true;
		$immediatly=true;
		extract($parameters);
		return $this->_add_event($element, $this->_get($url, $params, $responseElement, $jsCallback, $attr,$hasLoader), $event, $preventDefault, $stopPropagation,$immediatly);
	}

	/**
	 * Effectue un post vers $url sur l'évènement $event de $element en passant les paramètres $params
	 * puis affiche le résultat dans $responseElement
	 * @param string $element
	 * @param string $event
	 * @param string $url
	 * @param string $params queryString parameters (JSON format). default : {}
	 * @param string $responseElement
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true)
	 */
	public function _postOn($event,$element, $url, $params="{}", $responseElement="", $parameters=array()) {
		$preventDefault=true;
		$stopPropagation=true;
		$jsCallback=null;
		$attr="id";
		$hasLoader=true;
		$immediatly=true;
		extract($parameters);
		return $this->_add_event($element, $this->_post($url, $params, $responseElement, $jsCallback, $attr,$hasLoader), $event, $preventDefault, $stopPropagation,$immediatly);
	}

	/**
	 * Effectue un post vers $url sur l'évènement $event de $element en passant les paramètres du formulaire $form
	 * puis affiche le résultat dans $responseElement
	 * @param string $element
	 * @param string $event
	 * @param string $url
	 * @param string $form
	 * @param string $responseElement
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"validation"=>false,"jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true)
	 */
	public function _postFormOn($event,$element, $url, $form, $responseElement="", $parameters=array()) {
		$preventDefault=true;
		$stopPropagation=true;
		$validation=false;
		$jsCallback=null;
		$attr="id";
		$hasLoader=true;
		$immediatly=true;
		extract($parameters);
		return $this->_add_event($element, $this->_postForm($url, $form, $responseElement, $validation, $jsCallback, $attr,$hasLoader), $event, $preventDefault, $stopPropagation,$immediatly);
	}
}