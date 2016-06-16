<?php

namespace Ajax\semantic\traits;

use Ajax\semantic\html\elements\HtmlButtonGroups;
use Ajax\semantic\html\elements\HtmlButton;
use Ajax\semantic\html\elements\HtmlContainer;
use Ajax\semantic\html\elements\HtmlDivider;
use Ajax\semantic\html\elements\HtmlHeader;
use Ajax\semantic\html\elements\HtmlIcon;
use Ajax\semantic\html\elements\HtmlIconGroups;
use Ajax\semantic\html\elements\HtmlInput;
use Ajax\semantic\html\elements\HtmlLabel;
use Ajax\semantic\html\elements\HtmlList;
use Ajax\semantic\html\elements\HtmlSegment;
use Ajax\semantic\html\elements\HtmlSegmentGroups;
use Ajax\semantic\html\elements\HtmlReveal;
use Ajax\semantic\html\base\constants\RevealType;
use Ajax\semantic\html\base\constants\Direction;
use Ajax\semantic\html\elements\HtmlStep;
use Ajax\semantic\html\elements\HtmlFlag;
use Ajax\semantic\html\elements\HtmlImage;
use Ajax\semantic\html\base\constants\State;
use Ajax\semantic\html\elements\HtmlLabelGroups;

trait SemanticHtmlElementsTrait {

	public abstract function addHtmlComponent($htmlComponent);

	public function addState($state, $elements) {
		State::add($state, $elements);
	}

	/**
	 * Return a new Semantic Html Button
	 * @param string $identifier
	 * @param string $value
	 * @param string $cssStyle
	 * @param string $onClick
	 * @return HtmlButton
	 */
	public function htmlButton($identifier, $value="", $cssStyle=null, $onClick=null) {
		return $this->addHtmlComponent(new HtmlButton($identifier, $value, $cssStyle, $onClick));
	}

	/**
	 *
	 * @param string $identifier
	 * @param array $elements
	 * @param boolean $asIcons
	 * @return HtmlButtonGroups
	 */
	public function htmlButtonGroups($identifier, $elements=array(), $asIcons=false) {
		return $this->addHtmlComponent(new HtmlButtonGroups($identifier, $elements, $asIcons));
	}

	/**
	 * Creates an html container
	 * @param string $identifier
	 * @param string $content
	 * @return HtmlContainer
	 */
	public function htmlContainer($identifier, $content="") {
		return $this->addHtmlComponent(new HtmlContainer($identifier, $content));
	}

	/**
	 *
	 * @param string $identifier
	 * @param string $content
	 * @return HtmlDivider
	 */
	public function htmlDivider($identifier, $content="", $tagName="div") {
		return $this->addHtmlComponent(new HtmlDivider($identifier, $content, $tagName));
	}

	/**
	 *
	 * @param string $identifier
	 * @param number $niveau
	 * @param mixed $content
	 * @param string $type
	 * @return HtmlHeader
	 */
	public function htmlHeader($identifier, $niveau=1, $content=NULL, $type="page") {
		return $this->addHtmlComponent(new HtmlHeader($identifier, $niveau, $content, $type));
	}

	/**
	 *
	 * @param string $identifier
	 * @param string $icon
	 * @return HtmlIcon
	 */
	public function htmlIcon($identifier, $icon) {
		return $this->addHtmlComponent(new HtmlIcon($identifier, $icon));
	}

	public function htmlImage($identifier, $src="", $alt="", $size=NULL) {
		return $this->addHtmlComponent(new HtmlImage($identifier, $src, $alt, $size));
	}

	/**
	 *
	 * @param string $identifier
	 * @param array $icons
	 * @param string $size
	 * @return HtmlIconGroups
	 */
	public function htmlIconGroups($identifier, $icons=array(), $size="") {
		return $this->addHtmlComponent(new HtmlIconGroups($identifier, $icons, $size));
	}

	/**
	 *
	 * @param string $identifier
	 * @param string $type
	 * @param string $value
	 * @param string $placeholder
	 * @return HtmlInput
	 */
	public function htmlInput($identifier, $type="text", $value="", $placeholder="") {
		return $this->addHtmlComponent(new HtmlInput($identifier, $type, $value, $placeholder));
	}

	/**
	 *
	 * @param string $identifier
	 * @param string $content
	 * @param string $tagName
	 * @return HtmlLabel
	 */
	public function htmlLabel($identifier, $content="", $icon=NULL,$tagName="div") {
		return $this->addHtmlComponent(new HtmlLabel($identifier, $content,$icon, $tagName));
	}

	public function htmlLabelGroups($identifier,$labels=array(),$attributes=array()){
		return $this->addHtmlComponent(new HtmlLabelGroups($identifier,$labels,$attributes));
	}

	/**
	 *
	 * @param string $identifier
	 * @param array $items
	 * @return HtmlList
	 */
	public function htmlList($identifier, $items=array()) {
		return $this->addHtmlComponent(new HtmlList($identifier, $items));
	}

	/**
	 * Adds a new segment, used to create a grouping of related content
	 * @param string $identifier
	 * @param string $content
	 * @return HtmlSegment
	 */
	public function htmlSegment($identifier, $content="") {
		return $this->addHtmlComponent(new HtmlSegment($identifier, $content));
	}

	/**
	 * Adds a group of segments
	 * @param string $identifier
	 * @param array $items the segments
	 * @return HtmlSegmentGroups
	 */
	public function htmlSegmentGroups($identifier, $items=array()) {
		return $this->addHtmlComponent(new HtmlSegmentGroups($identifier, $items));
	}

	/**
	 *
	 * @param string $identifier
	 * @param string|HtmlSemDoubleElement $visibleContent
	 * @param string|HtmlSemDoubleElement $hiddenContent
	 * @param RevealType|string $type
	 * @param Direction|string $attributeType
	 */
	public function htmlReveal($identifier, $visibleContent, $hiddenContent, $type=RevealType::FADE, $attributeType=NULL) {
		return $this->addHtmlComponent(new HtmlReveal($identifier, $visibleContent, $hiddenContent, $type, $attributeType));
	}

	public function htmlStep($identifier, $steps=array()) {
		return $this->addHtmlComponent(new HtmlStep($identifier, $steps));
	}

	public function htmlFlag($identifier, $flag) {
		return $this->addHtmlComponent(new HtmlFlag($identifier, $flag));
	}
}