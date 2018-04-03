<?php

namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemDoubleElement;

/**
 * Semantic Icon component
 * @see http://phpmv-ui.kobject.net/index/direct/main/44
 * @see http://semantic-ui.com/elements/icon.html
 * @author jc
 * @version 1.001
 */
class HtmlIcon extends HtmlSemDoubleElement {
	protected $_icon;

	public function __construct($identifier, $icon) {
		parent::__construct($identifier, "i", "icon", NULL);
		$this->setIcon($icon);
	}

	public function getIcon() {
		return $this->_icon;
	}

	/**
	 * sets the icon
	 * @param string $icon
	 * @return HtmlIcon
	 */
	public function setIcon($icon) {
		if (isset($this->_icon)) {
			$this->removePropertyValue("class", $this->_icon);
		}
		$this->_icon=$icon;
		$this->addToProperty("class", $icon);
		return $this;
	}

	/**
	 * adds an icon in icon element
	 * @param string $icon
	 * @return HtmlIcon
	 */
	public function addToIcon($icon) {
		$this->addToProperty("class", $icon);
		return $this->addToMember($this->_icon, $icon);
	}

	/**
	 * Icon used as a simple loader
	 * @return HtmlIcon
	 */
	public function asLoader() {
		return $this->addToProperty("class", "loading");
	}

	/**
	 * An icon can be fitted, without any space to the left or right of it.
	 * @return HtmlIcon
	 */
	public function setFitted() {
		return $this->addToProperty("class", "fitted");
	}

	/**
	 *
	 * @param string $sens horizontally or vertically
	 * @return HtmlIcon
	 */
	public function setFlipped($sens="horizontally") {
		return $this->addToProperty("class", "flipped " . $sens);
	}

	/**
	 *
	 * @param string $sens clockwise or counterclockwise
	 * @return HtmlIcon
	 */
	public function setRotated($sens="clockwise") {
		return $this->addToProperty("class", "rotated " . $sens);
	}

	/**
	 * icon formatted as a link
	 * @param string $href
	 * @param string $target
	 * @return HtmlIcon
	 */
	public function asLink($href=NULL,$target=NULL) {
		if (isset($href)) {
			$_target="";
			if(isset($target))
				$_target="target='{$target}'";
			$this->wrap("<a href='" . $href . "' {$_target}>", "</a>");
		}
		return $this->addToProperty("class", "link");
	}

	public function setOutline() {
		return $this->addToProperty("class", "outline");
	}

	/**
	 *
	 * @param string $inverted
	 * @return HtmlIcon
	 */
	public function setBordered($inverted=false) {
		$invertedStr="";
		if ($inverted !== false)
			$invertedStr=" inverted";
		return $this->addToProperty("class", "bordered" . $invertedStr);
	}

	/**
	 *
	 * @return HtmlIcon
	 */
	public function toCorner() {
		return $this->addToProperty("class", "corner");
	}

	public function addLabel($label, $before=false, $icon=NULL) {
		if($before)
			$this->wrap($label);
		else
			$this->wrap("", $label);
		if(isset($icon))
			$this->addToIcon($icon);
		return $this;
	}

	public static function label($identifier, $icon, $label) {
		$result=new HtmlIcon($identifier, $icon);
		return $result->addLabel($label);
	}
}
