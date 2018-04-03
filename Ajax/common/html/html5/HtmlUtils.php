<?php

namespace Ajax\common\html\html5;

class HtmlUtils {
	public static function javascriptInclude($url){
		return '<script src="'.$url.'"></script>';
	}
	
	public static function stylesheetInclude($url){
		return '<link rel="stylesheet" type="text/css" href="'.$url.'">';
	}
}
