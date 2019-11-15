<?php

namespace Ajax\semantic\html\base;

use Ajax\common\html\HtmlDoubleElement;
use Ajax\semantic\html\content\InternalPopup;

use Ajax\semantic\html\base\traits\BaseTrait;
use Ajax\semantic\html\modules\HtmlDimmer;
use Ajax\semantic\html\elements\HtmlLabel;
use Ajax\semantic\html\base\constants\Direction;
use Ajax\JsUtils;
use Ajax\semantic\html\base\constants\Side;
use Ajax\common\html\html5\HtmlList;
use Ajax\common\html\BaseHtml;
use Ajax\semantic\components\Toast;

/**
 * Base class for Semantic double elements
 * @author jc
 * @version 1.0.2
 */
class HtmlSemDoubleElement extends HtmlDoubleElement {
	use BaseTrait;
	protected $_popup=NULL;
	protected $_dimmer=NULL;
	protected $_toast=NULL;
	protected $_params=array ();


	public function __construct($identifier, $tagName="p", $baseClass="ui", $content=NULL) {
		parent::__construct($identifier, $tagName);
		$this->_baseClass=$baseClass;
		$this->setClass($baseClass);
		if (isset($content)) {
			$this->content=$content;
		}
	}

	/**
	 * Defines the popup attributes
	 * @param string $variation
	 * @param string $popupEvent
	 */
	public function setPopupAttributes($variation=NULL, $popupEvent=NULL) {
		if (isset($this->_popup))
			$this->_popup->setAttributes($variation, $popupEvent);
	}

	/**
	 * Adds a popup to the element
	 * @param string $title
	 * @param string $content
	 * @param string $variation
	 * @param array $params
	 * @return HtmlSemDoubleElement
	 */
	public function addPopup($title="", $content="", $variation=NULL, $params=array()) {
		$this->_popup=new InternalPopup($this, $title, $content, $variation, $params);
		return $this;
	}

	/**
	 * Adds an html popup to the element
	 * @param string $html
	 * @param string $variation
	 * @param array $params
	 * @return HtmlSemDoubleElement
	 */
	public function addPopupHtml($html="", $variation=NULL, $params=array()) {
		$this->_popup=new InternalPopup($this);
		$this->_popup->setHtml($html);
		$this->_popup->setAttributes($variation, $params);
		return $this;
	}

	/**
	 * Adds a Dimmer to the element
	 * @param array $params
	 * @param mixed $content
	 * @return HtmlDimmer
	 */
	public function addDimmer($params=array(), $content=NULL) {
		$dimmer=new HtmlDimmer("dimmer-" . $this->identifier, $content);
		$dimmer->setParams($params);
		$dimmer->setContainer($this);
		$this->addContent($dimmer);
		return $dimmer;
	}

	/**
	 * Adds a label to the element
	 * @param mixed $label
	 * @param boolean $before
	 * @param string $icon
	 * @return mixed|HtmlLabel
	 */
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

	/**
	 * Adds an attached label to the element
	 * @param mixed $label
	 * @param string $side
	 * @param string $direction
	 * @param string $icon
	 * @return HtmlSemDoubleElement
	 */
	public function attachLabel($label,$side=Side::TOP,$direction=Direction::NONE,$icon=NULL){
		$label=$this->addLabel($label,true,$icon);
		$label->setAttached($side,$direction);
		return $this;
	}

	/**
	 * Transforms the element into a link
	 * @return HtmlSemDoubleElement
	 */
	public function asLink($href=NULL,$target=NULL) {
		if (isset($href))
			$this->setProperty("href", $href);
		if(isset($target))
			$this->setProperty("target", $target);
		return $this->setTagName("a");
	}

	/**
	 * Returns the script displaying the dimmer
	 * @param boolean $show
	 * @return string
	 */
	public function jsShowDimmer($show=true) {
		$status="hide";
		if ($show === true)
			$status="show";
		return '$("#.' . $this->identifier . ').dimmer("' . $status . '");';
	}

	/**
	 * {@inheritDoc}
	 * @see BaseHtml::compile()
	 */
	public function compile(JsUtils $js=NULL, &$view=NULL) {
		if (isset($this->_popup)){
			$this->_popup->compile($js);
		}
		return parent::compile($js, $view);
	}

	/**
	 * {@inheritDoc}
	 * @see HtmlDoubleElement::run()
	 */
	public function run(JsUtils $js) {
		$this->_bsComponent=$js->semantic()->generic("#" . $this->identifier);
		parent::run($js);
		$this->addEventsOnRun($js);
		if (isset($this->_popup)) {
			$this->_popup->run($js);
		}
		if (isset($this->_toast)) {
			$this->_toast->setJs($js);
		}
		return $this->_bsComponent;
	}

	/**
	 * @param array $items
	 * @param boolean $ordered
	 * @return \Ajax\common\html\html5\HtmlList
	 */
	public function addList($items,$ordered=false){
		$list=new HtmlList("list-".$this->identifier,$items);
		$list->setOrdered($ordered);
		$list->setClass("ui list");
		$this->addContent($list);
		return $list;
	}
	
	/**
	 * @param ?array $params
	 * @return \Ajax\semantic\components\Toast
	 */
	public function asToast($params=NULL){
		$this->_toast=new Toast();
		$this->_toast->attach('#'.$this->_identifier);
		$this->setProperty('style','display:none;');
		if(isset($params)){
			$this->_toast->setParams($params);
		}
		$this->_toast->setParam('onShow','$(".toast-box *").show();');
		return $this->_toast;
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
