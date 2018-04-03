<?php
namespace Ajax\bootstrap\html;

use Ajax\bootstrap\html\base\HtmlBsDoubleElement;
use Ajax\JsUtils;

use Ajax\bootstrap\html\base\HtmlNavElement;
use Ajax\common\html\HtmlDoubleElement;
/**
 * Twitter Bootstrap Breadcrumbs component
 * @see http://getbootstrap.com/components/#breadcrumbs
 * @author jc
 * @version 1.001
 * @method _hrefFunction($e)
 */
class HtmlBreadcrumbs extends HtmlNavElement {

	/**
	 * @var integer the start index for href generation
	 */
	protected $startIndex=0;
	/**
	 * @var boolean $autoActive sets the last element's class to <b>active</b> if true
	 */
	protected $autoActive;

	/**
	 * @var boolean if set to true, the path of the elements is absolute
	 */
	protected $absolutePaths;

	/**
	 * @var callable the function who generates the href elements. default : function($e){return $e->getContent()}
	 */
	protected $_hrefFunction;


	/**
	 * @param string $identifier
	 * @param array $elements
	 * @param boolean $autoActive sets the last element's class to <b>active</b> if true
	 * @param callable $hrefFunction the function who generates the href elements. default : function($e){return $e->getContent()}
	 */
	public function __construct($identifier,$elements=array(),$autoActive=true,$startIndex=0,$hrefFunction=NULL){
		parent::__construct($identifier,"ol");
		$this->startIndex=$startIndex;
		$this->setProperty("class", "breadcrumb");
		$this->content=array();
		$this->autoActive=$autoActive;
		$this->absolutePaths;
		$this->_hrefFunction=function (HtmlDoubleElement $e){return $e->getContent();};
		if(isset($hrefFunction)){
			$this->_hrefFunction=$hrefFunction;
		}
		$this->addElements($elements);
	}

	/**
	 * @param mixed $element
	 * @param string $href
	 * @return \Ajax\bootstrap\html\HtmlLink
	 */
	public function addElement($element,$href="",$glyph=NULL){
		$size=sizeof($this->content);
		if(\is_array($element)){
			$elm=new HtmlLink("lnk-".$this->identifier."-".$size);
			$elm->fromArray($element);
		}else if($element instanceof HtmlLink){
			$elm=$element;
		}else{
			$elm=new HtmlLink("lnk-".$this->identifier."-".$size,$href,$element);
			if(isset($glyph)){
				$elm->wrapContentWithGlyph($glyph);
			}
		}
		$elm->wrap("<li>","</li>");
		$this->content[]=$elm;
		$elm->setProperty($this->attr, $this->getHref($size));
		return $elm;
	}

	public function setActive($index=null){
		if(!isset($index)){
			$index=sizeof($this->content)-1;
		}
		$li=new HtmlBsDoubleElement("","li");
		$li->setClass("active");
		$li->setContent($this->content[$index]->getContent());
		$this->content[$index]=$li;
	}

	public function addElements($elements){
		foreach ( $elements as $element ) {
			$this->addElement($element);
		}
		return $this;
	}

	public function fromArray($array){
		$array=parent::fromArray($array);
		$this->addElements($array);
		return $array;
	}

	/**
	 * Return the url of the element at $index or the breadcrumbs url if $index is ommited
	 * @param int $index
	 * @param string $separator
	 * @return string
	 */
	public function getHref($index=null,$separator="/"){
		if(!isset($index)){
			$index=sizeof($this->content);
		}
		if($this->absolutePaths===true){
			return $this->_hrefFunction($this->content[$index]);
		}else{
			return $this->root.implode($separator, array_slice(array_map(function($e){return $this->_hrefFunction($e);}, $this->content),$this->startIndex,$index+1));
		}
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\BaseHtml::compile()
	 */
	public function compile(JsUtils $js=NULL, &$view=NULL) {
		if($this->autoActive){
			$this->setActive();
		}
		return parent::compile($js, $view);
	}

	/* (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\BaseHtml::fromDatabaseObject()
	 */
	public function fromDatabaseObject($object, $function) {
		return $this->addElement($function($object));
	}

		/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\BaseHtml::on()
	 */
	public function on($event, $jsCode, $stopPropagation=false, $preventDefault=false) {
		foreach ($this->content as $element){
			$element->on($event,$jsCode,$stopPropagation,$preventDefault);
		}
		return $this;
	}

	public function setAutoActive($autoActive) {
		$this->autoActive = $autoActive;
		return $this;
	}

	public function _ajaxOn($operation, $event, $url, $responseElement="", $parameters=array()) {
		foreach ($this->content as $element){
			$element->_ajaxOn($operation, $event, $url, $responseElement, $parameters);
		}
		return $this;
	}

	/**
	 * Associate an ajax get to the breadcrumbs elements, displayed in $targetSelector
	 * $attr member is used to build each element url
	 * @param string $targetSelector the target of the get
	 * @return HtmlBreadcrumbs
	 */
	public function autoGetOnClick($targetSelector){
		return $this->getOnClick($this->root, $targetSelector,array("attr"=>$this->attr));
	}

	public function contentAsString(){
		if($this->autoActive){
			$this->setActive();
		}
		return parent::contentAsString();
	}

	public function getElement($index){
		return $this->content[$index];
	}

	/**
	 * Add a glyphicon to the element at index $index
	 * @param mixed $glyph
	 * @param int $index
	 */
	public function addGlyph($glyph,$index=0){
		$elm=$this->getElement($index);
		return $elm->wrapContentWithGlyph($glyph);
	}

	/**
	 * Add new elements in breadcrumbs corresponding to request dispatcher : controllerName, actionName, parameters
	 * @param JsUtils $js
	 * @param object $dispatcher the request dispatcher
	 * @return \Ajax\bootstrap\html\HtmlBreadcrumbs
	 */
	public function fromDispatcher(JsUtils $js,$dispatcher,$startIndex=0){
		$this->startIndex=$startIndex;
		return $this->addElements($js->fromDispatcher($dispatcher));
	}


	/**
	 * sets the function who generates the href elements. default : function($element){return $element->getContent()}
	 * @param callable $_hrefFunction
	 * @return \Ajax\bootstrap\html\HtmlBreadcrumbs
	 */
	public function setHrefFunction($_hrefFunction) {
		$this->_hrefFunction = $_hrefFunction;
		return $this;
	}

	public function setStartIndex($startIndex) {
		$this->startIndex=$startIndex;
		return $this;
	}


}
