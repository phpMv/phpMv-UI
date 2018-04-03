<?php

namespace Ajax;

use Ajax\common\BaseGui;
use Ajax\bootstrap\html\HtmlButton;
use Ajax\bootstrap\html\HtmlButtongroups;
use Ajax\bootstrap\html\HtmlGlyphButton;
use Ajax\bootstrap\html\HtmlDropdown;
use Ajax\bootstrap\html\HtmlButtontoolbar;
use Ajax\bootstrap\html\HtmlNavbar;
use Ajax\bootstrap\html\HtmlProgressbar;
use Ajax\bootstrap\html\HtmlPanel;
use Ajax\bootstrap\html\HtmlAlert;
use Ajax\bootstrap\html\HtmlAccordion;
use Ajax\bootstrap\html\HtmlCarousel;
use Ajax\bootstrap\html\HtmlTabs;
use Ajax\bootstrap\html\HtmlModal;
use Ajax\bootstrap\html\HtmlSplitbutton;
use Ajax\bootstrap\html\HtmlInputgroup;
use Ajax\bootstrap\html\HtmlListgroup;
use Ajax\bootstrap\html\HtmlBreadcrumbs;
use Ajax\bootstrap\html\HtmlPagination;
use Ajax\bootstrap\html\HtmlGridSystem;
use Ajax\bootstrap\traits\BootstrapComponentsTrait;

class Bootstrap extends BaseGui {
	use BootstrapComponentsTrait;
	public function __construct($autoCompile=true) {
		parent::__construct($autoCompile);
	}


	/**
	 * Return a new Bootstrap Html Button
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
	 * Return a new Bootstrap Html Glyphbutton
	 * @param string $identifier
	 * @param mixed $glyphIcon
	 * @param string $value
	 * @param string $cssStyle
	 * @param string $onClick
	 * @return HtmlGlyphButton
	 */
	public function htmlGlyphButton($identifier, $glyphIcon=0, $value="", $cssStyle=NULL, $onClick=NULL) {
		return $this->addHtmlComponent(new HtmlGlyphButton($identifier, $glyphIcon, $value, $cssStyle, $onClick));
	}

	/**
	 * Return a new Bootstrap Html Buttongroups
	 * @param string $identifier
	 * @param array $values
	 * @param string $cssStyle
	 * @param string $size
	 * @return HtmlButtongroups
	 */
	public function htmlButtongroups($identifier, $values=array(), $cssStyle=NULL, $size=NULL) {
		return $this->addHtmlComponent(new HtmlButtongroups($identifier, $values, $cssStyle, $size));
	}

	/**
	 * Return a new Bootstrap Html Dropdown
	 * @param string $identifier
	 * @param array $items
	 * @param string $cssStyle
	 * @param string $size
	 * @return HtmlDropdown
	 */
	public function htmlDropdown($identifier, $value="", $items=array(), $cssStyle=NULL, $size=NULL) {
		return $this->addHtmlComponent(new HtmlDropdown($identifier, $value, $items, $cssStyle, $size));
	}

	/**
	 * Return a new Bootstrap Html Dropdown
	 * @param string $identifier
	 * @param array $elements
	 * @param string $cssStyle
	 * @param string $size
	 * @return HtmlButtontoolbar
	 */
	public function htmlButtontoolbar($identifier, $elements=array(), $cssStyle=NULL, $size=NULL) {
		return $this->addHtmlComponent(new HtmlButtontoolbar($identifier, $elements, $cssStyle, $size));
	}

	/**
	 * Return a new Bootstrap Html Navbar
	 * @param string $identifier
	 * @param string $brand
	 * @param string $brandHref
	 * @return HtmlNavbar
	 */
	public function htmlNavbar($identifier, $brand="Brand", $brandHref="#") {
		return $this->addHtmlComponent(new HtmlNavbar($identifier, $brand, $brandHref));
	}

	/**
	 * Return a new Bootstrap Html Progressbar
	 * @param string $identifier
	 * @param string $value
	 * @param string $max
	 * @param string $min
	 * @return HtmlProgressbar
	 */
	public function htmlProgressbar($identifier, $style="info", $value=0, $max=100, $min=0) {
		return $this->addHtmlComponent(new HtmlProgressbar($identifier, $style, $value, $max, $min));
	}

	/**
	 * Return a new Bootstrap Html Panel
	 * @param string $identifier the Html identifier of the element
	 * @param mixed $content the panel content (string or HtmlComponent)
	 * @param string $header the header
	 * @param string $footer the footer
	 * @return HtmlPanel
	 */
	public function htmlPanel($identifier, $content=NULL, $header=NULL, $footer=NULL) {
		return $this->addHtmlComponent(new HtmlPanel($identifier, $content, $header, $footer));
	}

	/**
	 * Return a new Bootstrap Html Alert
	 * @param string $identifier
	 * @param string $message
	 * @param string $cssStyle
	 * @return HtmlAlert
	 */
	public function htmlAlert($identifier, $message=NULL, $cssStyle="alert-warning") {
		return $this->addHtmlComponent(new HtmlAlert($identifier, $message, $cssStyle));
	}

	/**
	 * Return a new Bootstrap Accordion
	 * @param string $identifier
	 * @return HtmlAccordion
	 */
	public function htmlAccordion($identifier) {
		return $this->addHtmlComponent(new HtmlAccordion($identifier));
	}

	/**
	 * Return a new Bootstrap Html Carousel
	 * @param string $identifier
	 * @param array $images [(src=>"",alt=>"",caption=>"",description=>""),...]
	 * @return HtmlCarousel
	 */
	public function htmlCarousel($identifier, $images=NULL) {
		return $this->addHtmlComponent(new HtmlCarousel($identifier, $images));
	}

	/**
	 * Return a new Bootstrap Html tabs
	 * @param string $identifier
	 * @return HtmlTabs
	 */
	public function htmlTabs($identifier) {
		return $this->addHtmlComponent(new HtmlTabs($identifier));
	}
	/**
	 * Return a new Bootstrap Html listGroup
	 * @param string $identifier
	 * @param array $items array of items to add
	 * @param string $tagName container tagName
	 * @return HtmlListgroup
	 */
	public function htmlListgroup($identifier,$items=array(),$tagName="ul"){
		$listGroup=new HtmlListgroup($identifier,$tagName);
		$listGroup->addItems($items);
		return $this->addHtmlComponent($listGroup);
	}
	/**
	 * Return a new Bootstrap Html modal dialog
	 * @param string $identifier
	 * @param string $title
	 * @param string $content
	 * @param array $buttonCaptions
	 * @return HtmlModal
	 */
	public function htmlModal($identifier, $title="", $content="", $buttonCaptions=array()) {
		return $this->addHtmlComponent(new HtmlModal($identifier, $title, $content, $buttonCaptions));
	}

	/**
	 * Return a new Bootstrap Html SplitButton
	 * @param string $identifier
	 * @param string $value
	 * @param array $items
	 * @param string $cssStyle
	 * @param string $onClick
	 * @return HtmlSplitbutton
	 */
	public function htmlSplitbutton($identifier,$value="", $items=array(), $cssStyle="btn-default", $onClick=NULL) {
		return $this->addHtmlComponent(new HtmlSplitbutton($identifier, $value, $items, $cssStyle,$onClick));
	}

	/**
	 * Return a new Bootstrap Html InputGroup
	 * @param string $identifier
	 * @return HtmlInputgroup
	 */
	public function htmlInputgroup($identifier){
		return $this->addHtmlComponent(new HtmlInputgroup($identifier));
	}

	/**
	 * Return a new Bootstrap Html Breadcrumbs
	 * @param string $identifier
	 * @param array $elements
	 * @param boolean $autoActive sets the last element's class to <b>active</b> if true. default : true
	 * @param callable $hrefFunction the function who generates the href elements. default : function($e){return $e->getContent()}
	 * @return HtmlBreadcrumbs
	 */
	public function htmlBreadcrumbs($identifier,$elements=array(),$autoActive=true,$startIndex=0,$hrefFunction=NULL){
		return $this->addHtmlComponent(new HtmlBreadcrumbs($identifier,$elements,$autoActive,$startIndex,$hrefFunction));
	}

	/**
	 * Return a new Bootstrap Html Pagination
	 * @see http://getbootstrap.com/components/#pagination
	 * @param string $identifier
	 * @param int $from default : 1
	 * @param int $to default : 1
	 * @param int $active The active page
	 * @return HtmlPagination
	 */
	public function htmlPagination($identifier,$from=1,$to=1,$active=NULL,$countVisible=NULL){
		return $this->addHtmlComponent(new HtmlPagination($identifier,$from,$to,$active,$countVisible));
	}

	/**
	 * Return a new Bootstrap Html Grid system
	 * @see http://getbootstrap.com/css/#grid
	 * @param string $identifier
	 * @param int $numRows
	 * @param int $numCols
	 * @return HtmlGridSystem
	 */
	public function htmlGridSystem($identifier,$numRows=1,$numCols=NULL){
		return $this->addHtmlComponent(new HtmlGridSystem($identifier,$numRows,$numCols));
	}
}
