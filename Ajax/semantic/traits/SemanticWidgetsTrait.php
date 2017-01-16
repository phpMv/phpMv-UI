<?php
namespace Ajax\semantic\traits;

use Ajax\semantic\widgets\datatable\DataTable;

trait SemanticWidgetsTrait {

	public abstract function addHtmlComponent($htmlComponent);

	/**
	 * @param string $identifier
	 * @param string $model
	 * @param array $instances
	 * @return DataTable
	 */
	public function dataTable($identifier,$model, $instances){
		return $this->addHtmlComponent(new DataTable($identifier,$model,$instances));
	}
}