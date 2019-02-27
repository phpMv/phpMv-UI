<?php
namespace Ajax\common\html\traits;

use Ajax\service\JString;
use Ajax\common\html\BaseHtml;

/**
 * @author jc
 * @property BaseWidget $_self
 */
trait BaseHtmlPropertiesTrait{

	protected $properties=array ();
	abstract protected function ctrl($name, $value, $typeCtrl);
	abstract protected function removeOldValues(&$oldValue, $allValues);
	abstract protected function _getElementBy($callback,$elements);
	public function getProperties() {
		return $this->_self->properties;
	}

	/**
	 * @param array $properties
	 * @return $this
	 */
	public function setProperties($properties) {
		$this->_self->properties=$properties;
		return $this;
	}

	public function setProperty($name, $value) {
		$this->_self->properties[$name]=$value;
		return $this;
	}

	public function getProperty($name) {
		if (array_key_exists($name, $this->_self->properties))
			return $this->_self->properties[$name];
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @param string $separator
	 * @return $this
	 */
	public function addToProperty($name, $value, $separator=" ") {
		if (\is_array($value)) {
			foreach ( $value as $v ) {
				$this->_self->addToProperty($name, $v, $separator);
			}
		} else if ($value !== "" && $this->_self->propertyContains($name, $value) === false) {
			if(isset($this->_self->properties[$name])){
				$v=$this->_self->properties[$name];
				if (isset($v) && $v !== ""){
					$value=$v . $separator . $value;
				}
			}
			return $this->_self->setProperty($name, $value);
		}
		return $this;
	}

	public function addProperties($properties) {
		$this->_self->properties=array_merge($this->_self->properties, $properties);
		return $this;
	}

	public function removePropertyValue($name, $value) {
		$this->_self->properties[$name]=\str_replace($value, "", $this->_self->properties[$name]);
		return $this;
	}

	protected function removePropertyValues($name, $values) {
		$this->_self->removeOldValues($this->_self->properties[$name], $values);
		return $this;
	}

	protected function addToPropertyUnique($name, $value, $typeCtrl) {
		if (is_string($typeCtrl) && @class_exists($typeCtrl, true))
			$typeCtrl=$typeCtrl::getConstants();
			if (\is_array($typeCtrl)) {
				$this->_self->removeOldValues($this->_self->properties[$name], $typeCtrl);
			}
			return $this->_self->addToProperty($name, $value);
	}

	public function addToPropertyCtrl($name, $value, $typeCtrl) {
		return $this->_self->addToPropertyUnique($name, $value, $typeCtrl);
	}

	public function addToPropertyCtrlCheck($name, $value, $typeCtrl) {
		if ($this->_self->ctrl($name, $value, $typeCtrl) === true) {
			return $this->_self->addToProperty($name, $value);
		}
		return $this;
	}

	public function removeProperty($name) {
		if (\array_key_exists($name, $this->_self->properties))
			unset($this->_self->properties[$name]);
			return $this;
	}
	
	public function propertyContains($propertyName, $value) {
		$values=$this->_self->getProperty($propertyName);
		if (isset($values) && $value!=null) {
			return JString::contains($values, $value);
		}
		return false;
	}

	protected function setPropertyCtrl($name, $value, $typeCtrl) {
		if ($this->_self->ctrl($name, $value, $typeCtrl) === true)
			return $this->_self->setProperty($name, $value);
			return $this;
	}

	protected function getElementByPropertyValue($propertyName,$value, $elements) {
		return $this->_self->_getElementBy(function(BaseHtml $element) use ($propertyName,$value){return $element->propertyContains($propertyName, $value) === true;}, $elements);
	}
}
