<?php
namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\base\traits\LabeledIconTrait;
use Ajax\semantic\html\base\constants\Emphasis;
use Ajax\semantic\html\base\constants\Social;
use Ajax\semantic\html\modules\HtmlDropdown;

/**
 * Semantic Button component
 *
 * @see http://phpmv-ui.kobject.net/index/direct/main/31
 * @see http://semantic-ui.com/elements/button.html
 * @author jc
 * @version 1.001
 */
class HtmlButton extends HtmlSemDoubleElement {
	use LabeledIconTrait;

	/**
	 * Constructs an HTML Semantic button
	 *
	 * @param string $identifier
	 *        	HTML id
	 * @param string $value
	 *        	value of the Button
	 * @param string $cssStyle
	 *        	btn-default, btn-primary...
	 * @param string $onClick
	 *        	JS Code for click event
	 */
	public function __construct($identifier, $value = null, $cssStyle = null, $onClick = null) {
		parent::__construct($identifier, "button", "ui button");
		$this->content = $value;
		if (isset($cssStyle)) {
			$this->setStyle($cssStyle);
		}
		if (isset($onClick)) {
			$this->onClick($onClick);
		}
	}

	/**
	 * Set the button value
	 *
	 * @param string $value
	 * @return HtmlButton
	 */
	public function setValue($value) {
		if (is_array($this->content)) {
			foreach ($this->content as $i => $content) {
				if (is_string($content)) {
					$this->content[$i] = $value;
					return $this;
				}
			}
		}
		$this->content = $value;
		return $this;
	}

	/**
	 * define the button style
	 *
	 * @param string|int $cssStyle
	 * @return HtmlButton default : ""
	 */
	public function setStyle($cssStyle) {
		return $this->addToProperty("class", $cssStyle);
	}

	public function setFocusable($value = true) {
		if ($value === true)
			$this->setProperty("tabindex", "0");
		else {
			$this->removeProperty("tabindex");
		}
		return $this;
	}

	public function setAnimated($content, $animation = "") {
		$this->setTagName("div");
		$this->addToProperty("class", "animated " . $animation);
		$visible = new HtmlSemDoubleElement("visible-" . $this->identifier, "div");
		$visible->setClass("visible content");
		$visible->setContent($this->content);
		$hidden = new HtmlSemDoubleElement("hidden-" . $this->identifier, "div");
		$hidden->setClass("hidden content");
		$hidden->setContent($content);
		$this->content = array(
			$visible,
			$hidden
		);
		return $hidden;
	}

	/**
	 *
	 * @param string|HtmlIcon $icon
	 * @return HtmlButton
	 */
	public function asIcon($icon) {
		$iconO = $icon;
		if (\is_string($icon)) {
			$iconO = new HtmlIcon("icon-" . $this->identifier, $icon);
		}
		$this->addToProperty("class", "icon");
		$this->content = $iconO;
		return $this;
	}

	public function asSubmit() {
		$this->setProperty("type", "submit");
		return $this->setTagName("button");
	}

	/**
	 * Add and return a button label
	 *
	 * @param string $label
	 * @param boolean $before
	 * @param string $icon
	 * @return HtmlLabel
	 */
	public function addLabel($label, $before = false, $icon = NULL) {
		$this->tagName = "div";
		$prefix = "";
		if ($before)
			$prefix = "left ";
		$this->addToProperty("class", $prefix . "labeled");
		$isIcon = (isset($this->content[0]) && $this->content[0] instanceof HtmlIcon);
		$this->content = new HtmlButton("button-" . $this->identifier, $this->content);
		if ($isIcon) {
			$this->content->addClass("icon");
		}
		$this->content->setTagName("div");
		$label = new HtmlLabel("label-" . $this->identifier, $label, $icon, "a");
		$label->setBasic();
		$this->addContent($label, $before);
		return $label;
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\common\html\BaseHtml::fromArray()
	 */
	public function fromArray($array) {
		$array = parent::fromArray($array);
		foreach ($array as $key => $value) {
			$this->setProperty($key, $value);
		}
		return $array;
	}

	/**
	 * hint towards a positive consequence
	 *
	 * @return HtmlButton
	 */
	public function setPositive() {
		return $this->addToProperty("class", "positive");
	}

	public function setColor($color) {
		if (\is_array($this->content)) {
			foreach ($this->content as $content) {
				if ($content instanceof HtmlButton)
					$content->setColor($color);
			}
		} else
			parent::setColor($color);
		return $this;
	}

	/**
	 * hint towards a negative consequence
	 *
	 * @return HtmlButton
	 */
	public function setNegative() {
		return $this->addToProperty("class", "negative");
	}

	/**
	 * formatted to toggle on/off
	 *
	 * @return HtmlButton
	 */
	public function setToggle($active = "") {
		$this->onCreate("$('#" . $this->identifier . "').state();");
		return $this->addToProperty("class", "toggle " . $active);
	}

	/**
	 *
	 * @return HtmlButton
	 */
	public function setCircular() {
		return $this->addToProperty("class", "circular");
	}

	/**
	 * button is less pronounced
	 *
	 * @return HtmlButton
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

	/**
	 * Returns a new social Button
	 *
	 * @param string $identifier
	 * @param string $social
	 * @param string $value
	 * @return HtmlButton
	 */
	public static function social($identifier, $social, $value = NULL) {
		if ($value === NULL)
			$value = \ucfirst($social);
		$return = new HtmlButton($identifier, $value);
		$return->addIcon($social);
		return $return->addToPropertyCtrl("class", $social, Social::getConstants());
	}

	/**
	 * Returns a new labeled Button
	 *
	 * @param string $identifier
	 * @param string $value
	 * @param string $icon
	 * @param boolean $before
	 * @return \Ajax\semantic\html\elements\HtmlButton
	 */
	public static function labeled($identifier, $value, $icon, $before = true) {
		$result = new HtmlButton($identifier, $value);
		$result->addIcon($icon, $before, true);
		return $result;
	}

	/**
	 * Returns a new icon Button
	 *
	 * @param string $identifier
	 * @param string $icon
	 * @return HtmlButton
	 */
	public static function icon($identifier, $icon) {
		$result = new HtmlButton($identifier);
		$result->asIcon($icon);
		return $result;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see HtmlSemDoubleElement::asLink()
	 */
	public function asLink($href = NULL, $target = NULL) {
		parent::asLink($href, $target);
		return $this;
	}

	/**
	 * Returns a button with a dropdown button
	 *
	 * @param string $identifier
	 * @param string $value
	 * @param array $items
	 * @param boolean $asCombo
	 * @param string $icon
	 * @return HtmlButtonGroups
	 */
	public static function dropdown($identifier, $value, $items = [], $asCombo = false, $icon = null) {
		$result = new HtmlButtonGroups($identifier, [
			$value
		]);
		$dd = $result->addDropdown($items, $asCombo);
		if (isset($icon) && $dd instanceof HtmlDropdown)
			$dd->setIcon($icon);
		return $result;
	}

	public function addPopupConfirmation($message, $buttons = ["Okay","Cancel"]) {
		$elm = new HtmlSemDoubleElement('popup-confirm-' . $this->_identifier);
		$elm->setContent([
			'message' => new HtmlSemDoubleElement('popup-confirm-message-' . $this->_identifier, 'p', '', $message)
		]);
		$this->addPopupHtml($elm, null, [
			'on' => 'click'
		]);
		return $elm;
	}
}
