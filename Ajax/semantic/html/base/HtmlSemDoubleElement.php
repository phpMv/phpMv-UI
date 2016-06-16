<?php

namespace Ajax\semantic\html\base;

use Ajax\common\html\HtmlDoubleElement;
use Ajax\semantic\html\content\InternalPopup;

use Ajax\semantic\html\base\traits\BaseTrait;
use Ajax\semantic\html\modules\HtmlDimmer;
use Ajax\semantic\html\elements\HtmlLabel;
use Ajax\semantic\html\base\constants\Direction;
use Ajax\JsUtils;

/**
 * Base class for Semantic double elements
 * @author jc
 * @version 1.001
 */
class HtmlSemDoubleElement extends HtmlDoubleElement {
	use BaseTrait;
	protected $_popup=NULL;
	protected $_dimmer=NULL;

	public function __construct($identifier, $tagName="p", $baseClass="ui", $content=NULL) {
		parent::__construct($identifier, $tagName);
		$this->_baseClass=$baseClass;
		$this->setClass($baseClass);
		if (isset($content)) {
			$this->content=$content;
		}
	}

	public function setPopupAttributes($variation=NULL, $popupEvent=NULL) {
		if (isset($this->_popup))
			$this->_popup->setAttributes($variation, $popupEvent);
	}

	public function addPopup($title="", $content="", $variation=NULL, $params=array()) {
		$this->_popup=new InternalPopup($this, $title, $content, $variation, $params);
		return $this;
	}

	public function addPopupHtml($html="", $variation=NULL, $params=array()) {
		$this->_popup=new InternalPopup($this);
		$this->_popup->setHtml($html);
		$this->_popup->setAttributes($variation, $params);
		return $this;
	}

	public function addDimmer($params=array(), $content=NULL) {
		$dimmer=new HtmlDimmer("dimmer-" . $this->identifier, $content);
		$dimmer->setParams($params);
		$dimmer->setContainer($this);
		$this->addContent($dimmer);
		return $dimmer;
	}

	public function addLabel($label, $before=false, $icon=NULL) {
		$labelO=$label;
		if (\is_object($label) === false) {
			$labelO=new HtmlLabel("label-" . $this->identifier, $label);
			if (isset($icon))
				$labelO->addIcon($icon);
		} else {
			$labelO->addToPropertyCtrl("class", "label", array ("label" ));
		}
		$this->addContent($labelO, $before);
		return $labelO;
	}

	public function attachLabel($label,$side,$direction=Direction::NONE,$icon=NULL){
		$label=$this->addLabel($label,true,$icon);
		$label->setAttached($side,$direction);
		return $this;
	}

	/**
	 *
	 * @return \Ajax\semantic\html\base\HtmlSemDoubleElement
	 */
	public function asLink($href=NULL) {
		if (isset($href))
			$this->setProperty("href", $href);
		return $this->setTagName("a");
	}

	public function jsShowDimmer($show=true) {
		$status="hide";
		if ($show === true)
			$status="show";
		return '$("#.' . $this->identifier . ').dimmer("' . $status . '");';
	}

	public function compile(JsUtils $js=NULL, &$view=NULL) {
		if (isset($this->_popup))
			$this->_popup->compile();
		return parent::compile($js, $view);
	}

	public function run(JsUtils $js) {
		$this->_bsComponent=$js->semantic()->generic("#" . $this->identifier);
		parent::run($js);
		$this->addEventsOnRun($js);
		if (isset($this->_popup)) {
			$this->_popup->run($js);
		}
		return $this->_bsComponent;
	}
	/*
	 * public function __call($name, $arguments){
	 * $type=\substr($name, 0,3);
	 * $name=\strtolower(\substr($name, 3));
	 * $names=\array_merge($this->_variations,$this->_states);
	 * $argument=@$arguments[0];
	 * if(\array_search($name, $names)!==FALSE){
	 * switch ($type){
	 * case "set":
	 * if($argument===false){
	 * $this->removePropertyValue("class", $name);
	 * }else {
	 * $this->setProperty("class", $this->_baseClass." ".$name);
	 * }
	 * break;
	 * case "add":
	 * $this->addToPropertyCtrl("class", $name,array($name));
	 * break;
	 * default:
	 * throw new \Exception("Méthode ".$type.$name." inexistante.");
	 * }
	 * }else{
	 * throw new \Exception("Propriété ".$name." inexistante.");
	 * }
	 * return $this;
	 * }
	 */
}