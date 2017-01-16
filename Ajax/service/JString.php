<?php
namespace Ajax\service;
class JString {

	public static function contains($hay,$needle){
		return strpos($hay, $needle) !== false;
	}
	public static function startswith($hay, $needle) {
		return substr($hay, 0, strlen($needle)) === $needle;
	}

	public static function endswith($hay, $needle) {
		return substr($hay, -strlen($needle)) === $needle;
	}

	public static function isNull($s){
		return (!isset($s) || NULL===$s || ""===$s);
	}
	public static function isNotNull($s){
		return (isset($s) && NULL!==$s && ""!==$s);
	}

	public static function isBoolean($value){
		return \is_bool($value) || $value==1 || $value==0;
	}

	public static function isBooleanTrue($value){
		return $value==1 || $value;
	}

	public static function isBooleanFalse($value){
		return $value==0 || !$value;
	}
}