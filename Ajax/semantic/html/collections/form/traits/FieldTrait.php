<?php

namespace Ajax\semantic\html\collections\form\traits;

use Ajax\semantic\html\modules\HtmlDropdown;
use Ajax\semantic\html\elements\HtmlButton;
use Ajax\semantic\html\base\constants\Direction;
use Ajax\semantic\html\base\constants\State;
use Ajax\semantic\html\modules\checkbox\HtmlCheckbox;
use Ajax\common\html\BaseHtml;
use Ajax\semantic\html\elements\HtmlLabel;
use Ajax\common\html\html5\HtmlInput as HtmlInput5;


/**
 * @author jc
 * @property boolean $_hasIcon
 * @property string $identifier
 */
trait FieldTrait {

	abstract public function addToProperty($name, $value, $separator=" ");
	abstract public function addLabel($caption, $style="label-default", $leftSeparator="&nbsp;");
	abstract public function addContent($content,$before=false);
	abstract public function getField();
	abstract public function getDataField();

	public function setFocus() {
		$this->getField()->addToProperty("class", State::FOCUS);
	}

	public function addLoading() {
		if ($this->_hasIcon === false) {
			throw new \Exception("Input must have an icon for showing a loader, use addIcon before");
		}
		return $this->addToProperty("class", State::LOADING);
	}

	/**
	 * @param string|BaseHtml $label
	 * @param string $direction
	 * @param string $icon
	 * @return HtmlLabel
	 */
	public function labeled($label, $direction=Direction::LEFT, $icon=NULL) {
		$field=$this->getField();
		$labelO=$field->addLabel($label,$direction===Direction::LEFT,$icon);
		$field->addToProperty("class", $direction . " labeled");
		return $labelO;
	}

	/**
	 * @param string $direction
	 * @param string $caption
	 * @param string $value
	 * @param string $checkboxType
	 * @return HtmlLabel
	 */
	public function labeledCheckbox($direction=Direction::LEFT,$caption="",$value=NULL,$checkboxType=NULL){
		return $this->labeled(new HtmlCheckbox("lbl-ck-".$this->getField()->getIdentifier(),$caption,$value,$checkboxType),$direction);
	}

	/**
	 * @param string $icon
	 * @param string $direction
	 * @return HtmlLabel
	 */
	public function labeledToCorner($icon, $direction=Direction::LEFT) {
		return $this->labeled("", $direction . " corner", $icon)->toCorner($direction);
	}

	/**
	 * @param string $action
	 * @param string $direction
	 * @param string $icon
	 * @param boolean $labeled
	 * @return mixed|HtmlButton
	 */
	public function addAction($action, $direction=Direction::RIGHT, $icon=NULL, $labeled=false) {
		$field=$this->getField();
		$actionO=$action;
		if (\is_object($action) === false) {
			$actionO=new HtmlButton("action-" . $this->identifier, $action);
			if (isset($icon))
				$actionO->addIcon($icon, true, $labeled);
		}
		$field->addToProperty("class", $direction . " action");
		$field->addContent($actionO, \strstr($direction, Direction::LEFT) !== false);
		return $actionO;
	}

	/**
	 * @param string $label
	 * @param array $items
	 * @param string $direction
	 * @return HtmlLabel
	 */
	public function addDropdown($label="", $items=array(),$direction=Direction::RIGHT){
		$labelO=new HtmlDropdown("dd-".$this->identifier,$label,$items);
		$labelO->asSelect("select-".$this->identifier,false,true);
		return $this->labeled($labelO,$direction);
	}

	public function setTransparent() {
		return $this->getField()->addToProperty("class", "transparent");
	}

	public function setReadonly(){
		$this->getDataField()->setProperty("readonly", "");
		return $this;
	}

	public function setName($name){
		$this->getDataField()->setProperty("name",$name);
		return $this;
	}

	public function setFluid(){
		$this->getField()->addToProperty("class","fluid");
		return $this;
	}

	public function setDisabled($disable=true) {
		$field=$this->getField();
		if($disable)
			$field->addToProperty("class", "disabled");
		return $this;
	}
	
	public function setJsContent($content){
		$id="";
		$field=$this->getDataField();
		if(isset($field)){
			$id=$field->getIdentifier();
		}
		if($id!==''){
			return '$("#'.$id.'").val('.$content.')';
		}
	}
	
	public function getJsContent(){
		return $this->setJsContent("");
	}
	
	public function asFile($caption='', $direction=Direction::RIGHT, $icon='cloud upload alternate', $labeled=false){
		$field=$this->getField();
		$field->getDataField()->setProperty('readonly', 'readonly');
		$file=new HtmlInput5($this->identifier.'-file','file');
		$file->setProperty('style','display: none!important;');
		$field->getField()->content['file']=$file;
		$this->addAction($caption,$direction,$icon,$labeled);
	}
}
