<?php

namespace Ajax\common\components;


use Ajax\JsUtils;
/**
 * Base component for JQuery UI visuals components
 * @author jc
 * @version 1.001
 */
abstract class BaseComponent {
	public $jquery_code_for_compile=array ();
	protected $params=array ();

	/**
	 *
	 * @var JsUtils
	 */
	protected $js;

	public function __construct(JsUtils $js=NULL) {
		$this->js=$js;
	}

	protected function getParamsAsJSON($params) {
		$result="";
		if (sizeof($params)>0) {
			$result=json_encode($params, JSON_UNESCAPED_SLASHES);
			$result=str_ireplace("%quote%", "\"", $result);
		}
		return $result;
	}

	public function setParam($key, $value) {
		$this->params [$key]=$value;
		return $this;
	}

	public function getParam($key) {
		$value=null;
		if (array_key_exists($key, $this->params))
			$value=$this->params [$key];
		return $value;
	}

	public function getParams() {
		return $this->params;
	}

	public function compile(JsUtils $js=NULL) {
		if ($js==NULL)
			$js=$this->js;
		$script=$this->getScript();
		$js->addToCompile($script);
	}

	protected function setParamCtrl($key, $value, $typeCtrl) {
		if (\is_array($typeCtrl)) {
			if (array_search($value, $typeCtrl)===false)
				throw new \Exception("La valeur passée a propriété `".$key."` ne fait pas partie des valeurs possibles : {".implode(",", $typeCtrl)."}");
		} else {
			if (!$typeCtrl($value)) {
				throw new \Exception("La fonction ".$typeCtrl." a retourné faux pour l'affectation de la propriété ".$key);
			}
		}
		$this->setParam($key, $value);
	}

	public function setParams($params) {
		if(\is_array($params)) {
			foreach ($params as $k => $v) {
				$method = "set" . ucfirst($k);
				if (method_exists($this, $method))
					$this->$method($v);
				else {
					$this->setParam($k, $v);
					trigger_error("`{$k}` doesn't exists!", E_USER_NOTICE);
				}
			}
		}

		return $this;
	}

	public function addParams($params){
		foreach ($params as $k=>$v){
				$this->setParam($k, $v);
		}
		return $this;
	}

	abstract public function getScript();

	public function setDebug($value){
		return $this->setParam("debug", $value);
	}

	public function setVerbose($value){
		return $this->setParam("verbose", $value);
	}

}
