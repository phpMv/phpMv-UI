<?php

namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\base\traits\LabeledIconTrait;
use Ajax\semantic\html\base\constants\Emphasis;
use Ajax\semantic\html\base\constants\Social;
use Ajax\semantic\html\elements\html5\HtmlLink;

/**
 * Semantic Button component
 * @see http://semantic-ui.com/elements/button.html
 * @author jc
 * @version 1.001
 */
class HtmlButton extends HtmlSemDoubleElement {
	use LabeledIconTrait;

	/**
	 * Constructs an HTML Semantic button
	 * @param string $identifier HTML id
	 * @param string $value value of the Button
	 * @param string $cssStyle btn-default, btn-primary...
	 * @param string $onClick JS Code for click event
	 */
	public function __construct($identifier, $value="", $cssStyle=null, $onClick=null) {
		parent::__construct($identifier, "button", "ui button");
		$this->content=$value;
		if (isset($cssStyle)) {
			$this->setStyle($cssStyle);
		}
		if (isset($onClick)) {
			$this->onClick($onClick);
		}
	}

	/**
	 * Set the button value
	 * @param string $value
	 * @return \Ajax\semantic\html\HtmlButton
	 */
	public function setValue($value) {
		$this->content=$value;
		return $this;
	}

	/**
	 * define the button style
	 * @param string|int $cssStyle
	 * @return \Ajax\semantic\html\HtmlButton default : ""
	 */
	public function setStyle($cssStyle) {
		return $this->addToProperty("class", $cssStyle);
	}

	public function setFocusable($value=true) {
		if ($value === true)
			$this->setProperty("tabindex", "0");
		else {
			$this->removeProperty("tabindex");
		}
		return $this;
	}

	public function setAnimated($content, $animation="") {
		$this->setTagName("div");
		$this->addToProperty("class", "animated " . $animation);
		$visible=new HtmlSemDoubleElement("visible-" . $this->identifier, "div");
		$visible->setClass("visible content");
		$visible->setContent($this->content);
		$hidden=new HtmlSemDoubleElement("hidden-" . $this->identifier, "div");
		$hidden->setClass("hidden content");
		$hidden->setContent($content);
		$this->content=array ($visible,$hidden );
		return $hidden;
	}

	/**
	 *
	 * @param string|HtmlIcon $icon
	 * @return \Ajax\semantic\html\elements\HtmlButton
	 */
	public function asIcon($icon) {
		$iconO=$icon;
		if (\is_string($icon)) {
			$iconO=new HtmlIcon("icon-" . $this->identifier, $icon);
		}
		$this->addToProperty("class", "icon");
		$this->content=$iconO;
		return $this;
	}

	public function asSubmit() {
		$this->setProperty("type", "submit");
		return $this->setTagName("button");
	}

	/**
	 * Add and return a button label
	 * @param string $caption
	 * @param string $before
	 * @param string $icon
	 * @return \Ajax\semantic\html\elements\HtmlLabel
	 */
	public function addLabel($label, $before=false, $icon=NULL) {
		$this->tagName="div";
		$this->addToProperty("class", "labeled");
		$this->content=new HtmlButton("button-" . $this->identifier, $this->content);
		$this->content->setTagName("div");
		$label=new HtmlLabel("label-" . $this->identifier, $label, "a");
		if(isset($icon))
			$label->addIcon($icon);
		$label->setBasic();
		$this->addContent($label, $before);
		return $label;
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\common\html\BaseHtml::fromArray()
	 */
	public function fromArray($array) {
		$array=parent::fromArray($array);
		foreach ( $array as $key => $value ) {
			$this->setProperty($key, $value);
		}
		return $array;
	}

	/**
	 * show it is currently the active user selection
	 * @return \Ajax\semantic\html\elements\HtmlButton
	 */
	public function setActive() {
		return $this->addToProperty("class", "active");
	}

	/**
	 * hint towards a positive consequence
	 * @return \Ajax\semantic\html\elements\HtmlButton
	 */
	public function setPositive() {
		return $this->addToProperty("class", "positive");
	}

	/**
	 * hint towards a negative consequence
	 * @return \Ajax\semantic\html\elements\HtmlButton
	 */
	public function setNegative() {
		return $this->addToProperty("class", "negative");
	}

	/**
	 * formatted to toggle on/off
	 * @return \Ajax\semantic\html\elements\HtmlButton
	 */
	public function setToggle() {
		return $this->addToProperty("class", "toggle");
	}

	/**
	 *
	 * @return \Ajax\semantic\html\elements\HtmlButton
	 */
	public function setCircular() {
		return $this->addToProperty("class", "circular");
	}

	/**
	 * button is less pronounced
	 * @return \Ajax\semantic\html\elements\HtmlButton
	 */
	public function setBasic() {
		return $this->addToProperty("class", "basic");
	}

	public function setEmphasis($value) {
		return $this->addToPropertyCtrl("class", $value, Emphasis::getConstants());
	}

	public function setLoading() {
		return $this->addToProperty("class", "loading");
	}

	public static function social($identifier, $social, $value=NULL) {
		if ($value === NULL)
			$value=\ucfirst($social);
		$return=new HtmlButton($identifier, $value);
		$return->addIcon($social);
		return $return->addToPropertyCtrl("class", $social, Social::getConstants());
	}

	public static function labeled($identifier, $value, $icon, $before=true) {
		$result=new HtmlButton($identifier, $value);
		$result->addIcon($icon, $before, true);
		return $result;
	}

	public static function icon($identifier, $icon) {
		$result=new HtmlButton($identifier);
		$result->asIcon($icon);
		return $result;
	}

	public function asLink($href=NULL) {
		$lnk=new HtmlLink("lnk-".$this->identifier,$href,$this->content);
		$this->content=$lnk;
		return $this;
	}
}