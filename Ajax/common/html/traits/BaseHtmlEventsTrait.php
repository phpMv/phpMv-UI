<?php
namespace Ajax\common\html\traits;
use Ajax\JsUtils;
use Ajax\service\AjaxCall;
use Ajax\common\components\SimpleExtComponent;
use Ajax\service\Javascript;

/**
 * @author jc
 * @property SimpleExtComponent $_bsComponent
 * @property string identifier
 */
trait BaseHtmlEventsTrait{

	protected $_events=array ();

	public function addEvent($event, $jsCode, $stopPropagation=false, $preventDefault=false) {
		if ($stopPropagation === true) {
			$jsCode="event.stopPropagation();" . $jsCode;
		}
		if ($preventDefault === true) {
			$jsCode="event.preventDefault();" . $jsCode;
		}
		return $this->_addEvent($event, $jsCode);
	}

	public function _addEvent($event, $jsCode) {
		if (array_key_exists($event, $this->_events)) {
			if (is_array($this->_events[$event])) {
				$this->_events[$event][]=$jsCode;
			} else {
				$this->_events[$event]=array ($this->_events[$event],$jsCode );
			}
		} else {
			$this->_events[$event]=$jsCode;
		}
		return $this;
	}

	public function on($event, $jsCode, $stopPropagation=false, $preventDefault=false) {
		return $this->addEvent($event, $jsCode, $stopPropagation, $preventDefault);
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

	public function addEventsOnRun(JsUtils $js) {
		$this->_eventsOnCreate($js);
		if (isset($this->_bsComponent)) {
			foreach ( $this->_events as $event => $jsCode ) {
				$code=$jsCode;
				if (is_array($jsCode)) {
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
		}
	}

	protected function _eventsOnCreate(JsUtils $js){
		if(isset($this->_events["_create"])){
			$create=$this->_events["_create"];
			if(\is_array($create)){
				$create=\implode("", $create);
			}
			if($create!=="")
				$js->exec($create,true);
			unset($this->_events["_create"]);
		}
	}

	public function _ajaxOn($operation, $event, $url, $responseElement="", $parameters=array()) {
		$params=array ("url" => $url,"responseElement" => $responseElement );
		$params=array_merge($params, $parameters);
		$this->_addEvent($event, new AjaxCall($operation, $params));
		return $this;
	}

	public function getOn($event, $url, $responseElement="", $parameters=array()) {
		return $this->_ajaxOn("get", $event, $url, $responseElement, $parameters);
	}

	public function getOnClick($url, $responseElement="", $parameters=array()) {
		return $this->getOn("click", $url, $responseElement, $parameters);
	}

	public function postOn($event, $url, $params="{}", $responseElement="", $parameters=array()) {
		$parameters["params"]=$params;
		return $this->_ajaxOn("post", $event, $url, $responseElement, $parameters);
	}

	public function postOnClick($url, $params="{}", $responseElement="", $parameters=array()) {
		return $this->postOn("click", $url, $params, $responseElement, $parameters);
	}

	public function postFormOn($event, $url, $form, $responseElement="", $parameters=array()) {
		$parameters["form"]=$form;
		return $this->_ajaxOn("postForm", $event, $url, $responseElement, $parameters);
	}

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
}
