<?php

namespace Ajax\semantic\html\collections\form;

use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\semantic\html\elements\HtmlHeader;
use Ajax\semantic\html\collections\HtmlMessage;
use Ajax\semantic\html\base\constants\State;
use Ajax\semantic\html\collections\form\traits\FieldsTrait;
use Ajax\semantic\html\elements\HtmlDivider;
use Ajax\JsUtils;
use Ajax\semantic\html\collections\form\traits\FormTrait;
use Ajax\semantic\components\Form;
use Ajax\common\html\BaseHtml;
use Ajax\common\html\HtmlDoubleElement;
use Ajax\semantic\components\validation\FieldValidation;

/**
 * Semantic Form component
 * @see http://semantic-ui.com/collections/form.html
 * @author jc
 * @version 1.001
 */
class HtmlForm extends HtmlSemCollection {

	use FieldsTrait,FormTrait;
	/**
	 * @var array
	 */
	protected $_fields;

	/**
	 * @var array
	 */
	protected $_validationParams;
	
	protected $_extraFieldRules;

	public function __construct($identifier, $elements=array()) {
		parent::__construct($identifier, "form", "ui form");
		$this->_states=[ State::ERROR,State::SUCCESS,State::WARNING,State::DISABLED ];
		$this->setProperty("name", $this->identifier);
		$this->_fields=array ();
		$this->addItems($elements);
		$this->_validationParams=[];
		$this->_extraFieldRules=[];
	}

	protected function getForm(){
		return $this;
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
	 * Adds a divider
	 * @param string $caption
	 * @return HtmlForm
	 */
	public function addDivider($caption=NULL){
		return $this->addContent(new HtmlDivider("",$caption));
	}

	/**
	 * Adds a group of fields
	 * @param array $fields
	 * @param string $label
	 * @return HtmlFormFields
	 */
	public function addFields($fields=NULL, $label=NULL) {
		if (isset($fields)) {
			if (!$fields instanceof HtmlFormFields) {
				if (!\is_array($fields)) {
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
			if (isset($label)){
				$fields->wrap("<div class='field'><label>{$label}</label>","</div>");
			}
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

	/**
	 * @param int $index
	 * @return mixed|NULL|BaseHtml
	 */
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
	 * @return HtmlForm
	 */
	public function setEqualWidth() {
		return $this->addToProperty("class", "equal width");
	}

	/**
	 * Adds a field (alias for addItem)
	 * @param HtmlFormField $field
	 * @return HtmlDoubleElement
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
	 * @return HtmlMessage
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



	public function compile(JsUtils $js=NULL,&$view=NULL){
		if(\sizeof($this->_validationParams)>0)
			$this->setProperty("novalidate", "");
		return parent::compile($js,$view);
	}

	public function run(JsUtils $js) {
		if(isset($js)){
			$compo=$js->semantic()->form("#".$this->identifier);
		}else{
			$compo=new Form();
			$compo->attach("#".$this->identifier);
		}
		foreach ($this->_fields as $field){
			if($field instanceof HtmlFormField){
				$this->addCompoValidation($compo, $field);
			}
		}
		foreach ($this->content as $field){
			if($field instanceof HtmlFormFields){
				$items=$field->getItems();
				foreach ($items as $_field){
					if($_field instanceof HtmlFormField)
						$this->addCompoValidation($compo, $_field);
				}
			}
		}
		foreach ($this->_extraFieldRules as $field=>$fieldValidation){
			$this->addExtraCompoValidation($compo, $fieldValidation);
		}
		$this->_runValidationParams($compo,$js);
		return $this->_bsComponent;
	}
	
	public function getExtraFieldValidation($fieldname){
		if(!isset($this->_extraFieldRules[$fieldname])){
			$this->_extraFieldRules[$fieldname]=new FieldValidation($fieldname);
		}
		return $this->_extraFieldRules[$fieldname];
	}

	public function addValidationParam($paramName,$paramValue,$before="",$after=""){
		$this->addBehavior($this->_validationParams, $paramName, $paramValue,$before,$after);
		return $this;
	}

	public function setValidationParams(array $_validationParams) {
		$this->_validationParams=$_validationParams;
		return $this;
	}
	
	public function hasValidationParams(){
		return sizeof($this->_validationParams)>0;
	}

	public function getValidationParams() {
		return $this->_validationParams;
	}

	public function removeValidationParam($param){
		unset($this->_validationParams[$param]);
		return $this;
	}

}
