<?php

namespace Ajax\semantic\html\collections\form;

use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\semantic\html\elements\HtmlHeader;
use Ajax\semantic\html\collections\HtmlMessage;
use Ajax\semantic\html\base\constants\State;
use Ajax\semantic\html\collections\form\traits\FieldsTrait;
use Ajax\semantic\html\elements\HtmlDivider;
use Ajax\JsUtils;
use Ajax\service\AjaxCall;

/**
 * Semantic Form component
 * @see http://semantic-ui.com/collections/form.html
 * @author jc
 * @version 1.001
 */
class HtmlForm extends HtmlSemCollection {

	use FieldsTrait;
	/**
	 * @var array
	 */
	protected $_fields;

	/**
	 * @var array
	 */
	protected $_validationParams;

	public function __construct($identifier, $elements=array()) {
		parent::__construct($identifier, "form", "ui form");
		$this->_states=[ State::ERROR,State::SUCCESS,State::WARNING,State::DISABLED ];
		$this->setProperty("name", $this->identifier);
		$this->_fields=array ();
		$this->_validationParams=[];
		$this->addItems($elements);
	}

	/**
	 * @param string $title
	 * @param number $niveau
	 * @param string $dividing
	 * @return HtmlHeader
	 */
	public function addHeader($title, $niveau=1, $dividing=true) {
		$header=new HtmlHeader("", $niveau, $title);
		if ($dividing)
			$header->setDividing();
		return $this->addItem($header);
	}

	/**
	 * @param string $caption
	 * @return \Ajax\semantic\html\collections\form\HtmlForm
	 */
	public function addDivider($caption=NULL){
		return $this->addContent(new HtmlDivider("",$caption));
	}

	public function addFields($fields=NULL, $label=NULL) {
		if (isset($fields)) {
			if (!$fields instanceof HtmlFormFields) {
				if (\is_array($fields) === false) {
					$fields=\func_get_args();
					$end=\end($fields);
					if (\is_string($end)) {
						$label=$end;
						\array_pop($fields);
					} else
						$label=NULL;
				}
				$this->_fields=\array_merge($this->_fields, $fields);
				$fields=new HtmlFormFields("fields-" . $this->identifier . "-" . $this->count(), $fields);
			}
			if (isset($label))
				$fields=new HtmlFormField("", $fields, $label);
		} else {
			$fields=new HtmlFormFields("fields-" . $this->identifier . "-" . $this->count());
		}
		$this->addItem($fields);
		return $fields;
	}

	public function addItem($item) {
		$item=parent::addItem($item);
		if (\is_subclass_of($item, HtmlFormField::class) === true) {
			$this->_fields[]=$item;
		}
		return $item;
	}

	public function getField($index) {
		if (\is_string($index)) {
			$field=$this->getElementById($index, $this->_fields);
		} else {
			$field=$this->_fields[$index];
		}
		return $field;
	}

	/**
	 * automatically divide fields to be equal width
	 * @return \Ajax\semantic\html\collections\form\HtmlForm
	 */
	public function setEqualWidth() {
		return $this->addToProperty("class", "equal width");
	}

	/**
	 * Adds a field (alias for addItem)
	 * @param HtmlFormField $field
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addField($field) {
		return $this->addItem($field);
	}

	/**
	 *
	 * @param string $identifier
	 * @param string $content
	 * @param string $header
	 * @param string $icon
	 * @param string $type
	 * @return \Ajax\semantic\html\collections\HtmlMessage
	 */
	public function addMessage($identifier, $content, $header=NULL, $icon=NULL, $type=NULL) {
		$message=new HtmlMessage($identifier, $content);
		if (isset($header))
			$message->addHeader($header);
		if (isset($icon))
			$message->setIcon($icon);
		if (isset($type))
			$message->setStyle($type);
		return $this->addItem($message);
	}

	private function addCompoValidation($js,$compo,$field){
		$validation=$field->getValidation();
		if(isset($validation)){
			if(isset($compo)===false){
				$compo=$js->semantic()->form("#".$this->identifier);
			}
			$validation->setIdentifier($field->getDataField()->getIdentifier());
			$compo->addFieldValidation($validation);
		}
		return $compo;
	}

	public function run(JsUtils $js) {
		$compo=NULL;
		foreach ($this->_fields as $field){
			if($field instanceof HtmlFormField)
				$compo=$this->addCompoValidation($js, $compo, $field);
		}
		foreach ($this->content as $field){
			if($field instanceof HtmlFormFields){
				$items=$field->getItems();
				foreach ($items as $_field){
					if($_field instanceof HtmlFormField)
						$compo=$this->addCompoValidation($js, $compo, $_field);
				}
			}
		}
		if(isset($compo)===false){
			return parent::run($js);
		}
		if(isset($this->_validationParams["_ajaxSubmit"])){
			if($this->_validationParams["_ajaxSubmit"] instanceof AjaxCall){
				$compilation=$this->_validationParams["_ajaxSubmit"]->compile($js);
				$compilation=str_ireplace("\"","%quote%", $compilation);
				$this->onSuccess($compilation);
				unset($this->_validationParams["_ajaxSubmit"]);
			}
		}
		$compo->addParams($this->_validationParams);
		$this->_bsComponent=$compo;
		$this->addEventsOnRun($js);
		return $this->_bsComponent;
	}

	public function setLoading() {
		return $this->addToProperty("class", "loading");
	}

	public function addErrorMessage(){
		return $this->addContent((new HtmlMessage(""))->setError());
	}

	public function jsState($state) {
		return $this->jsDoJquery("addClass", $state);
	}

	public function setValidationParams(array $_validationParams) {
		$this->_validationParams=$_validationParams;
		return $this;
	}

	public function submitOn($event,$identifier,$url,$responseElement){
		$elem=$this->getElementById($identifier, $this->content);
		if(isset($elem)){
			$elem->addEvent($event, "$('#".$this->identifier."').form('validate form');");
			$this->_validationParams["_ajaxSubmit"]=new AjaxCall("postForm", ["form"=>$this->identifier,"responseElement"=>$responseElement,"url"=>$url]);
		}
		return $this;
	}

	public function submitOnClick($identifier,$url,$responseElement){
		return $this->submitOn("click", $identifier, $url, $responseElement);
	}

	public function addSubmit($identifier,$value,$cssStyle=NULL,$url=NULL,$responseElement=NULL){
		$bt=$this->addButton($identifier, $value,$cssStyle);
		if(isset($url) && isset($responseElement))
			$this->submitOnClick($identifier, $url, $responseElement);
		return $bt;
	}

	public function addReset($identifier,$value,$cssStyle=NULL){
		$bt=$this->addButton($identifier, $value,$cssStyle);
		$bt->setProperty("type", "reset");
		return $bt;
	}

	/**
	 * Callback on each valid field
	 * @param string $jsCode
	 * @return \Ajax\semantic\html\collections\form\HtmlForm
	 */
	public function onValid($jsCode){
		$this->_validationParams["onValid"]="%function(){".$jsCode."}%";
		return $this;
	}

	/**
	 * Callback if a form is all valid
	 * @param string $jsCode can use event and fields parameters
	 * @return HtmlForm
	 */
	public function onSuccess($jsCode){
		$this->_validationParams["onSuccess"]="%function(evt,fields){".$jsCode."}%";
		return $this;
	}

}