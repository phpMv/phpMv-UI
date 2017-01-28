<?php

namespace Ajax\common\html;


use Ajax\service\JString;
use Ajax\common\components\SimpleExtComponent;
use Ajax\JsUtils;
use Ajax\common\html\traits\BaseHtmlEventsTrait;

/**
 * BaseHtml for HTML components
 * @author jc
 * @version 1.2
 */
abstract class BaseHtml extends BaseWidget {
	use BaseHtmlEventsTrait;
	protected $_template;
	protected $tagName;
	protected $properties=array ();
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
			if(array_key_exists($key, $array)===true)
				$this->_callSetter("set" . ucfirst($key), $key, $array[$key], $array);
		}
		foreach ( $array as $key => $value ) {
			if($this->_callSetter($key, $key, $value, $array)===false){
				$this->_callSetter("set" . ucfirst($key), $key, $value, $array);
			}
		}
		return $array;
	}

	private function _callSetter($setter,$key,$value,&$array){
		$result=false;
		if (method_exists($this, $setter) && !JString::startswith($key, "_")) {
			try {
				$this->$setter($value);
				unset($array[$key]);
				$result=true;
			} catch ( \Exception $e ) {
				$result=false;
			}
		}
		return $result;
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
		}
		$this->_wrapAfter[]=$after;
		return $this;
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

	protected function setWrapBefore($wrapBefore) {
		$this->_wrapBefore=$wrapBefore;
		return $this;
	}

	protected function setWrapAfter($wrapAfter) {
		$this->_wrapAfter=$wrapAfter;
		return $this;
	}
}