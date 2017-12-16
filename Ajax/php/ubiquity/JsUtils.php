<?php

namespace Ajax\php\ubiquity;

use Ubiquity\controllers\Startup;
use Ubiquity\utils\RequestUtils;
class JsUtils extends \Ajax\JsUtils{

	public function getUrl($url){
		return RequestUtils::getUrl($url);
	}

	public function addViewElement($identifier,$content,&$view){
		$controls=$view->getVar("q");
		if (isset($controls) === false) {
			$controls=array ();
		}
		$controls[$identifier]=$content;
		$view->setVar("q", $controls);
	}

	public function createScriptVariable(&$view,$view_var, $output){
		$view->setVar($view_var,$output);
	}

	public function forward($initialController,$controller,$action,$params=array()){
		return $initialController->forward($controller,$action,$params,true,true,true);
	}

	public function renderContent($initialControllerInstance,$viewName, $params=NULL) {
		return $initialControllerInstance->loadView($viewName,$params,true);
	}

	public function fromDispatcher($dispatcher){
		return Startup::$urlParts;
	}
}
