<?php
namespace Ajax\semantic\traits;

use Ajax\semantic\widgets\datatable\DataTable;
use Ajax\semantic\widgets\dataelement\DataElement;
use Ajax\semantic\widgets\dataform\DataForm;
use Ajax\semantic\widgets\business\user\FormLogin;
use Ajax\semantic\widgets\datatable\JsonDataTable;
use Ajax\semantic\widgets\business\user\FormAccount;
use Ajax\common\html\BaseHtml;

trait SemanticWidgetsTrait {

	abstract public function addHtmlComponent(BaseHtml $htmlComponent);

	/**
	 * @param string $identifier
	 * @param string $model
	 * @param array $instances
	 * @return DataTable
	 */
	public function dataTable($identifier,$model, $instances=null){
		return $this->addHtmlComponent(new DataTable($identifier,$model,$instances));
	}

	/**
	 * @param string $identifier
	 * @param string $model
	 * @param array $instances
	 * @return JsonDataTable
	 */
	public function jsonDataTable($identifier,$model, $instances=null){
		return $this->addHtmlComponent(new JsonDataTable($identifier,$model,$instances));
	}

	/**
	 * @param string $identifier
	 * @param object $instance
	 * @return DataElement
	 */
	public function dataElement($identifier, $instance=null){
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

	/**
	 * @param string $identifier
	 * @param object $instance
	 * @return FormLogin
	 */
	public function defaultLogin($identifier,$instance=null){
		return $this->addHtmlComponent(FormLogin::regular($identifier,$instance));
	}

	/**
	 * @param string $identifier
	 * @param object $instance
	 * @return FormLogin
	 */
	public function smallLogin($identifier,$instance=null){
		return $this->addHtmlComponent(FormLogin::small($identifier,$instance));
	}

	/**
	 * @param string $identifier
	 * @param object $instance
	 * @return FormLogin
	 */
	public function segmentedLogin($identifier,$instance=null){
		return $this->addHtmlComponent(FormLogin::attachedSegment($identifier,$instance));
	}

	/**
	 * @param string $identifier
	 * @param object $instance
	 * @return FormAccount
	 */
	public function defaultAccount($identifier,$instance=null){
		return $this->addHtmlComponent(FormAccount::regular($identifier,$instance));
	}
}
