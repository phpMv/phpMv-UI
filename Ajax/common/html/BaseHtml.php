<?php

namespace Ajax\common\html;


use Ajax\common\components\SimpleExtComponent;
use Ajax\JsUtils;
use Ajax\common\html\traits\BaseHtmlEventsTrait;
use Ajax\common\html\traits\BaseHtmlPropertiesTrait;

/**
 * BaseHtml for HTML components
 * @author jc
 * @version 1.3
 */
abstract class BaseHtml extends BaseWidget {
	use BaseHtmlEventsTrait,BaseHtmlPropertiesTrait;
	protected $_template;
	protected $tagName;
	protected $_wrapBefore=array ();
	protected $_wrapAfter=array ();
	protected $_bsComponent;
	protected $_compiled=false;
	protected $_postCompile;
	protected $_preCompile;

	/**
	 *
	 * @param JsUtils $js
	 * @return SimpleExtComponent
	 */
	abstract public function run(JsUtils $js);

	private function _callSetter($setter,$key,$value,&$array){
		$result=false;
		if (method_exists($this, $setter) && substr($setter, 0, 1) !== "_") {
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

	protected function getTemplate(JsUtils $js=NULL) {
		return PropertyWrapper::wrap($this->_wrapBefore, $js) . $this->_template . PropertyWrapper::wrap($this->_wrapAfter, $js);
	}

	protected function ctrl($name, $value, $typeCtrl) {
		if (\is_array($typeCtrl)) {
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



	protected function setMemberCtrl(&$name, $value, $typeCtrl) {
		if ($this->ctrl($name, $value, $typeCtrl) === true) {
			return $name=$value;
		}
		return $this;
	}

	protected function addToMemberUnique(&$name, $value, $typeCtrl, $separator=" ") {
		if (\is_array($typeCtrl)) {
			$this->removeOldValues($name, $typeCtrl);
			$name.=$separator . $value;
		}
		return $this;
	}



	protected function addToMemberCtrl(&$name, $value, $typeCtrl, $separator=" ") {
		if ($this->ctrl($name, $value, $typeCtrl) === true) {
			if (\is_array($typeCtrl)) {
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



	protected function removeOldValues(&$oldValue, $allValues) {
		$oldValue=str_ireplace($allValues, "", $oldValue);
		$oldValue=trim($oldValue);
	}

	protected function _getElementBy($callback,$elements){
		if (\is_array($elements)) {
			$elements=\array_values($elements);
			$flag=false;
			$index=0;
			while ( !$flag && $index < sizeof($elements) ) {
				if ($elements[$index] instanceof BaseHtml)
					$flag=($callback($elements[$index]));
					$index++;
			}
			if ($flag === true)
				return $elements[$index - 1];
		} elseif ($elements instanceof BaseHtml) {
			if ($callback($elements))
				return $elements;
		}
		return null;
	}

	protected function setWrapBefore($wrapBefore) {
		$this->_wrapBefore=$wrapBefore;
		return $this;
	}

	protected function setWrapAfter($wrapAfter) {
		$this->_wrapAfter=$wrapAfter;
		return $this;
	}

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
		return $this->_getElementBy(function(BaseWidget $element) use ($identifier){return $element->getIdentifier()===$identifier;}, $elements);
	}

	public function getBsComponent() {
		return $this->_bsComponent;
	}

	public function setBsComponent($bsComponent) {
		$this->_bsComponent=$bsComponent;
		return $this;
	}

	protected function compile_once(JsUtils $js=NULL, &$view=NULL) {
		if(!$this->_compiled){
			if(isset($js)){
				$beforeCompile=$js->getParam("beforeCompileHtml");
				if(\is_callable($beforeCompile)){
					$beforeCompile($this,$js,$view);
				}
			}
			if(\is_callable($this->_preCompile)){
				$pc=$this->_preCompile;
				$pc($this);
			}
			unset($this->properties["jsCallback"]);
			$this->_compiled=true;
		}
	}

	public function compile(JsUtils $js=NULL, &$view=NULL) {
		$this->compile_once($js,$view);
		$result=$this->getTemplate($js);
		foreach ( $this as $key => $value ) {
				if(\strstr($result, "%{$key}%")!==false){
					if (\is_array($value)) {
						$v=PropertyWrapper::wrap($value, $js);
					}elseif($value instanceof \stdClass){
							$v=\print_r($value,true);
					}else{
						$v=$value;
					}
					$result=str_replace("%{$key}%", $v, $result);
				}
		}
		if (isset($js)===true) {
			$this->run($js);
			if (isset($view) === true) {
				$js->addViewElement($this->_identifier, $result, $view);
			}
		}

		if(\is_callable($this->_postCompile)){
			$pc=$this->_postCompile;
			$pc($this);
		}
		return $result;
	}

	public function __toString() {
		return $this->compile();
	}

	public function onPostCompile($callback){
		$this->_postCompile=$callback;
	}

	public function onPreCompile($callback){
		$this->_preCompile=$callback;
	}
}
