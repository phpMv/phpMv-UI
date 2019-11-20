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
use Ajax\common\html\BaseHtml;
use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\elements\HtmlEmoji;

trait SemanticHtmlElementsTrait {

	abstract public function addHtmlComponent(BaseHtml $htmlComponent);

	public function addState($state, $elements) {
		State::add($state, $elements);
	}

	/**
	 * Return a new Semantic Html Button
	 * @see http://phpmv-ui.kobject.net/index/direct/main/31
	 * @see http://semantic-ui.com/elements/button.html
	 * @param string $identifier
	 * @param string $value
	 * @param string $cssStyle
	 * @param string $onClick
	 * @return HtmlButton
	 */
	public function htmlButton($identifier, $value=null, $cssStyle=null, $onClick=null) {
		return $this->addHtmlComponent(new HtmlButton($identifier, $value, $cssStyle, $onClick));
	}

	/**
	 * Returns a group of Semantic buttons
	 * @see http://phpmv-ui.kobject.net/index/direct/main/50
	 * @see http://semantic-ui.com/elements/button.html#buttons
	 * @param string $identifier
	 * @param array $elements
	 * @param boolean $asIcons
	 * @return HtmlButtonGroups
	 */
	public function htmlButtonGroups($identifier, $elements=array(), $asIcons=false) {
		return $this->addHtmlComponent(new HtmlButtonGroups($identifier, $elements, $asIcons));
	}

	/**
	 * Returns a new Semantic container
	 * @see http://phpmv-ui.kobject.net/index/direct/main/34
	 * @see http://semantic-ui.com/elements/container.html
	 * @param string $identifier
	 * @param string $content
	 * @return HtmlContainer
	 */
	public function htmlContainer($identifier, $content="") {
		return $this->addHtmlComponent(new HtmlContainer($identifier, $content));
	}

	/**
	 * Returns a new Semantic divider
	 * @see http://phpmv-ui.kobject.net/index/direct/main/42
	 * @see http://semantic-ui.com/elements/divider.html
	 * @param string $identifier
	 * @param string $content
	 * @return HtmlDivider
	 */
	public function htmlDivider($identifier, $content="", $tagName="div") {
		return $this->addHtmlComponent(new HtmlDivider($identifier, $content, $tagName));
	}

	/**
	 * Returns a new Semantic header
	 * @see http://phpmv-ui.kobject.net/index/direct/main/43
	 * @see http://semantic-ui.com/elements/header.html
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
	 * Returns a new Semantic icon
	 * @see http://phpmv-ui.kobject.net/index/direct/main/44
	 * @see http://semantic-ui.com/elements/icon.html
	 * @param string $identifier
	 * @param string $icon
	 * @return HtmlIcon
	 */
	public function htmlIcon($identifier, $icon) {
		return $this->addHtmlComponent(new HtmlIcon($identifier, $icon));
	}

	/**
	 * Returns a new Semantic image
	 * @see http://phpmv-ui.kobject.net/index/direct/main/55
	 * @see http://semantic-ui.com/elements/image.html
	 * @param string $identifier
	 * @param string $src
	 * @param string $alt
	 * @param string $size
	 * @return HtmlImage
	 */
	public function htmlImage($identifier, $src="", $alt="", $size=NULL) {
		return $this->addHtmlComponent(new HtmlImage($identifier, $src, $alt, $size));
	}

	/**
	 * Returns a new Semantic group of images
	 * @see http://phpmv-ui.kobject.net/index/direct/main/0
	 * @see http://semantic-ui.com/elements/image.html#size
	 * @param string $identifier
	 * @param array $icons
	 * @param string $size
	 * @return HtmlIconGroups
	 */
	public function htmlIconGroups($identifier, $icons=array(), $size="") {
		return $this->addHtmlComponent(new HtmlIconGroups($identifier, $icons, $size));
	}

	/**
	 * Returns a new Semantic html input
	 * @see http://phpmv-ui.kobject.net/index/direct/main/45
	 * @see http://semantic-ui.com/elements/input.html
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
	 * Returns a new Semantic label
	 * @see http://phpmv-ui.kobject.net/index/direct/main/46
	 * @see http://semantic-ui.com/elements/label.html
	 * @param string $identifier
	 * @param string $content
	 * @param string $tagName
	 * @return HtmlLabel
	 */
	public function htmlLabel($identifier, $content="", $icon=NULL,$tagName="div") {
		return $this->addHtmlComponent(new HtmlLabel($identifier, $content,$icon, $tagName));
	}

	/**
	 * @param string $identifier
	 * @param array $labels
	 * @param array $attributes
	 * @return HtmlLabelGroups
	 */
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
	 * @return HtmlReveal
	 */
	public function htmlReveal($identifier, $visibleContent, $hiddenContent, $type=RevealType::FADE, $attributeType=NULL) {
		return $this->addHtmlComponent(new HtmlReveal($identifier, $visibleContent, $hiddenContent, $type, $attributeType));
	}

	/**
	 * @param string $identifier
	 * @param array $steps
	 * @return HtmlStep
	 */
	public function htmlStep($identifier, $steps=array()) {
		return $this->addHtmlComponent(new HtmlStep($identifier, $steps));
	}

	/**
	 * @param string $identifier
	 * @param string $flag
	 * @return HtmlFlag
	 */
	public function htmlFlag($identifier, $flag) {
		return $this->addHtmlComponent(new HtmlFlag($identifier, $flag));
	}
	
	/**
	 * @param string $identifier
	 * @param string $emoji
	 * @return HtmlEmoji
	 */
	public function htmlEmoji($identifier, $emoji) {
		return $this->addHtmlComponent(new HtmlEmoji($identifier, $emoji));
	}
}
