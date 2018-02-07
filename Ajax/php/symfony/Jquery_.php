<?php
namespace Ajax\php\symfony;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

abstract class Jquery_ extends JsUtils {
	protected $container;

	/**
	 * Performs jQuery compilation and displays a view
	 * @param string $viewName
	 * @param array $parameters
	 * @return Response
	 */
	public function renderView($viewName,$parameters=[]){
		$twig=$this->container->get("twig");
		$this->compile($parameters);
		return new Response($twig->render($viewName, $parameters));
	}

	public function generateUrl($path){
		$request=Request::createFromGlobals();
		return $request->getBaseUrl().$path;
	}
}
