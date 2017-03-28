<?php
namespace Ajax\service;
class JQueryAjaxEffect {
	public static function none($responseElement,$jqueryDone="html"){
		return "\t$({$responseElement}).{$jqueryDone}( data );\n";
	}

	public static function fade($responseElement,$jqueryDone="html"){
		return "\t$({$responseElement}).hide().{$jqueryDone}( data ).fadeIn();\n";
	}

	public static function slide($responseElement,$jqueryDone="html"){
		return "\t$({$responseElement}).hide().{$jqueryDone}( data ).slideDown();\n";
	}
}
