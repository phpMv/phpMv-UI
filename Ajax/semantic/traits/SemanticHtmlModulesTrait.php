<?php

namespace Ajax\semantic\traits;

use Ajax\semantic\html\base\constants\CheckboxType;
use Ajax\semantic\html\modules\HtmlRating;
use Ajax\semantic\html\modules\HtmlProgress;
use Ajax\semantic\html\modules\HtmlSearch;
use Ajax\semantic\html\modules\HtmlDimmer;
use Ajax\semantic\html\modules\HtmlModal;
use Ajax\semantic\html\modules\checkbox\HtmlCheckbox;
use Ajax\semantic\html\modules\HtmlTab;
use Ajax\semantic\html\modules\HtmlShape;
use Ajax\semantic\html\modules\HtmlPopup;
use Ajax\semantic\html\modules\HtmlDropdown;
use Ajax\common\html\BaseHtml;
use Ajax\semantic\html\modules\HtmlAccordion;
use Ajax\semantic\html\collections\menus\HtmlAccordionMenu;
use Ajax\semantic\html\modules\HtmlSticky;
use Ajax\semantic\html\collections\form\HtmlFormFields;
use Ajax\semantic\html\modules\HtmlSlider;

trait SemanticHtmlModulesTrait {

	abstract public function addHtmlComponent(BaseHtml $htmlComponent);

	/**
	 * Module checkbox
	 * @param string $identifier
	 * @param string $label
	 * @param mixed $value
	 * @param CheckboxType $type
	 * @return HtmlCheckbox
	 */
	public function htmlCheckbox($identifier, $label=NULL, $value=NULL, $type=NULL) {
		return $this->addHtmlComponent(new HtmlCheckbox($identifier, $label, $value, $type));
	}
	
	/**
	 * @param string $identifier
	 * @param array $items
	 * @param string $label
	 * @param mixed $value
	 * @param string $type
	 * @return HtmlFormFields
	 */
	public function htmlRadios($identifier, $items=[], $label=NULL,$value=NULL, $type=NULL) {
		return $this->addHtmlComponent(HtmlFormFields::radios($identifier,$items,$label,$value,$type));
	}

	/**
	 *
	 * @param string $identifier
	 * @param int $value
	 * @param int $max
	 * @param string $icon
	 * @return HtmlRating
	 */
	public function htmlRating($identifier, $value, $max, $icon="") {
		return $this->addHtmlComponent(new HtmlRating($identifier, $value, $max, $icon));
	}

	/**
	 *
	 * @param string $identifier
	 * @param int $value
	 * @param string $label
	 * @return HtmlProgress
	 */
	public function htmlProgress($identifier, $value=0, $label=NULL) {
		return $this->addHtmlComponent(new HtmlProgress($identifier, $value, $label));
	}

	/**
	 *
	 * @param string $identifier
	 * @param string $placeholder
	 * @return HtmlSearch
	 */
	public function htmlSearch($identifier, $placeholder=NULL, $icon=NULL) {
		return $this->addHtmlComponent(new HtmlSearch($identifier, $placeholder, $icon));
	}

	/**
	 *
	 * @param string $identifier
	 * @param mixed $content
	 * @return HtmlDimmer
	 */
	public function htmlDimmer($identifier, $content=NULL) {
		return $this->addHtmlComponent(new HtmlDimmer($identifier, $content));
	}


	/**
	 * Returns a new semantic modal dialog
	 * @param string $identifier
	 * @param string $header
	 * @param string $content
	 * @param array $actions
	 * @return HtmlModal
	 */
	public function htmlModal($identifier, $header="", $content="", $actions=array()) {
		return $this->addHtmlComponent(new HtmlModal($identifier, $header,$content,$actions));
	}

	/**
	 * Returns a new Semantic Tab
	 * @see http://semantic-ui.com/modules/tab.html
	 * @param array $tabs
	 * @return HtmlTab
	 */
	public function htmlTab($identifier, $tabs=array()) {
		return $this->addHtmlComponent(new HtmlTab($identifier, $tabs));
	}

	/**
	 * Returns a new Semantic Shape
	 * @see http://semantic-ui.com/modules/shape.html
	 * @param array $slides
	 * @return HtmlShape
	 */
	public function htmlShape($identifier, $slides=array()) {
		return $this->addHtmlComponent(new HtmlShape($identifier, $slides));
	}

	/**
	 *
	 * @param string $identifier
	 * @param string $value
	 * @param array $items
	 * @param boolean $associative
	 * @return HtmlDropdown
	 */
	public function htmlDropdown($identifier, $value="", $items=array(),$associative=true) {
		return $this->addHtmlComponent(new HtmlDropdown($identifier, $value, $items,$associative));
	}

	/**
	 *
	 * @param string $identifier
	 * @param mixed $content
	 * @return HtmlPopup
	 */
	public function htmlPopup(BaseHtml $container, $identifier, $content) {
		return $this->addHtmlComponent(new HtmlPopup($container, $identifier, $content));
	}

	/**
	 * Returns a new Semantic Accordion
	 * @param string $identifier
	 * @return HtmlAccordion
	 */
	public function htmlAccordion($identifier) {
		return $this->addHtmlComponent(new HtmlAccordion($identifier));
	}

	/**
	 * Return a new Semantic Menu Accordion
	 * @param string $identifier
	 * @return HtmlAccordionMenu
	 */
	public function htmlAccordionMenu($identifier, $items=array()) {
		return $this->addHtmlComponent(new HtmlAccordionMenu($identifier, $items));
	}


	/**
	 * Returns a new Semantic Sticky
	 * @param string $identifier
	 * @param array $content
	 * @return HtmlSticky
	 */
	public function htmlSticky($identifier, $content=array()) {
		return $this->addHtmlComponent(new HtmlSticky($identifier, $content));
	}
	
	/**
	 * Returns a new Semantic Slider
	 * @param string $identifier
	 * @return HtmlSlider
	 */
	public function htmlSlider($identifier) {
		return $this->addHtmlComponent(new HtmlSlider($identifier));
	}
}
