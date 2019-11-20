<?php

namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemDoubleElement;


/**
 * Semantic Emoji component
 * Ajax\semantic\html\elements$HtmlIcon
 * This class is part of phpMv-ui
 * @author jcheron <myaddressmail@gmail.com>
 * @version 1.0.0
 *
 */
class HtmlEmoji extends HtmlSemDoubleElement {
	protected $_emoji;

	public function __construct($identifier, $emoji) {
		parent::__construct($identifier, "em", "", NULL);
		$this->setEmoji($emoji);
	}

	public function getEmoji() {
		return $this->_emoji;
	}

	/**
	 * sets the emoji
	 * @param string $emoji
	 * @return HtmlEmoji
	 */
	public function setEmoji($emoji) {
		$emoji=":{$emoji}:";
		if (isset($this->_emoji)) {
			$this->removePropertyValue("data-emoji", $this->_emoji);
		}
		$this->_emoji=$emoji;
		$this->addToProperty("data-emoji", $emoji);
		return $this;
	}

	/**
	 * Emoji used as a simple loader
	 * @return HtmlEmoji
	 */
	public function asLoader() {
		return $this->addToProperty("class", "loading");
	}

	/**
	 * icon formatted as a link
	 * @param string $href
	 * @param string $target
	 * @return HtmlEmoji
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

	public function addLabel($label, $before=false, $emoji=null) {
		if($before)
			$this->wrap($label);
		else
			$this->wrap("", $label);
		if($emoji!=null)
			$this->setEmoji($emoji);
		return $this;
	}

}
