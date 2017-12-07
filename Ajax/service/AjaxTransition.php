<?php
namespace Ajax\service;
use Ajax\semantic\html\base\constants\Transition;

class AjaxTransition {
	public static function none($responseElement,$jqueryDone="html"){
		return $responseElement.".".$jqueryDone."( data )";
	}

	public static function jqFade($responseElement,$jqueryDone="html"){
		return $responseElement.".hide().{$jqueryDone}( data ).fadeIn()";
	}

	public static function jqSlide($responseElement,$jqueryDone="html"){
		return $responseElement.".hide().{$jqueryDone}( data ).slideDown()";
	}

	public static function random($responseElement,$jqueryDone="html"){
		$transitions=Transition::getConstantValues();
		$transition=$transitions[\rand(0,\sizeof($transitions)-1)];
		return self::__callStatic($transition, [$responseElement,$jqueryDone]);
	}

	public static function __callStatic($name, $arguments){
		if(\sizeof($arguments)==2){
			$responseElement=$arguments[0];
			$jqueryDone=$arguments[1];
			$name=JString::camelCaseToSeparated($name);
			return $responseElement.".".$jqueryDone."( data ).transition('{$name} in')";
		}
	}
}
