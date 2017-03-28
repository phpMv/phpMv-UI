<?php
namespace Ajax\semantic\traits;

use Ajax\semantic\widgets\datatable\DataTable;
use Ajax\semantic\widgets\dataelement\DataElement;
use Ajax\semantic\widgets\dataform\DataForm;
use Ajax\semantic\widgets\business\user\FormLogin;
use Ajax\semantic\widgets\datatable\JsonDataTable;

trait SemanticWidgetsTrait {

	abstract public function addHtmlComponent($htmlComponent);

	/**
	 * @param string $identifier
	 * @param string $model
	 * @param array $instances
	 * @return DataTable
	 */
	public function dataTable($identifier,$model, $instances){
		return $this->addHtmlComponent(new DataTable($identifier,$model,$instances));
	}

	/**
	 * @param string $identifier
	 * @param string $model
	 * @param array $instances
	 * @return JsonDataTable
	 */
	public function jsonDataTable($identifier,$model, $instances){
		return $this->addHtmlComponent(new JsonDataTable($identifier,$model,$instances));
	}

	/**
	 * @param string $identifier
	 * @param object $instance
	 * @return DataElement
	 */
	public function dataElement($identifier, $instance){
		return $this->addHtmlComponent(new DataElement($identifier,$instance));
	}

	/**
	 * @param string $identifier
	 * @param object $instance
	 * @return DataForm
	 */
	public function dataForm($identifier, $instance){
		return $this->addHtmlComponent(new DataForm($identifier,$instance));
	}

	public function defaultLogin($identifier,$instance=null){
		return $this->addHtmlComponent(FormLogin::regular($identifier,$instance));
	}

	public function smallLogin($identifier,$instance=null){
		return $this->addHtmlComponent(FormLogin::small($identifier,$instance));
	}

	public function segmentedLogin($identifier,$instance=null){
		return $this->addHtmlComponent(FormLogin::attachedSegment($identifier,$instance));
	}
}
