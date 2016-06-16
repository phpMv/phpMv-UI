<?php
namespace Ajax\bootstrap\html\base;

use Ajax\JsUtils;
/**
 * Bs class for navigation elements : Breadcrumbs and Pagination
 * @author jc
 * @version 1.001
 */
abstract class HtmlNavElement extends HtmlBsDoubleElement {
	/**
	 * @var string the root site
	 */
	protected $root;

	/**
	 * @var String the html attribute which contains the elements url. default : data-ajax
	 */
	protected $attr;


	public function __construct($identifier,$tagName){
		parent::__construct($identifier,$tagName);
		$this->root="";
		$this->attr="data-ajax";
	}

	/**
	 * Associate an ajax get to the elements, displayed in $targetSelector
	 * $attr member is used to build each element url
	 * @param string $targetSelector the target of the get
	 * @param string $attr the html attribute used to build the elements url
	 * @return HtmlNavElement
	 */
	public function autoGetOnClick($targetSelector){
		return $this->getOnClick($this->root, $targetSelector,array("attr"=>$this->attr));
	}

	public function contentAsString(){
		return implode("", $this->content);
	}

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

	public abstract function fromDispatcher(JsUtils $js,$dispatcher,$startIndex=0);

}