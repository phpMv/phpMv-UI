<?php
namespace Ajax\php\ubiquity;

use Ajax\Semantic;
use Ubiquity\controllers\Controller;

/**
 * Ajax\php\ubiquity$UIService
 * This class is part of phpMv-UI
 *
 * @author jc
 * @version 1.0.0
 *
 */
class UIService {

	protected Controller $controller;

	protected JsUtils $jquery;

	protected Semantic $semantic;

	public function __construct(Controller $controller) {
		$this->jquery = $controller->jquery;
		$this->controller = $controller;
		$this->semantic = $this->jquery->semantic();
	}
}

