<?php
namespace Ajax\semantic\html\base;

use Ajax\JsUtils;
use Ajax\service\JArray;
/**
 * Sem class for navigation elements : Breadcrumbs and Pagination
 * @author jc
 * @version 1.001
 */
abstract class HtmlSemNavElement extends HtmlSemCollection {
	/**
	 * @var string the root site
	 */
	protected $root;

	/**
	 * @var String the html attribute which contains the elements url. default : data-ajax
	 */
	protected $attr;

	protected $_contentSeparator="";


	public function __construct($identifier,$tagName,$baseClass){
		parent::__construct($identifier,$tagName,$baseClass);
		$this->root="";
		$this->attr="data-ajax";
	}

	/**
	 * Associate an ajax get to the elements, displayed in $targetSelector
	 * @param string $targetSelector the target of the get
	 * @return HtmlNavElement
	 */
	public function autoGetOnClick($targetSelector){
		return $this->getOnClick($this->root, $targetSelector,array("attr"=>$this->attr));
	}

	public function contentAsString(){
		return JArray::implode($this->_contentSeparator, $this->content);
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

	public abstract function fromDispatcher(JsUtils $js,$dispatcher, $startIndex=0);


	public function setContentDivider($divider,$index=NULL) {
		$divider="<div class='divider'> {$divider} </div>";
		return $this->setDivider($divider, $index);
	}

	public function setIconContentDivider($iconContentDivider,$index=NULL) {
		$contentDivider="<i class='".$iconContentDivider." icon divider'></i>";
		return $this->setDivider($contentDivider, $index);
	}

	protected function setDivider($divider,$index){
		if(isset($index)){
			if(\is_array($this->_contentSeparator)===false)
				$this->_contentSeparator=array_fill (0, $this->count()-1,$this->_contentSeparator);
			$this->_contentSeparator[$index]=$divider;
		}else{
			$this->_contentSeparator=$divider;
		}
		return $this;
	}

	protected function getContentDivider($index){
		if(\is_array($this->_contentSeparator)===true){
			return @$this->_contentSeparator[$index];
		}
		return $this->_contentSeparator;
	}


}