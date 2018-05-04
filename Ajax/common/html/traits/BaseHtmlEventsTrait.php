<?php
namespace Ajax\common\html\traits;
use Ajax\JsUtils;
use Ajax\service\AjaxCall;
use Ajax\service\Javascript;
use Ajax\common\html\BaseHtml;

/**
 * @author jc
 * @property \Ajax\common\components\SimpleExtComponent $_bsComponent
 * @property string identifier
 * @property BaseHtml _self
 */
trait BaseHtmlEventsTrait{

	protected $_events=array ();

	/**
	 * @param string $event
	 * @param string|AjaxCall $jsCode
	 * @param boolean $stopPropagation
	 * @param boolean $preventDefault
	 * @return BaseHtml
	 */
	public function addEvent($event, $jsCode, $stopPropagation=false, $preventDefault=false) {
		if ($stopPropagation === true) {
			$jsCode=Javascript::$stopPropagation . $jsCode;
		}
		if ($preventDefault === true) {
			$jsCode=Javascript::$preventDefault . $jsCode;
		}
		return $this->_addEvent($event, $jsCode);
	}
	
	public function trigger($event,$params="[]"){
		$this->executeOnRun('$("#'.$this->identifier.'").trigger("'.$event.'",'.$params.');');
	}
	
	public function jsTrigger($event,$params="[this]"){
		return $this->jsDoJquery("trigger",["'".$event."'",$params]);
	}

	/**
	 * @param string $event
	 * @param string|AjaxCall $jsCode
	 * @return BaseHtml
	 */
	public function _addEvent($event, $jsCode) {
		if (array_key_exists($event, $this->_events)) {
			if (\is_array($this->_events[$event])) {
				if(array_search($jsCode, $this->_events[$event])===false){
					$this->_events[$event][]=$jsCode;
				}
			} else {
				$this->_events[$event]=array ($this->_events[$event],$jsCode );
			}
		} else {
			$this->_events[$event]=$jsCode;
		}
		return $this;
	}

	/**
	 * @param string $event
	 * @param string $jsCode
	 * @param boolean $stopPropagation
	 * @param boolean $preventDefault
	 * @return BaseHtml
	 */
	public function on($event, $jsCode, $stopPropagation=false, $preventDefault=false) {
		return $this->_self->addEvent($event, $jsCode, $stopPropagation, $preventDefault);
	}

	public function onClick($jsCode, $stopPropagation=false, $preventDefault=true) {
		return $this->on("click", $jsCode, $stopPropagation, $preventDefault);
	}

	public function setClick($jsCode) {
		return $this->onClick($jsCode);
	}

	public function onCreate($jsCode){
		if(isset($this->_events["_create"])){
			$this->_events["_create"][]=$jsCode;
		}else{
			$this->_events["_create"]=[$jsCode];
		}
		return $this;
	}

	public function addEventsOnRun(JsUtils $js=NULL) {
		$this->_eventsOnCreate($js);
		if (isset($this->_bsComponent)) {
			foreach ( $this->_events as $event => $jsCode ) {
				$code=$jsCode;
				if (\is_array($jsCode)) {
					$code="";
					foreach ( $jsCode as $jsC ) {
						if ($jsC instanceof AjaxCall) {
							$code.="\n" . $jsC->compile($js);
						} else {
							$code.="\n" . $jsC;
						}
					}
				} elseif ($jsCode instanceof AjaxCall) {
					$code=$jsCode->compile($js);
				}
				$this->_bsComponent->addEvent($event, $code);
			}
			$this->_events=array ();
			return $this->_bsComponent->getScript();
		}
		return "";
	}

	protected function _eventsOnCreate(JsUtils $js=NULL){
		if(isset($this->_events["_create"])){
			$create=$this->_events["_create"];
			if(\is_array($create)){
				$create=\implode("", $create);
			}
			if(isset($js) && $create!=="")
				$js->exec($create,true);
			unset($this->_events["_create"]);
		}
	}

	/**
	 * @param string $operation http method get, post, postForm or json
	 * @param string $event the event that triggers the request
	 * @param string $url The url of the request
	 * @param string $responseElement The selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>null,"headers"=>null,"historize"=>false)
	 * @return $this
	 */
	public function _ajaxOn($operation, $event, $url, $responseElement="", $parameters=array()) {
		$params=array ("url" => $url,"responseElement" => $responseElement );
		$params=array_merge($params, $parameters);
		$this->_addEvent($event, new AjaxCall($operation, $params));
		return $this;
	}

	/**
	 * Performs a get to $url on the event $event on $element
	 * and display it in $responseElement
	 * @param string $event the event that triggers the get request
	 * @param string $url The url of the request
	 * @param string $responseElement The selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>null,"headers"=>null,"historize"=>false)
	 * @return $this
	 **/
	public function getOn($event, $url, $responseElement="", $parameters=array()) {
		return $this->_ajaxOn("get", $event, $url, $responseElement, $parameters);
	}

	/**
	 * Performs a get to $url on the click event on $element
	 * and display it in $responseElement
	 * @param string $url The url of the request
	 * @param string $responseElement The selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>null,"headers"=>null,"historize"=>false)
	 * @return $this
	 **/
	public function getOnClick($url, $responseElement="", $parameters=array()) {
		return $this->getOn("click", $url, $responseElement, $parameters);
	}

	/**
	 * Performs a post to $url on the event $event on $element
	 * and display it in $responseElement
	 * @param string $event the event that triggers the post request
	 * @param string $url The url of the request
	 * @param string $params the request parameters in JSON format
	 * @param string $responseElement The selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>null,"headers"=>null,"historize"=>false)
	 * @return $this
	 **/
	public function postOn($event, $url, $params="{}", $responseElement="", $parameters=array()) {
		$allParameters=[];
		if(isset($parameters["params"])){
			$allParameters[]=JsUtils::_correctParams($parameters["params"]);
		}
		if(isset($params)){
			$allParameters[]=JsUtils::_correctParams($params);
		}
		$parameters["params"]=\implode("+'&'+", $allParameters);
		return $this->_ajaxOn("post", $event, $url, $responseElement, $parameters);
	}
	
	/**
	 * Performs a post to $url on the click event on $element
	 * and display it in $responseElement
	 * @param string $url The url of the request
	 * @param string $params the request parameters in JSON format
	 * @param string $responseElement The selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>null,"headers"=>null,"historize"=>false)
	 * @return $this
	 **/
	public function postOnClick($url, $params="{}", $responseElement="", $parameters=array()) {
		return $this->postOn("click", $url, $params, $responseElement, $parameters);
	}

	/**
	 * Performs a post form with ajax
	 * @param string $event the event that triggers the post request
	 * @param string $url The url of the request
	 * @param string $form The form HTML id
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null,"historize"=>false)
	 * @return $this
	 */
	public function postFormOn($event, $url, $form, $responseElement="", $parameters=array()) {
		$parameters["form"]=$form;
		return $this->_ajaxOn("postForm", $event, $url, $responseElement, $parameters);
	}

	/**
	 * Performs a post form with ajax on click
	 * @param string $url The url of the request
	 * @param string $form The form HTML id
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null,"historize"=>false)
	 * @return $this
	 */
	public function postFormOnClick($url, $form, $responseElement="", $parameters=array()) {
		return $this->postFormOn("click", $url, $form, $responseElement, $parameters);
	}

	public function jsDoJquery($jqueryCall, $param="") {
		return "$('#" . $this->identifier . "')." . $jqueryCall . "(" . Javascript::prep_value($param) . ");";
	}

	public function executeOnRun($jsCode) {
		return $this->_addEvent("execute", $jsCode);
	}

	public function jsHtml($content="") {
		return $this->jsDoJquery("html", $content);
	}

	public function jsShow() {
		return $this->jsDoJquery("show");
	}

	public function jsHide() {
		return $this->jsDoJquery("hide");
	}

	public function jsToggle($value) {
		return $this->jsDoJquery("toggle",$value);
	}
	/**
	 * @return array
	 */
	public function getEvents() {
		return $this->_events;
	}

}
