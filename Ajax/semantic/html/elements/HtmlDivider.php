<?php

namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemDoubleElement;

/**
 * Semantic UI divider component
 * @see http://phpmv-ui.kobject.net/index/direct/main/42
 * @see http://semantic-ui.com/elements/divider.html
 * @author jc
 * @version 1.001
 */
class HtmlDivider extends HtmlSemDoubleElement {

	public function __construct($identifier, $content="", $tagName="div") {
		parent::__construct($identifier, $tagName, "ui divider");
		$this->content=$content;
	}

	/**
	 * vertical divider
	 * @return HtmlDivider
	 */
	public function setVertical() {
		return $this->addToPropertyCtrl("class", "vertical", array ("vertical","horizontal" ));
	}

	/**
	 * horizontal divider
	 * @return HtmlDivider
	 */
	public function setHorizontal() {
		return $this->addToPropertyCtrl("class", "horizontal", array ("vertical","horizontal" ));
	}

	/**
	 * hide the divider
	 * @return HtmlDivider
	 */
	public function setHidden() {
		return $this->addToProperty("class", "hidden");
	}

	/**
	 * fitted, without any space above or below it
	 * @return HtmlDivider
	 */
	public function setFitted() {
		return $this->addToProperty("class", "fitted");
	}

	/**
	 *
	 * @return HtmlDivider
	 */
	public function asHeader() {
		return $this->addToProperty("class", "header");
	}

	/**
	 * @return HtmlDivider
	 */
	public function setIgnored(){
		return $this->addToProperty("class", "ignored");
	}
}
