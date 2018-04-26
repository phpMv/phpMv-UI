<?php

namespace Ajax\php\ubiquity;

use Ubiquity\controllers\Startup;
use Ubiquity\utils\http\URequest;

class JsUtils extends \Ajax\JsUtils{

	public function getUrl($url){
		return URequest::getUrl($url);
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
	
	/**
	 * Performs jQuery compilation and displays a view
	 * @param string $viewName
	 * @param mixed $parameters Variable or associative array to pass to the view <br> If a variable is passed, it will have the name <b> $ data </ b> in the view, <br>
	 * If an associative array is passed, the view retrieves variables from the table's key names
	 * @param boolean $asString If true, the view is not displayed but returned as a string (usable in a variable)
	 */
	public function renderView($viewName,$parameters=[],$asString=false){
		if(isset($this->injected)){
			$view=$this->injected->getView();
			$this->compile($view);
			if (isset($parameters))
				$view->setVars($parameters);
			return $view->render($viewName, $asString);
		}
		throw new \Exception(get_class()." instance is not properly instancied : you omitted the second parameter \$controller!");
	}
	
	/**
	 * Performs jQuery compilation and displays the default view
	 * @param mixed $parameters Variable or associative array to pass to the view <br> If a variable is passed, it will have the name <b> $ data </ b> in the view, <br>
	 * If an associative array is passed, the view retrieves variables from the table's key names
	 * @param boolean $asString If true, the view is not displayed but returned as a string (usable in a variable)
	 */
	public function renderDefaultView($parameters=[],$asString=false){
		return $this->renderView($this->injected->getDefaultViewName(),$parameters,$asString);
	}
}
