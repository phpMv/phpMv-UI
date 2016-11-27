<?php

namespace Ajax;

use Ajax\common\BaseGui;
use Ajax\semantic\html\collections\menus\HtmlMenu;
use Ajax\semantic\html\modules\HtmlDropdown;
use Ajax\semantic\html\collections\HtmlMessage;
use Ajax\semantic\html\modules\HtmlPopup;
use Ajax\common\html\BaseHtml;
use Ajax\semantic\html\collections\HtmlGrid;
use Ajax\semantic\html\collections\menus\HtmlIconMenu;
use Ajax\semantic\html\collections\menus\HtmlLabeledIconMenu;
use Ajax\semantic\html\collections\HtmlBreadcrumb;
use Ajax\semantic\html\modules\HtmlAccordion;
use Ajax\semantic\components\Accordion;
use Ajax\semantic\html\collections\menus\HtmlAccordionMenu;
use Ajax\semantic\html\collections\form\HtmlForm;
use Ajax\semantic\traits\SemanticComponentsTrait;
use Ajax\semantic\traits\SemanticHtmlElementsTrait;
use Ajax\semantic\html\modules\HtmlSticky;
use Ajax\semantic\traits\SemanticHtmlCollectionsTrait;
use Ajax\semantic\traits\SemanticHtmlModulesTrait;
use Ajax\semantic\traits\SemanticHtmlViewsTrait;

/**
 * Semantic PHP library
 *
 * @author jcheron
 * @version 1.004
 * @license Apache 2 http://www.apache.org/licenses/
 */
 class Semantic extends BaseGui {
	use SemanticComponentsTrait,SemanticHtmlElementsTrait,SemanticHtmlCollectionsTrait,
	SemanticHtmlModulesTrait,SemanticHtmlViewsTrait;

	public function __construct($autoCompile=true) {
		parent::__construct($autoCompile=true);
	}

	/**
	 *
	 * @param string $identifier
	 * @param array $items
	 * @return Ajax\semantic\html\collections\menus\HtmlMenu
	 */
	public function htmlMenu($identifier, $items=array()) {
		return $this->addHtmlComponent(new HtmlMenu($identifier, $items));
	}

	/**
	 * Adds an icon menu
	 * @param string $identifier
	 * @param array $items icons
	 * @return Ajax\semantic\html\collections\menus\HtmlIconMenu
	 */
	public function htmlIconMenu($identifier, $items=array()) {
		return $this->addHtmlComponent(new HtmlIconMenu($identifier, $items));
	}

	/**
	 * Adds an labeled icon menu
	 * @param string $identifier
	 * @param array $items icons
	 * @return HtmlLabeledIconMenu
	 */
	public function htmlLabeledIconMenu($identifier, $items=array()) {
		return $this->addHtmlComponent(new HtmlLabeledIconMenu($identifier, $items));
	}

	/**
	 *
	 * @param string $identifier
	 * @param string $value
	 * @param array $items
	 * @return HtmlDropdown
	 */
	public function htmlDropdown($identifier, $value="", $items=array()) {
		return $this->addHtmlComponent(new HtmlDropdown($identifier, $value, $items));
	}

	/**
	 * Adds a new message
	 * @param string $identifier
	 * @param string $content
	 * @return HtmlMessage
	 */
	public function htmlMessage($identifier, $content="") {
		return $this->addHtmlComponent(new HtmlMessage($identifier, $content));
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
	 *
	 * @param string $identifier
	 * @param int $numRows
	 * @param int $numCols
	 * @param boolean $createCols
	 * @param boolean $implicitRows
	 * @return HtmlGrid
	 */
	public function htmlGrid($identifier, $numRows=1, $numCols=NULL, $createCols=true, $implicitRows=false) {
		return $this->addHtmlComponent(new HtmlGrid($identifier, $numRows, $numCols, $createCols, $implicitRows));
	}

	/**
	 * Returns a new Semantic Html Breadcrumb
	 * @param string $identifier
	 * @param array $items
	 * @param boolean $autoActive sets the last element's class to <b>active</b> if true. default : true
	 * @param function $hrefFunction the function who generates the href elements. default : function($e){return $e->getContent()}
	 * @return HtmlBreadcrumb
	 */
	public function htmlBreadcrumb($identifier, $items=array(), $autoActive=true, $startIndex=0, $hrefFunction=NULL) {
		return $this->addHtmlComponent(new HtmlBreadcrumb($identifier, $items, $autoActive, $startIndex, $hrefFunction));
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
	 * @return HtmlAccordion
	 */
	public function htmlAccordionMenu($identifier, $items=array()) {
		return $this->addHtmlComponent(new HtmlAccordionMenu($identifier, $items));
	}

	/**
	 * Returns a new Semantic Form
	 * @param string $identifier
	 * @param array $elements
	 * @return HtmlForm
	 */
	public function htmlForm($identifier, $elements=array()) {
		return $this->addHtmlComponent(new HtmlForm($identifier, $elements));
	}

	/**
	 * @param string $identifier
	 * @param array $content
	 * @return HtmlSticky
	 */
	public function htmlSticky($identifier, $content=array()) {
		return $this->addHtmlComponent(new HtmlSticky($identifier, $content));
	}
}