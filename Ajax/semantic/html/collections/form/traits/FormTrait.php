<?php
namespace Ajax\semantic\html\collections\form\traits;

use Ajax\semantic\html\collections\form\HtmlForm;
use Ajax\semantic\html\collections\HtmlMessage;
use Ajax\service\AjaxCall;
use Ajax\JsUtils;
use Ajax\common\html\BaseHtml;
use Ajax\semantic\components\Form;
use Ajax\semantic\html\collections\form\HtmlFormField;
use Ajax\semantic\components\validation\FieldValidation;

/**
 * trait used in Widget and HtmlForm
 * @author jc
 *
 */
trait FormTrait{

	/**
	 * @return HtmlForm
	 */
	abstract protected function getForm();
	

	protected function addCompoValidation(Form $compo,HtmlFormField $field){
		$validation=$field->getValidation();
		if(isset($validation)){
			$validation->setIdentifier($field->getDataField()->getIdentifier());
			$compo->addFieldValidation($validation);
		}
	}
	
	protected function addExtraCompoValidation(Form $compo,FieldValidation $validation){
		$compo->addFieldValidation($validation);
	}

	protected function _runValidationParams(Form &$compo,JsUtils $js=NULL){
		$form=$this->getForm();
		$params=$form->getValidationParams();
		if(isset($params["_ajaxSubmit"])){
			$compilation=$this->_compileAjaxSubmit($params["_ajaxSubmit"],$js);
			$this->onSuccess($compilation);
			$form->removeValidationParam("_ajaxSubmit");
		}
		$compo->addParams($form->getValidationParams());
		$form->setBsComponent($compo);
		$form->addEventsOnRun($js);
	}

	protected function _compileAjaxSubmit($ajaxSubmit,JsUtils $js=null){
		$compilation="";
		if(\is_array($ajaxSubmit)){
			foreach ($ajaxSubmit as $ajaxSubmitItem){
				$compilation.=$ajaxSubmitItem->compile($js);
			}
		}elseif($ajaxSubmit instanceof AjaxCall){
			$compilation=$ajaxSubmit->compile($js);
		}
		$compilation=str_ireplace("\"","%quote%", $compilation);
		return $compilation;
	}

	public function setLoading() {
		return $this->getForm()->addToProperty("class", "loading");
	}

	public function setState($state) {
		$this->getForm()->addToProperty("class", $state);
		return $this;
	}

	public function setAttached($value=true){
		$form=$this->getForm();
		if($value)
			$form->addToPropertyCtrl("class", "attached", array ("attached" ));
		return $form;
	}

	public function addErrorMessage(){
		return $this->getForm()->addContent((new HtmlMessage(""))->setError());
	}

	public function jsState($state) {
		return $this->getForm()->jsDoJquery("addClass", $state);
	}

	/**
	 * @param string $event
	 * @param string|BaseHtml $identifierOrElement
	 * @param string $url
	 * @param string $responseElement
	 * @param array $parameters
	 * @return HtmlForm
	 */
	public function submitOn($event,$identifierOrElement,$url,$responseElement,$parameters=NULL){
		$form=$this->getForm();
		if($identifierOrElement  instanceof BaseHtml)
			$elem=$identifierOrElement;
		else
			$elem=$form->getElementById($identifierOrElement, $form->getContent());
		if(isset($elem)){
			$this->_buttonAsSubmit($elem, $event,$url,$responseElement,$parameters);
		}
		return $form;
	}

	public function submitOnClick($identifier,$url,$responseElement,$parameters=NULL){
		return $this->submitOn("click", $identifier, $url, $responseElement,$parameters);
	}

	public function addSubmit($identifier,$value,$cssStyle=NULL,$url=NULL,$responseElement=NULL,$parameters=NULL){
		$bt=$this->getForm()->addButton($identifier, $value,$cssStyle);
		return $this->_buttonAsSubmit($bt, "click",$url,$responseElement,$parameters);
	}

	protected function _buttonAsSubmit(BaseHtml &$button,$event,$url,$responseElement=NULL,$parameters=NULL){
		$form=$this->getForm();
		if(isset($url) && isset($responseElement)){
			$button->addEvent($event, "$('#".$form->getIdentifier()."').form('validate form');",true,true);
			$this->setSubmitParams($url,$responseElement,$parameters);
		}
		return $button;
	}

	public function setSubmitParams($url,$responseElement=NULL,$parameters=NULL){
		$form=$this->getForm();
		$params=["form"=>$form->getIdentifier(),"responseElement"=>$responseElement,"url"=>$url,"stopPropagation"=>true];
		if(\is_array($parameters)){
			$params=\array_merge($params,$parameters);
		}
		$form->addValidationParam("_ajaxSubmit", new AjaxCall("postForm", $params));
		return $this;
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
		$form->addValidationParam("onSuccess", $jsCode,"%function(event,fields){","}%");
		return $form;
	}
	
	public function addExtraFieldRules($fieldname,$rules){
		$form=$this->getForm();
		$fv=$form->getExtraFieldValidation($fieldname);
		foreach ($rules as $rule){
			$fv->addRule($rule);
		}
	}
	
	public function addExtraFieldRule($fieldname,$type,$prompt=NULL,$value=NULL){
		$form=$this->getForm();
		$fv=$form->getExtraFieldValidation($fieldname);
		$fv->addRule($type,$prompt,$value);
	}
	
	public function setOptional($fieldname,$optional=true){
		$form=$this->getForm();
		$fv=$form->getExtraFieldValidation($fieldname);
		$fv->setOptional($optional);
	}
}
