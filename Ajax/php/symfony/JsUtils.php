<?php

namespace Ajax\php\symfony;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Ajax\service\JString;
class JsUtils extends \Ajax\JsUtils{
	public function getUrl($url){
		//$request = Request::createFromGlobals();
		$router=$this->getInjected();
		if(isset($router)===true){
			try {
			$url=$router->generate($url);
			}catch (\Exception $e){
				return $router->getContext()->getBaseUrl();
			}
		}
		return $url;
	}
	public function addViewElement($identifier,$content,&$view){
		if(\array_key_exists("q", $view)===false){
			$view["q"]=array();
		}
		$view["q"][$identifier]=$content;
	}

	public function createScriptVariable(&$view,$view_var, $output){
		$view[$view_var]=$output;
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
		$request = $dispatcher->get('request_stack')->getCurrentRequest();
		$uri=$request->getPathInfo();
		if(JString::startswith($uri, "/")){
			$uri=\substr($uri, 1);
		}
		return \explode("/", $uri);
	}
}