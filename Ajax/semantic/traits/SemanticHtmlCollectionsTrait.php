<?php
namespace Ajax\semantic\traits;

use Ajax\semantic\html\collections\HtmlTable;


trait SemanticHtmlCollectionsTrait {

	public abstract function addHtmlComponent($htmlComponent);

	/**
	 * @param string $identifier
	 * @param int $rowCount
	 * @param int $colCount
	 */
	public function htmlTable($identifier, $rowCount, $colCount){
		return $this->addHtmlComponent(new HtmlTable($identifier, $rowCount, $colCount));
	}
}