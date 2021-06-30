<?php
namespace Ajax\semantic\widgets\base;

use Ajax\service\JString;
use Ajax\service\JArray;
use Ajax\service\JReflection;

class InstanceViewer {

	protected $widgetIdentifier;

	protected $instance;

	protected $reflect;

	protected $properties;

	protected $visibleProperties;

	protected $values;

	protected $afterCompile;

	protected $captions;

	protected $captionCallback;

	protected $defaultValueFunction;

	protected $groupByFields;

	public static $index = 0;

	public function __construct($identifier, $instance = NULL, $captions = NULL) {
		$this->widgetIdentifier = $identifier;
		$this->values = [];
		$this->afterCompile = [];
		if (isset($instance))
			$this->setInstance($instance);
		$this->setCaptions($captions);
		$this->captionCallback = NULL;
		$this->defaultValueFunction = function ($name, $value) {
			return $value;
		};
	}

	public function moveFieldTo($from, $to) {
		if (JArray::moveElementTo($this->visibleProperties, $from, $to)) {
			return JArray::moveElementTo($this->values, $from, $to);
		}
		return false;
	}

	public function swapFields($index1, $index2) {
		if (JArray::swapElements($this->visibleProperties, $index1, $index2)) {
			return JArray::swapElements($this->values, $index1, $index2);
		}
		return false;
	}

	public function removeField($index) {
		\array_splice($this->visibleProperties, $index, 1);
		\array_splice($this->values, $index, 1);
		\array_splice($this->captions, $index, 1);
		return $this;
	}

	public function getValues() {
		$values = [];
		$index = 0;
		$count = $this->count();
		$hasGroupby = is_array($this->groupByFields);
		if (! $hasGroupby) {
			while ($index < $count) {
				$values[] = $this->getValue($index ++);
			}
		} else {
			while ($index < $count) {
				if (array_search($index, $this->groupByFields) === false) {
					$values[] = $this->getValue($index);
				}
				$index ++;
			}
		}
		return $values;
	}

	public function getIdentifier($index = NULL) {
		if (! isset($index))
			$index = self::$index;
		$value = $index;
		if (isset($this->values["identifier"])) {
			if (\is_string($this->values["identifier"]))
				$value = JReflection::callMethod($this->instance, $this->values["identifier"], []);
			else
				$value = $this->values["identifier"]($index, $this->instance);
		}
		return $value;
	}

	public function getValue($index) {
		$property = $this->properties[$index];
		return $this->_getValue($property, $index);
	}

	protected function _beforeAddProperty($index, &$field) {}

	protected function _getDefaultValue($name, $value, $index) {
		$func = $this->defaultValueFunction;
		return $func($name, $value, $index, $this->instance);
	}

	protected function _getPropertyValue(\ReflectionProperty $property) {
		$property->setAccessible(true);
		return $property->getValue($this->instance);
	}

	protected function _getValue($property, $index) {
		$value = null;
		$propertyName = $property;
		if ($property instanceof \ReflectionProperty) {
			$value = $this->_getPropertyValue($property);
			$propertyName = $property->getName();
		} elseif (\is_callable($property) && \array_search($property, ['system','date']) === false){
			try{
				$value = $property($this->instance);
			}catch(\Error $e){}
		}
		elseif (\is_array($property)) {
			$values = \array_map(function ($v) use ($index) {
				return $this->_getValue($v, $index);
			}, $property);
			$value = \implode('', $values);
		} elseif (\is_string($property)) {
			$value = '';
			if (isset($this->instance->{$property})) {
				$value = $this->instance->{$property};
			} elseif (\method_exists($this->instance, $getter = JReflection::getterName($property))) {
				$value = JReflection::callMethod($this->instance, $getter, []);
			}
		}

		return $this->_postGetValue($index, $propertyName, $value);
	}

	protected function _postGetValue($index, $propertyName, $value) {
		if (isset($this->values[$index])) {
			$value = $this->values[$index]($value, $this->instance, $index, self::$index);
		} else {
			$value = $this->_getDefaultValue($propertyName, $value, $index);
		}
		if (isset($this->afterCompile[$index])) {
			if (\is_callable($this->afterCompile[$index])) {
				$this->afterCompile[$index]($value, $this->instance, self::$index);
			}
		}
		return $value;
	}

	public function insertField($index, $field, $key = null) {
		if (isset($key)) {
			array_splice($this->visibleProperties, $index, 0, [
				$key => $field
			]);
		} else {
			array_splice($this->visibleProperties, $index, 0, $field);
		}
		return $this;
	}

	public function sortColumnContent($index, $array) {
		if (isset($this->visibleProperties[$index])) {
			if (is_array($this->visibleProperties[$index])) {
				$this->visibleProperties[$index] = JArray::sortAssociative($this->visibleProperties[$index], $array);
			}
		}
		return $this;
	}

	public function insertInField($index, $field, $key = null) {
		$vb = $this->visibleProperties;
		if (isset($vb[$index])) {
			if (isset($key)) {
				if (\is_array($vb[$index])) {
					$this->visibleProperties[$index][$key] = $field;
				} else {
					$this->visibleProperties[$index] = [
						$vb[$index],
						$key => $field
					];
				}
			} else {
				if (\is_array($vb[$index])) {
					$this->visibleProperties[$index][] = $field;
				} else {
					$this->visibleProperties[$index] = [
						$vb[$index],
						$field
					];
				}
			}
		} else {
			return $this->insertField($index, $field);
		}
		return $this;
	}

	public function addField($field, $key = null) {
		if (isset($key)) {
			$this->visibleProperties[] = [
				$key => $field
			];
		} else {
			$this->visibleProperties[] = $field;
		}
		return $this;
	}

	public function addFields($fields) {
		$this->visibleProperties = \array_merge($this->visibleProperties, $fields);
		return $this;
	}

	public function count() {
		return \sizeof($this->properties);
	}

	public function visiblePropertiesCount() {
		return \sizeof($this->visibleProperties);
	}

	public function getProperty($index) {
		return $this->properties[$index];
	}

	public function getFieldName($index) {
		$property = $this->getProperty($index);
		if ($property instanceof \ReflectionProperty) {
			$result = $property->getName();
		} elseif (\is_callable($property)) {
			$result = $this->visibleProperties[$index];
		} else {
			$result = $property;
		}
		return $result;
	}

	protected function showableProperty(\ReflectionProperty $rProperty) {
		return JString::startswith($rProperty->getName(), "_") === false;
	}

	public function setInstance($instance) {
		if (\is_string($instance) && \class_exists($instance)) {
			$instance = new $instance();
		}
		$this->instance = $instance;
		$this->properties = [];
		try{
			$this->reflect = new \ReflectionClass($instance);
			if (JArray::count($this->visibleProperties) === 0) {
				$this->properties = $this->getDefaultProperties();
			} else {
				foreach ($this->visibleProperties as $property) {
					$this->setInstanceProperty($property);
				}
			}
		}catch (\Throwable $th){
			
		}
		return $this;
	}

	private function setInstanceProperty($property) {
		if (\is_callable($property)) {
			$this->properties[] = $property;
		} elseif (\is_string($property)) {
			try {
				$this->_beforeAddProperty(\sizeof($this->properties), $property);
				$rProperty = $this->reflect->getProperty($property);
				$this->properties[] = $rProperty;
			} catch (\Exception $e) {
				$this->_beforeAddProperty(\sizeof($this->properties), $property);
				$this->properties[] = $property;
			}
		} elseif (\is_int($property)) {
			$props = $this->getDefaultProperties();
			if (isset($props[$property]))
				$this->properties[] = $props[$property];
			else
				$this->properties[] = $property;
		} else {
			$this->properties[] = $property;
		}
	}

	protected function getDefaultProperties() {
		$result = [];
		$properties = $this->reflect->getProperties();
		foreach ($properties as $property) {
			$showable = $this->showableProperty($property);
			if ($showable !== false) {
				$result[] = $property;
			}
		}
		return $result;
	}

	public function setVisibleProperties($visibleProperties) {
		$this->visibleProperties = $visibleProperties;
		return $this;
	}

	public function setValueFunction($index, $callback) {
		$this->values[$index] = $callback;
		return $this;
	}

	public function setIdentifierFunction($callback) {
		$this->values["identifier"] = $callback;
		return $this;
	}

	public static function setIndex($index) {
		self::$index = $index;
	}

	public function getProperties() {
		return $this->properties;
	}

	public function getCaption($index) {
		if (isset($this->captions[$index])) {
			return $this->captions[$index];
		}
		if ($this->properties[$index] instanceof \ReflectionProperty)
			return $this->properties[$index]->getName();
		elseif (\is_callable($this->properties[$index]))
			return "";
		else
			return $this->properties[$index];
	}

	public function getCaptions() {
		$hasGroupby = is_array($this->groupByFields);
		$count = $this->count();
		$moreAdded = false;
		if (isset($this->captions)) {
			$captions = \array_values($this->captions);
			$gbSize = $hasGroupby ? sizeof($this->groupByFields) : 0;
			$captionsSize = \sizeof($captions);
			for ($i = $captionsSize; $i < $count - $gbSize; $i ++) {
				$captions[] = "";
				$moreAdded = true;
			}
		} else {
			$captions = [];
			$index = 0;
			while ($index < $count) {
				$captions[] = $this->getCaption($index ++);
			}
		}
		if ($hasGroupby && sizeof($captions) >= $count && ! $moreAdded) {
			$captions = JArray::removeByKeys($captions, $this->groupByFields);
		}
		if (isset($this->captionCallback) && \is_callable($this->captionCallback)) {
			$callback = $this->captionCallback;
			$callback($captions, $this->instance);
		}
		return $captions;
	}

	public function setCaption($index, $caption) {
		if (isset($this->captions) === false)
			$this->captions = [];
		$this->captions[$index] = $caption;
		return $this;
	}

	public function setCaptions($captions) {
		$this->captions = $captions;
		return $this;
	}

	/**
	 * Associates a $callback function after the compilation of the field at $index position
	 * The $callback function can take the following arguments : $field=>the compiled field, $instance : the active instance of the object, $index: the field position
	 *
	 * @param int $index
	 *        	postion of the compiled field
	 * @param callable $callback
	 *        	function called after the field compilation
	 * @return InstanceViewer
	 */
	public function afterCompile($index, $callback) {
		$this->afterCompile[$index] = $callback;
		return $this;
	}

	/**
	 * Defines a callback function to call for modifying captions
	 * function parameters are $captions: the captions to modify and $instance: the active model instance
	 *
	 * @param callable $captionCallback
	 * @return \Ajax\semantic\widgets\base\InstanceViewer
	 */
	public function setCaptionCallback($captionCallback) {
		$this->captionCallback = $captionCallback;
		return $this;
	}

	/**
	 * Defines the default function which displays fields value
	 *
	 * @param callable $defaultValueFunction
	 *        	function parameters are : $name : the field name, $value : the field value ,$index : the field index, $instance : the active instance of model
	 * @return \Ajax\semantic\widgets\base\InstanceViewer
	 */
	public function setDefaultValueFunction($defaultValueFunction) {
		$this->defaultValueFunction = $defaultValueFunction;
		return $this;
	}

	public function getVisibleProperties() {
		return $this->visibleProperties;
	}

	public function getSimpleProperties() {
		return array_filter($this->visibleProperties, function ($item) {
			return ! (is_array($item) || is_object($item));
		});
	}

	/**
	 *
	 * @return callable
	 */
	public function getDefaultValueFunction() {
		return $this->defaultValueFunction;
	}

	/**
	 *
	 * @return mixed
	 */
	public function getGroupByFields() {
		return $this->groupByFields;
	}

	/**
	 *
	 * @param mixed $groupByFields
	 */
	public function setGroupByFields($groupByFields) {
		$this->groupByFields = $groupByFields;
	}
	
	public function addGroupBy($groupByField){
		$this->groupByFields[]=$groupByField;
	}

	public function getGroupByFieldsCount() {
		if (is_array($this->groupByFields)) {
			return sizeof($this->groupByFields);
		}
		return 0;
	}
}
