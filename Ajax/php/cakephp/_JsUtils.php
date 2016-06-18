<?php

namespace Ajax\php\cakephp;


use Ajax\service\JString;
use Cake\Routing\Router;
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
	 * @param Symfony\Component\DependencyInjection\ContainerInterface $initialControllerInstance
	 * @param string $controllerName
	 * @param string $actionName
	 * @param array $params
	 * @see \Ajax\JsUtils::forward()
	 */
	public function forward($initialControllerInstance,$controllerName,$actionName,$params=array()){
		$path=$params;
		$request = $initialControllerInstance->get('request_stack')->getCurrentRequest();
		$path['_forwarded'] = $request->attributes;
		$path['_controller'] = $controllerName.":".$actionName;
		$subRequest = $request->duplicate([], null, $path);
		$response= $initialControllerInstance->get('http_kernel')->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
		return $response->getContent();
	}

	public function renderContent($initialControllerInstance,$viewName, $params=NULL) {
        if ($initialControllerInstance->has('templating')) {
            return $initialControllerInstance->get('templating')->render($viewName, $params);
        }

        if (!$initialControllerInstance->has('twig')) {
            throw new \LogicException('You can not use the "renderView" method if the Templating Component or the Twig Bundle are not available.');
        }

        return $initialControllerInstance->get('twig')->render($viewName, $params);
	}

	public function fromDispatcher($dispatcher){
		return \explode("/", Router::getRequest(true)->url);
	}
}