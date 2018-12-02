<?php

namespace Ajax\common\html\traits;

trait BaseHooksTrait {
	protected $_hooks=[];
	
	/**
	 * @param string $hookKey
	 * @return boolean
	 */
	public function hookExists($hookKey){
		return isset($this->_hooks[$hookKey]);
	}
	
	/**
	 * @param string $hookKey
	 * @return callable|NULL
	 */
	public function getHook($hookKey){
		if(isset($this->_hooks[$hookKey])){
			return $this->_hooks[$hookKey];
		}
		return null;
	}
	
	/**
	 * Adds a new Hook
	 * @param String $hookKey
	 * @param callable $callable
	 */
	public function addHook($hookKey,$callable){
		$this->_hooks[$hookKey]=$callable;
	}
	
	/**
	 * Executes the hook with key $hookKey
	 * @param string $hookKey
	 * @param mixed|null $variable
	 * @return void|mixed
	 */
	public function execHook($hookKey,...$variables){
		if(($hook=$this->getHook($hookKey))!=null){
			return call_user_func_array($hook,$variables);
		}
		return;
	}
}

