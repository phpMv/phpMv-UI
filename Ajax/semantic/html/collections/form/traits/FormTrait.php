<?php
namespace Ajax\semantic\html\collections\form\traits;

use Ajax\semantic\html\collections\form\HtmlForm;
use Ajax\semantic\html\collections\HtmlMessage;
use Ajax\service\AjaxCall;

trait FormTrait{
	/**
	 * @return HtmlForm
	 */
	abstract protected function getForm();

	public function setLoading() {
		return $this->getForm()->addToProperty("class", "loading");
	}

	public function addErrorMessage(){
		return $this->getForm()->addContent((new HtmlMessage(""))->setError());
	}

	public function jsState($state) {
		return $this->getForm()->jsDoJquery("addClass", $state);
	}

	/**
	 * @param string $event
	 * @param string $identifier
	 * @param string $url
	 * @param string $responseElement
	 * @return \Ajax\semantic\html\collections\form\HtmlForm
	 */
	public function submitOn($event,$identifier,$url,$responseElement){
		$form=$this->getForm();
		$elem=$form->getElementById($identifier, $form->getContent());
		if(isset($elem)){
			$this->_buttonAsSubmit($elem, $event,$url,$responseElement);
		}
		return $form;
	}

	public function submitOnClick($identifier,$url,$responseElement){
		return $this->submitOn("click", $identifier, $url, $responseElement);
	}

	public function addSubmit($identifier,$value,$cssStyle=NULL,$url=NULL,$responseElement=NULL){
		$bt=$this->getForm()->addButton($identifier, $value,$cssStyle);
		return $this->_buttonAsSubmit($bt, "click",$url,$responseElement);
	}

	protected function _buttonAsSubmit(&$button,$event,$url,$responseElement=NULL){
		$form=$this->getForm();
		if(isset($url) && isset($responseElement)){
			$button->addEvent($event, "$('#".$form->getIdentifier()."').form('validate form');");
			$form->addValidationParam("_ajaxSubmit", new AjaxCall("postForm", ["form"=>$form->getIdentifier(),"responseElement"=>$responseElement,"url"=>$url]));
		}
		return $button;
	}

	public function addReset($identifier,$value,$cssStyle=NULL){
		$bt=$this->getForm()->addButton($identifier, $value,$cssStyle);
		$bt->setProperty("type", "reset");
		return $bt;
	}

	/**
	 * Callback on each valid field
	 * @param string $jsCode
	 * @return \Ajax\semantic\html\collections\form\HtmlForm
	 */
	public function onValid($jsCode){
		$form=$this->getForm();
		$form->addValidationParam("onValid", "%function(){".$jsCode."}%");
		return $form;
	}

	/**
	 * Callback if a form is all valid
	 * @param string $jsCode can use event and fields parameters
	 * @return HtmlForm
	 */
	public function onSuccess($jsCode){
		$form=$this->getForm();
		$form->addValidationParam("onSuccess", "%function(evt,fields){".$jsCode."}%");
		return $form;
	}
}