<?php
namespace Ajax\common\html\traits;
use Ajax\JsUtils;
use Ajax\bootstrap\html\base\HtmlNavElement;

/**
 * @author jc
 * @property string $identifier
 * @property string $root
 * @property string $attr
 */
trait NavElementTrait{

	abstract public function contentAsString();
	/**
	 * Generate the jquery script to set the elements to the HtmlNavElement
	 * @param JsUtils $jsUtils
	 */
	public function jsSetContent(JsUtils $jsUtils){
		$jsUtils->html("#".$this->identifier,str_replace("\"","'", $this->contentAsString()),true);
	}

	public function getRoot() {
		return $this->root;
	}
	public function setRoot($root) {
		$this->root = $root;
		return $this;
	}
	public function getAttr() {
		return $this->attr;
	}

	/**
	 * Define the html attribute for each element url in ajax
	 * @param string $attr html attribute
	 * @return HtmlNavElement
	 */
	public function setAttr($attr) {
		$this->attr = $attr;
		return $this;
	}

	public function __call($method, $args) {
		if(isset($this->$method) && is_callable($this->$method)) {
			return call_user_func_array(
					$this->$method,
					$args
					);
		}
	}

	abstract public function fromDispatcher(JsUtils $js,$dispatcher,$startIndex=0);
}
