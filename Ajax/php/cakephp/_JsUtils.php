<?php

namespace Ajax\php\cakephp;


use Cake\Routing\Router;
use Cake\View\View;
use Cake\Network\Response;
use Cake\Core\App;

class _JsUtils extends \Ajax\JsUtils{
	public function getUrl($url){
		return Router::url($url);
	}
	public function addViewElement($identifier,$content,&$view){
		$viewVars=$view->viewVars;
		if (isset($viewVars["q"]) === false) {
			$controls=array ();
		}else{
			$controls=$viewVars["q"];
		}
		$controls[$identifier]=$content;
		$view->set("q", $controls);
	}

	public function createScriptVariable(&$view,$view_var, $output){
		$view->set($view_var,$output);
	}

	/**
	 * @param App\Controller\AppController $initialControllerInstance
	 * @param string $controllerName
	 * @param string $actionName
	 * @param array $params
	 * @see \Ajax\JsUtils::forward()
	 */
	public function forward($initialControllerInstance,$controllerName,$actionName,$params=array()){
		\ob_start();
		if(isset($params) && !\is_array($params)){
			$params=[$params];
		}
		$url=h(Router::url(\array_merge([
				'controller' => $controllerName,
				'action' => $actionName],$params),false
		));
		$base=Router::url("/");
		if (substr($url, 0, strlen($base)) == $base) {
			$url = substr($url, strlen($base));
		}
		$initialControllerInstance->requestAction($url);
		$result=\ob_get_contents();
		\ob_end_clean();
		return $result;
	}

	public function renderContent($initialControllerInstance,$viewName, $params=NULL) {
		$view = new View(Router::getRequest(true), new Response());
		if(\is_array($params)){
			foreach ($params as $k=>$v){
				$view->set($k, $v);
			}
		}
		return $view->render($viewName);
	}

	public function fromDispatcher($dispatcher){
		return \explode("/", Router::getRequest(true)->url);
	}
}
