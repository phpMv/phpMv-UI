<?php

namespace Ajax\service;

class JReflection {
	public static function shortClassName($object){
		$classNameWithNamespace = get_class($object);
		return substr($classNameWithNamespace, strrpos($classNameWithNamespace, '\\')+1);
	}
}