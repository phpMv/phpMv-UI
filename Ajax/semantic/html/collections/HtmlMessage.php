<?php

namespace Ajax\semantic\html\collections;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\common\html\html5\HtmlList;
use Ajax\semantic\html\elements\HtmlIcon;
use Ajax\JsUtils;
use Ajax\semantic\html\base\constants\Style;
use Ajax\common\html\HtmlDoubleElement;
/**
 * Semantic Message component
 * @see http://semantic-ui.com/collections/message.html
 * @author jc
 * @version 1.001
 */
class HtmlMessage extends HtmlSemDoubleElement {
	protected $icon;
	protected $close;
	public function __construct($identifier, $content="") {
		parent::__construct($identifier, "div");
		$this->_template="<%tagName% id='%identifier%' %properties%>%close%%icon%%wrapContentBefore%%content%%wrapContentAfter%</%tagName%>";
		$this->setClass("ui message");
		$this->setContent($content);
	}

	public function addHeader($header){
		$headerO=$header;
		if(\is_string($header)){
			$headerO=new HtmlSemDoubleElement("header-".$this->identifier,"div");
			$headerO->setClass("header");
			$headerO->setContent($header);
		}
		return $this->addContent($headerO,true);
	}

	public function addList($elements,$ordered=false){
		$list=new HtmlList("list-".$this->identifier,$elements);
		$list->setOrdered($ordered);
		$list->setClass("ui list");
		$this->addContent($list);
	}

	public function setIcon($icon){
		$this->addToProperty("class", "icon");
		$this->wrapContent("<div class='content'>","</div>");
		$this->icon=new HtmlIcon("icon-".$this->identifier, $icon);
		return $this;
	}

	public function addLoader($loaderIcon="notched circle"){
		$this->setIcon($loaderIcon);
		$this->icon->addToIcon("loading");
		return $this;
	}

	public function setDismissable($dismiss=true){
		if($dismiss===true)
			$this->close=new HtmlIcon("close-".$this->identifier, "close");
		else
			$this->close=NULL;
		return $this;
	}

	/**
	 * {@inheritDoc}
	 * @see \Ajax\semantic\html\base\HtmlSemDoubleElement::run()
	 */
	public function run(JsUtils $js){
		parent::run($js);
		if(isset($this->close)){
			$js->execOn("click", "#".$this->identifier." .close", "$(this).closest('.message').transition('fade')");
		}
	}

	public function setState($visible=true){
		$visible=($visible===true)?"visible":"hidden";
		return $this->addToPropertyCtrl("class", $visible, array("visible","hidden"));
	}

	public function setVariation($value="floating"){
		return $this->addToPropertyCtrl("class", $value, array("floating","compact"));
	}

	public function setStyle($style){
		return $this->addToPropertyCtrl("class", $style, Style::getConstants());
	}

	public function setAttached(HtmlDoubleElement $toElement=NULL){
		if(isset($toElement)){
			$toElement->addToProperty("class", "attached");
		}
		return $this->addToProperty("class", "attached");
	}
}