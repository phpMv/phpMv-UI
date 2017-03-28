<?php

namespace Ajax\php\yii;

use yii\helpers\Url;

class JsUtils extends \Ajax\JsUtils{
	public function getUrl($url){
		if($url==="")
			$url="/";
		return Url::toRoute($url);
	}

	public function addViewElement($identifier,$content,&$view){
		$params=$view->params;
		if (\array_key_exists("q", $params)===false) {
			$view->params["q"]=array();
		}
		$view->params["q"][$identifier]=$content;
	}

	public function createScriptVariable(&$view,$view_var, $output){
		$view->params[$view_var]=$output;
	}

	public function forward($initialControllerInstance,$controllerName,$actionName,$params=array()){
		\ob_start();
		$ctrInfo=\yii::$app->createController($controllerName."/".$actionName);
		$ctrInfo[0]->{$ctrInfo[1]}($params);
		$result=\ob_get_contents();
		\ob_end_clean();
		return $result;
	}

	public function renderContent($initialControllerInstance,$viewName, $params=NULL) {
		return \yii::$app->view->render($viewName,$params);
	}

	public function fromDispatcher($dispatcher){
		$uri=new \Ajax\php\yii\URI();
		return $uri->segment_array();
	}
}
