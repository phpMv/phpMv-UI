<?php
namespace Ajax\semantic\html\base;

use Ajax\service\JArray;
use Ajax\common\html\traits\NavElementTrait;
use Ajax\bootstrap\html\base\HtmlNavElement;
/**
 * Sem class for navigation elements : Breadcrumbs and Pagination
 * @author jc
 * @version 1.001
 */
abstract class HtmlSemNavElement extends HtmlSemCollection {
	use NavElementTrait;
	/**
	 * @var string the root site
	 */
	protected $root;

	/**
	 * @var String the html attribute which contains the elements url. default : data-ajax
	 */
	protected $attr;

	/**
	 * @var string|array
	 */
	protected $_contentSeparator="";


	public function __construct($identifier,$tagName,$baseClass){
		parent::__construct($identifier,$tagName,$baseClass);
		$this->root="";
		$this->attr="data-ajax";
	}

	/**
	 * Associate an ajax get to the elements, displayed in $targetSelector
	 * @param string $targetSelector the target of the get
	 * @return HtmlSemNavElement
	 */
	public function autoGetOnClick($targetSelector){
		return $this->getOnClick($this->root, $targetSelector,array("attr"=>$this->attr));
	}

	public function contentAsString(){
		return JArray::implode($this->_contentSeparator, $this->content);
	}

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
			if(!\is_array($this->_contentSeparator))
				$this->_contentSeparator=array_fill (0, $this->count()-1,$this->_contentSeparator);
			$this->_contentSeparator[$index]=$divider;
		}else{
			$this->_contentSeparator=$divider;
		}
		return $this;
	}

	protected function getContentDivider($index){
		if(\is_array($this->_contentSeparator)){
			return @$this->_contentSeparator[$index];
		}
		return $this->_contentSeparator;
	}


}
