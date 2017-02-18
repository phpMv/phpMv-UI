<?php
namespace Ajax\common\html\traits;

use Ajax\service\JString;
use Ajax\common\html\BaseHtml;

/**
 * @author jc
 *
 */
trait BaseHtmlPropertiesTrait{

	protected $properties=array ();
	abstract protected function ctrl($name, $value, $typeCtrl);
	abstract protected function removeOldValues(&$oldValue, $allValues);
	abstract protected function _getElementBy($callback,$elements);
	public function getProperties() {
		return $this->properties;
	}

	/**
	 * @param array $properties
	 * @return BaseHtml
	 */
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

	protected function removePropertyValue($name, $value) {
		$this->properties[$name]=\str_replace($value, "", $this->properties[$name]);
		return $this;
	}

	protected function removePropertyValues($name, $values) {
		$this->removeOldValues($this->properties[$name], $values);
		return $this;
	}

	protected function addToPropertyUnique($name, $value, $typeCtrl) {
		if (@class_exists($typeCtrl, true))
			$typeCtrl=$typeCtrl::getConstants();
			if (\is_array($typeCtrl)) {
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

	public function removeProperty($name) {
		if (\array_key_exists($name, $this->properties))
			unset($this->properties[$name]);
			return $this;
	}

	public function propertyContains($propertyName, $value) {
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

	protected function getElementByPropertyValue($propertyName,$value, $elements) {
		return $this->_getElementBy(function($element) use ($propertyName,$value){return $element->propertyContains($propertyName, $value) === true;}, $elements);
	}
}