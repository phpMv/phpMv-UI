<?php
namespace Ajax\semantic\traits;

use Ajax\semantic\html\collections\table\HtmlTable;
use Ajax\semantic\html\collections\HtmlMessage;
use Ajax\semantic\html\collections\menus\HtmlMenu;
use Ajax\semantic\html\collections\form\HtmlForm;
use Ajax\semantic\html\collections\HtmlGrid;


trait SemanticHtmlCollectionsTrait {

	public abstract function addHtmlComponent($htmlComponent);

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
		if(isset($msg))
			$msg->setStyle($styles);
		return $msg;
	}

	/**
	 *
	 * @param string $identifier
	 * @param array $items
	 * @return Ajax\semantic\html\collections\HtmlMenu
	 */
	public function htmlMenu($identifier, $items=array()) {
		return $this->addHtmlComponent(new HtmlMenu($identifier, $items));
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