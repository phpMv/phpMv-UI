<?php
namespace Ajax\semantic\traits;

use Ajax\semantic\widgets\ListView;

trait SemanticWidgetsTrait {

	public abstract function addHtmlComponent($htmlComponent);

	/**
	 * @param string $identifier
	 * @param string $model
	 * @param array $instances
	 * @return ListView
	 */
	public function listView($identifier,$model, $instances){
		return $this->addHtmlComponent(new ListView($identifier,$model,$instances));
	}
}