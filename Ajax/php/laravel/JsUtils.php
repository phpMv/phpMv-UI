<?php

namespace Ajax\php\laravel;

use Illuminate\Support\Facades\App;

class JsUtils extends \Ajax\JsUtils{
	public function getUrl($url){
		return \url($url);
	}
	public function addViewElement($identifier,$content,&$view){
		$controls=$view->__get("q");
		if (isset($controls) === false) {
			$controls=array ();
		}
		$controls[$identifier]=$content;
		$view->__set("q", $controls);
	}

	public function createScriptVariable(&$view,$view_var, $output){
		$view->__set($view_var,$output);
	}

	public function forward($initialControllerInstance,$controllerName,$actionName,$params=NULL){
		\ob_start();
		App::make($controllerName)->{$actionName}($params);
		$result=\ob_get_contents();
		\ob_end_clean();
		return $result;
	}

	public function renderContent($initialControllerInstance,$viewName, $params=NULL) {
		return \view()->make($viewName,$params)->render();
	}

	public function fromDispatcher($dispatcher){
		return $dispatcher->segments();
	}
}
