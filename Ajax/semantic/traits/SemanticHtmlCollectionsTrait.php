<?php
namespace Ajax\semantic\traits;

use Ajax\semantic\html\collections\table\HtmlTable;
use Ajax\semantic\html\collections\HtmlMessage;
use Ajax\semantic\html\collections\menus\HtmlMenu;
use Ajax\semantic\html\collections\form\HtmlForm;
use Ajax\semantic\html\collections\HtmlGrid;
use Ajax\semantic\html\collections\HtmlBreadcrumb;
use Ajax\semantic\html\collections\menus\HtmlIconMenu;
use Ajax\semantic\html\collections\menus\HtmlLabeledIconMenu;
use Ajax\common\html\BaseHtml;


trait SemanticHtmlCollectionsTrait {

	abstract public function addHtmlComponent(BaseHtml $htmlComponent);

	/**
	 * @param string $identifier
	 * @param int $rowCount
	 * @param int $colCount
	 * @return HtmlTable
	 */
	public function htmlTable($identifier, $rowCount, $colCount){
		return $this->addHtmlComponent(new HtmlTable($identifier, $rowCount, $colCount));
	}

	/**
	 * Adds a new message
	 * @param string $identifier
	 * @param string $content
	 * @param $styles string|array|NULL
	 * @return HtmlMessage
	 */
	public function htmlMessage($identifier, $content="",$styles=NULL) {
		$msg= $this->addHtmlComponent(new HtmlMessage($identifier, $content));
		if(isset($msg) && $styles!==null)
			$msg->setStyle($styles);
		return $msg;
	}

	/**
	 *
	 * @param string $identifier
	 * @param array $items
	 * @return HtmlMenu
	 */
	public function htmlMenu($identifier, $items=array()) {
		return $this->addHtmlComponent(new HtmlMenu($identifier, $items));
	}

	/**
	 * Adds an icon menu
	 * @param string $identifier
	 * @param array $items icons
	 */
	public function htmlIconMenu($identifier, $items=array()) {
		return $this->addHtmlComponent(new HtmlIconMenu($identifier, $items));
	}

	/**
	 * Adds an labeled icon menu
	 * @param string $identifier
	 * @param array $items icons
	 */
	public function htmlLabeledIconMenu($identifier, $items=array()) {
		return $this->addHtmlComponent(new HtmlLabeledIconMenu($identifier, $items));
	}

	/**
	 * Returns a new Semantic Html Breadcrumb
	 * @param string $identifier
	 * @param array $items
	 * @param boolean $autoActive sets the last element's class to <b>active</b> if true. default : true
	 * @param callable $hrefFunction the function who generates the href elements. default : function($e){return $e->getContent()}
	 * @return HtmlBreadcrumb
	 */
	public function htmlBreadcrumb($identifier, $items=array(), $autoActive=true, $startIndex=0, $hrefFunction=NULL) {
		return $this->addHtmlComponent(new HtmlBreadcrumb($identifier, $items, $autoActive, $startIndex, $hrefFunction));
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
}
