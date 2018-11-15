<?php

namespace Ajax\common\html;


use Ajax\common\components\SimpleExtComponent;
use Ajax\JsUtils;
use Ajax\common\html\traits\BaseHtmlEventsTrait;
use Ajax\common\html\traits\BaseHtmlPropertiesTrait;
use Ajax\service\Javascript;

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
	protected $_runned=false;
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

	protected function getTemplate(JsUtils $js=NULL,$view=null) {
		return PropertyWrapper::wrap($this->_wrapBefore, $js,$view) . $this->_template . PropertyWrapper::wrap($this->_wrapAfter, $js,$view);
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
		$this->ctrl($name, $value, $typeCtrl);
		$name=$value;
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
		$this->ctrl($name, $value, $typeCtrl);
		if (\is_array($typeCtrl)) {
			$this->removeOldValues($name, $typeCtrl);
		}
		$name.=$separator . $value;
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
			$this->callCallback($this->_preCompile);
			unset($this->properties["jsCallback"]);
			$this->_compiled=true;
		}
	}

	public function compile(JsUtils $js=NULL, &$view=NULL) {
		$this->compile_once($js,$view);
		$result=$this->getTemplate($js,$view);
		foreach ( $this as $key => $value ) {
				if(\strstr($result, "%{$key}%")!==false){
					if (\is_array($value)) {
						$v=PropertyWrapper::wrap($value, $js,$view);
					}elseif($value instanceof \stdClass){
							$v=\print_r($value,true);
					}elseif ($value instanceof BaseHtml){
						$v=$value->compile($js,$view);
					}else{
						$v=$value;
					}
					$result=str_replace("%{$key}%", $v, $result);
				}
		}
		if (isset($js)===true) {
			$this->run($js);
			if (isset($view) === true) {
				$js->addViewElement($this->getLibraryId(), $result, $view);
			}
		}

		if(\is_callable($this->_postCompile)){
			$pc=$this->_postCompile;
			$pc($this);
		}
		return $result;
	}
	
	/**
	 * Sets the element draggable, and eventualy defines the dropzone (HTML5 drag and drop)
	 * @param string $attr default: "id"
	 * @param BaseHtml $dropZone the dropzone element
	 * @param array $parameters default: ["jsCallback"=>"","jqueryDone"=>"append"]
	 * @return \Ajax\common\html\BaseHtml
	 */
	public function setDraggable($attr="id",$dropZone=null,$parameters=[]){
		$this->setProperty("draggable", "true");
		$this->addEvent("dragstart",Javascript::draggable($attr));
		if(isset($dropZone)&& $dropZone instanceof BaseHtml){
			$jqueryDone="append";$jsCallback="";
			extract($parameters);
			$dropZone->asDropZone($jsCallback,$jqueryDone,$parameters);
		}
		return $this;
	}
	
	/**
	 * Declares the element as a drop zone (HTML5 drag and drop)
	 * @param string $jsCallback
	 * @param string $jqueryDone
	 * @param array $parameters
	 * @return \Ajax\common\html\BaseHtml
	 */
	public function asDropZone($jsCallback="",$jqueryDone="append",$parameters=[]){
		$stopPropagation=false;
		$this->addEvent("dragover", '', $stopPropagation,true);
		extract($parameters);
		$this->addEvent("drop",Javascript::dropZone($jqueryDone,$jsCallback),$stopPropagation,true);
		return $this;
	}

	public function __toString() {
		return $this->compile();
	}

	public function onPostCompile($callback){
		$this->_postCompile=$callback;
	}

	public function onPreCompile($callback){
		$this->_preCompile=$this->addCallback($this->_preCompile, $callback);
	}
	
	private function addCallback($originalValue,$callback){
		if(isset($originalValue)){
			if(!is_array($originalValue)){
				$result=[$originalValue];
			}
			$result[]=$callback;
			return $result;
		}
		return $callback;
	}
	
	private function callCallback($callable){
		if(\is_callable($callable)){
			return $callable($this);
		}
		if(is_array($callable)){
			foreach ($callable as $call){
				$this->callCallback($call);
			}
		}
	}
}
