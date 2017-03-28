<?php

namespace Ajax\php\phalcon;

use Phalcon\DiInterface;
use Phalcon\Di\InjectionAwareInterface;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Controller;

class JsUtils extends \Ajax\JsUtils implements InjectionAwareInterface{
	protected $_di;
	public function setDi(DiInterface $di) {
		$this->_di=$di;
		//$this->_setDi($di);
	}

	public function getDi() {
		return $this->_di;
	}

	public function getUrl($url){
		return $this->_di->get("url")->get($url);
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
		$dispatcher = $initialController->dispatcher;
		$dispatcher->setControllerName($controller);
		$dispatcher->setActionName($action);
		$dispatcher->dispatch();
		$template=$initialController->view->getRender($dispatcher->getControllerName(), $dispatcher->getActionName(),$dispatcher->getParams(), function ($view) {
			$view->setRenderLevel(View::LEVEL_ACTION_VIEW);
		});
		return $template;
	}

	public function renderContent($initialControllerInstance,$viewName, $params=NULL) {
		list($controller,$action)=\explode("@", $viewName);
		$template=$initialControllerInstance->view->getRender($controller, $action, $params, function ($view) {
			$view->setRenderLevel(View::LEVEL_ACTION_VIEW);
		});
		return $template;
	}

	public function fromDispatcher($dispatcher){
		$params=$dispatcher->getParams();
		$action=$dispatcher->getActionName();
		$items=array($dispatcher->getControllerName());
		if(\sizeof($params)>0 || \strtolower($action)!="index" ){
			$items[]=$action;
			foreach ($params as $p){
				if(\is_object($p)===false)
					$items[]=$p;
			}
		}
		return $items;
	}
}
