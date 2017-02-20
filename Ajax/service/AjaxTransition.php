<?php
namespace Ajax\service;
class AjaxTransition {
	public static function none($responseElement,$jqueryDone="html"){
		return "$({$responseElement}).{$jqueryDone}( data )";
	}

	public static function fade($responseElement,$jqueryDone="html"){
		return "$({$responseElement}).hide().{$jqueryDone}( data ).fadeIn()";
	}

	public static function slide($responseElement,$jqueryDone="html"){
		return "$({$responseElement}).hide().{$jqueryDone}( data ).slideDown()";
	}

	public static function bSlidedown($responseElement,$jqueryDone="html"){
		return "$({$responseElement}).{$jqueryDone}( data ).transition('slide down in')";
	}

	public static function bScale($responseElement,$jqueryDone="html"){
		return "$({$responseElement}).{$jqueryDone}( data ).transition('scale in')";
	}
}