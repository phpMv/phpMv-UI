<?php
namespace Ajax\bootstrap\html\base;

use Ajax\common\html\traits\NavElementTrait;

/**
 * Bs class for navigation elements : Breadcrumbs and Pagination
 * @author jc
 * @version 1.001
 */
abstract class HtmlNavElement extends HtmlBsDoubleElement {
	use NavElementTrait;
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
	 * $this->attr member is used to build each element url
	 * @param string $targetSelector the target of the get
	 * @return HtmlNavElement
	 */
	public function autoGetOnClick($targetSelector){
		return $this->getOnClick($this->root, $targetSelector,array("attr"=>$this->attr));
	}

	public function contentAsString(){
		return implode("", $this->content);
	}
}
