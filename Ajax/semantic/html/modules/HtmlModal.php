<?php

namespace Ajax\semantic\html\modules;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\JsUtils;
use Ajax\service\JArray;
use Ajax\common\html\BaseHtml;
use Ajax\semantic\html\elements\HtmlButton;
use Ajax\semantic\html\elements\HtmlImage;
use Ajax\semantic\html\elements\HtmlIcon;

class HtmlModal extends HtmlSemDoubleElement {
	protected $_params=array();
	protected $_paramParts=array();

	public function __construct($identifier, $header="", $content="", $actions=array()) {
		parent::__construct($identifier, "div","ui modal");
		if(isset($header)){
			$this->setHeader($header);
		}
		if(isset($content)){
			$this->setContent($content);
		}
		if(isset($actions)){
			$this->setActions($actions);
		}
	}

	public function setHeader($value) {
		$this->content["header"]=new HtmlSemDoubleElement("header-" . $this->identifier, "a", "header", $value);
		return $this;
	}

	public function setContent($value) {
		$this->content["content"]=new HtmlSemDoubleElement("content-" . $this->identifier, "div", "content", $value);
		return $this;
	}

	public function setActions($actions) {
		$this->content["actions"]=new HtmlSemDoubleElement("content-" . $this->identifier, "div", "actions");
		if(\is_array($actions)){
			foreach ($actions as $action){
				$this->addAction($action);
			}
		}
		else{
			$this->addAction($actions);
		}
		return $this;
	}

	public function addAction($action){
		if(!$action instanceof BaseHtml){
			$class="";
			if(\array_search($action, ["Okay","Yes"])!==false){
				$class="approve";
			}
			if(\array_search($action, ["Cancel","No"])!==false){
				$class="cancel";
			}
			$action=new HtmlButton("action-".$this->identifier,$action);
			if($class!=="")
				$action->addToProperty("class", $class);
		}
		return $this->addElementInPart($action, "actions");
	}

	public function addContent($content,$before=false){
		$this->content["content"]->addContent($content,$before);
		return $this;
	}

	public function addImageContent($image,$description=NULL){
		$content=$this->content["content"];
		if(isset($description)){
			$description=new HtmlSemDoubleElement("description-".$this->identifier,"div","description",$description);
			$content->addContent($description,true);
		}
		if($image!==""){
			$img=new HtmlImage("image-".$this->identifier,$image,"","medium");
			$content->addContent($img,true);
			$content->addToProperty("class","image");
		}
		return $this;
	}

	public function addIconContent($icon,$description=NULL){
		$content=$this->content["content"];
		if(isset($description)){
			$description=new HtmlSemDoubleElement("description-".$this->identifier,"div","description",$description);
			$content->addContent($description,true);
		}
		if($icon!==""){
			$img=new HtmlIcon("image-".$this->identifier,$icon);
			$content->addContent($img,true);
			$content->addToProperty("class","image");
		}
		return $this;
	}

	private function addContentInPart($content,$uiClass,$part) {
		return $this->addElementInPart(new HtmlSemDoubleElement($part."-" . $this->identifier, "div", $uiClass, $content), $part);
	}

	private function addElementInPart($element,$part) {
		$this->content[$part]->addContent($element);
		return $element;
	}

	public function showDimmer($value){
		$value=$value?"show":"hide";
		$this->_paramParts[]=["'".$value." dimmer'"];
		return $this;
	}

	public function setInverted(){
		$this->_params["inverted"]=true;
		return $this;
	}

	public function setBasic(){
		return $this->addToProperty("class", "basic");
	}

	public function setActive(){
		return $this->addToProperty("class", "active");
	}

	public function setTransition($value){
		$this->_paramParts[]=["'setting'","'transition'","'".$value."'"];
	}

	/**
	 * render the content of an existing view : $controller/$action and set the response to the modal content
	 * @param JsUtils $js
	 * @param Controller $initialController
	 * @param string $viewName
	 * @param $params The parameters to pass to the view
	 */
	public function renderView(JsUtils $js,$initialController,$viewName, $params=array()) {
		return $this->setContent($js->renderContent($initialController, $viewName,$params));
	}

	/**
	 * render the content of $controller::$action and set the response to the modal content
	 * @param JsUtils $js
	 * @param string $title The panel title
	 * @param Controller $initialControllerInstance
	 * @param string $controllerName the controller name
	 * @param string $actionName the action name
	 */
	public function forward(JsUtils $js,$initialControllerInstance,$controllerName,$actionName,$params=NULL){
		return $this->setContent($js->forward($initialControllerInstance, $controllerName, $actionName,$params));
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Ajax\semantic\html\base\HtmlSemDoubleElement::compile()
	 */
	public function compile(JsUtils $js=NULL, &$view=NULL) {
		$this->content=JArray::sortAssociative($this->content, ["header","content","actions" ]);
		return parent::compile($js, $view);
	}
	/*
	 * (non-PHPdoc)
	 * @see BaseHtml::run()
	 */
	public function run(JsUtils $js) {
		if(isset($this->_bsComponent)===false)
			$this->_bsComponent=$js->semantic()->modal("#".$this->identifier,$this->_params,$this->_paramParts);
		$this->addEventsOnRun($js);
		return $this->_bsComponent;
	}
}