<?php

namespace Ajax\common\html;


use Ajax\service\AjaxCall;
use Ajax\service\JString;
use Ajax\common\components\SimpleExtComponent;
use Ajax\JsUtils;

/**
 * BaseHtml for HTML components
 * @author jc
 * @version 1.001
 */
abstract class BaseHtml extends BaseWidget {
	protected $_template;
	protected $tagName;
	protected $properties=array ();
	protected $_events=array ();
	protected $_wrapBefore=array ();
	protected $_wrapAfter=array ();
	protected $_bsComponent;

	public function getBsComponent() {
		return $this->_bsComponent;
	}

	public function setBsComponent($bsComponent) {
		$this->_bsComponent=$bsComponent;
		return $this;
	}

	protected function getTemplate(JsUtils $js=NULL) {
		return PropertyWrapper::wrap($this->_wrapBefore, $js) . $this->_template . PropertyWrapper::wrap($this->_wrapAfter, $js);
	}

	public function getProperties() {
		return $this->properties;
	}

	public function setProperties($properties) {
		$this->properties=$properties;
		return $this;
	}

	public function setProperty($name, $value) {
		$this->properties[$name]=$value;
		return $this;
	}

	public function getProperty($name) {
		if (array_key_exists($name, $this->properties))
			return $this->properties[$name];
	}

	public function addToProperty($name, $value, $separator=" ") {
		if (\is_array($value)) {
			foreach ( $value as $v ) {
				$this->addToProperty($name, $v, $separator);
			}
		} else if ($value !== "" && $this->propertyContains($name, $value) === false) {
			$v=@$this->properties[$name];
			if (isset($v) && $v !== "")
				$v=$v . $separator . $value;
			else
				$v=$value;

			return $this->setProperty($name, $v);
		}
		return $this;
	}

	public function addProperties($properties) {
		$this->properties=array_merge($this->properties, $properties);
		return $this;
	}

	public function compile(JsUtils $js=NULL, &$view=NULL) {
		$result=$this->getTemplate($js);
		foreach ( $this as $key => $value ) {
			if (JString::startswith($key, "_") === false && $key !== "events") {
				if (is_array($value)) {
					$v=PropertyWrapper::wrap($value, $js);
				} else {
					$v=$value;
				}
				$result=str_ireplace("%" . $key . "%", $v, $result);
			}
		}
		if (isset($js)===true) {
			$this->run($js);
			if (isset($view) === true) {
				$js->addViewElement($this->identifier, $result, $view);
			}
		}
		return $result;
	}

	protected function ctrl($name, $value, $typeCtrl) {
		if (is_array($typeCtrl)) {
			if (array_search($value, $typeCtrl) === false) {
				throw new \Exception("La valeur passée `" . $value . "` à la propriété `" . $name . "` ne fait pas partie des valeurs possibles : {" . implode(",", $typeCtrl) . "}");
			}
		} else {
			if (!$typeCtrl($value)) {
				throw new \Exception("La fonction " . $typeCtrl . " a retourné faux pour l'affectation de la propriété " . $name);
			}
		}
		return true;
	}

	protected function propertyContains($propertyName, $value) {
		$values=$this->getProperty($propertyName);
		if (isset($values)) {
			return JString::contains($values, $value);
		}
		return false;
	}

	protected function setPropertyCtrl($name, $value, $typeCtrl) {
		if ($this->ctrl($name, $value, $typeCtrl) === true)
			return $this->setProperty($name, $value);
		return $this;
	}

	protected function setMemberCtrl(&$name, $value, $typeCtrl) {
		if ($this->ctrl($name, $value, $typeCtrl) === true) {
			return $name=$value;
		}
		return $this;
	}

	protected function addToMemberUnique(&$name, $value, $typeCtrl, $separator=" ") {
		if (is_array($typeCtrl)) {
			$this->removeOldValues($name, $typeCtrl);
			$name.=$separator . $value;
		}
		return $this;
	}

	protected function removePropertyValue($name, $value) {
		$this->properties[$name]=\str_replace($value, "", $this->properties[$name]);
		return $this;
	}

	protected function removePropertyValues($name, $values) {
		$this->removeOldValues($this->properties[$name], $values);
		return $this;
	}

	public function removeProperty($name) {
		if (\array_key_exists($name, $this->properties))
			unset($this->properties[$name]);
		return $this;
	}

	protected function addToMemberCtrl(&$name, $value, $typeCtrl, $separator=" ") {
		if ($this->ctrl($name, $value, $typeCtrl) === true) {
			if (is_array($typeCtrl)) {
				$this->removeOldValues($name, $typeCtrl);
			}
			$name.=$separator . $value;
		}
		return $this;
	}

	protected function addToMember(&$name, $value, $separator=" ") {
		$name=str_ireplace($value, "", $name) . $separator . $value;
		return $this;
	}

	protected function addToPropertyUnique($name, $value, $typeCtrl) {
		if (@class_exists($typeCtrl, true))
			$typeCtrl=$typeCtrl::getConstants();
		if (is_array($typeCtrl)) {
			$this->removeOldValues($this->properties[$name], $typeCtrl);
		}
		return $this->addToProperty($name, $value);
	}

	public function addToPropertyCtrl($name, $value, $typeCtrl) {
		return $this->addToPropertyUnique($name, $value, $typeCtrl);
	}

	public function addToPropertyCtrlCheck($name, $value, $typeCtrl) {
		if ($this->ctrl($name, $value, $typeCtrl) === true) {
			return $this->addToProperty($name, $value);
		}
		return $this;
	}

	protected function removeOldValues(&$oldValue, $allValues) {
		$oldValue=str_ireplace($allValues, "", $oldValue);
		$oldValue=trim($oldValue);
	}

	/**
	 *
	 * @param JsUtils $js
	 * @return SimpleExtComponent
	 */
	public abstract function run(JsUtils $js);

	public function getTagName() {
		return $this->tagName;
	}

	public function setTagName($tagName) {
		$this->tagName=$tagName;
		return $this;
	}

	public function fromArray($array) {
		foreach ( $this as $key => $value ) {
			if (array_key_exists($key, $array) && !JString::startswith($key, "_")) {
				$setter="set" . ucfirst($key);
				$this->$setter($array[$key]);
				unset($array[$key]);
			}
		}
		foreach ( $array as $key => $value ) {
			if (method_exists($this, $key)) {
				try {
					$this->$key($value);
					unset($array[$key]);
				} catch ( \Exception $e ) {
					// Nothing to do
				}
			} else {
				$setter="set" . ucfirst($key);
				if (method_exists($this, $setter)) {
					try {
						$this->$setter($value);
						unset($array[$key]);
					} catch ( \Exception $e ) {
						// Nothing to do
					}
				}
			}
		}
		return $array;
	}

	public function fromDatabaseObjects($objects, $function) {
		if (isset($objects)) {
			foreach ( $objects as $object ) {
				$this->fromDatabaseObject($object, $function);
			}
		}
		return $this;
	}

	public function fromDatabaseObject($object, $function) {
	}

	public function wrap($before, $after="") {
		if (isset($before)) {
			array_unshift($this->_wrapBefore, $before);
			// $this->_wrapBefore[]=$before;
		}
		$this->_wrapAfter[]=$after;
		return $this;
	}

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

	public function addEventsOnRun(JsUtils $js) {
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

	public function getElementById($identifier, $elements) {
		if (is_array($elements)) {
			$flag=false;
			$index=0;
			while ( !$flag && $index < sizeof($elements) ) {
				if ($elements[$index] instanceof BaseHtml)
					$flag=($elements[$index]->getIdentifier() === $identifier);
				$index++;
			}
			if ($flag === true)
				return $elements[$index - 1];
		} elseif ($elements instanceof BaseHtml) {
			if ($elements->getIdentifier() === $identifier)
				return $elements;
		}
		return null;
	}

	protected function getElementByPropertyValue($propertyName,$value, $elements) {
		if (is_array($elements)) {
			$flag=false;
			$index=0;
			while ( !$flag && $index < sizeof($elements) ) {
				if ($elements[$index] instanceof BaseHtml)
					$flag=($elements[$index]->propertyContains($propertyName, $value) === true);
					$index++;
			}
			if ($flag === true)
				return $elements[$index - 1];
		} elseif ($elements instanceof BaseHtml) {
			if ($elements->propertyContains($propertyName, $value) === true)
				return $elements;
		}
		return null;
	}

	public function __toString() {
		return $this->compile();
	}

	/**
	 * Puts HTML values in quotes for use in jQuery code
	 * unless the supplied value contains the Javascript 'this' or 'event'
	 * object, in which case no quotes are added
	 *
	 * @param string $value
	 * @return string
	 */
	public function _prep_value($value) {
		if (is_array($value)) {
			$value=implode(",", $value);
		}
		if (strrpos($value, 'this') === false && strrpos($value, 'event') === false) {
			$value='"' . $value . '"';
		}
		return $value;
	}

	public function jsDoJquery($jqueryCall, $param="") {
		return "$('#" . $this->identifier . "')." . $jqueryCall . "(" . $this->_prep_value($param) . ");";
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

	protected function setWrapBefore($wrapBefore) {
		$this->_wrapBefore=$wrapBefore;
		return $this;
	}

	protected function setWrapAfter($wrapAfter) {
		$this->_wrapAfter=$wrapAfter;
		return $this;
	}
}