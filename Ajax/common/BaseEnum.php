<?php

namespace Ajax\common;

/**
 * Base class for enums
 * see at http://stackoverflow.com/questions/254514/php-and-enumerations
 * @author jc
 *
 */
abstract class BaseEnum {
	private static $constCacheArray=NULL;

	public static function getConstants() {
		if (self::$constCacheArray == NULL) {
			self::$constCacheArray=[ ];
		}
		$calledClass=get_called_class();
		if (!array_key_exists($calledClass, self::$constCacheArray)) {
			$reflect=new \ReflectionClass($calledClass);
			self::$constCacheArray[$calledClass]=$reflect->getConstants();
		}
		return self::$constCacheArray[$calledClass];
	}

	public static function getConstantValues($postFix="",$prefixBefore=false) {
		if ($postFix == "")
			return \array_values(self::getConstants());
		else {
			if($prefixBefore===false){
				return \array_map(function ($elem) use($postFix) {
					return $elem . " " . $postFix;
				}, \array_values(self::getConstants()));
			}else{
				return \array_map(function ($elem) use($postFix) {
					return $postFix." ".$elem;
				}, \array_values(self::getConstants()));
			}
		}
	}

	public static function isValidName($name, $strict=false) {
		$constants=self::getConstants();

		if ($strict) {
			return array_key_exists($name, $constants);
		}

		$keys=array_map('strtolower', array_keys($constants));
		return in_array(strtolower($name), $keys);
	}

	public static function isValidValue($value) {
		$values=array_values(self::getConstants());
		return in_array($value, $values, true);
	}
}
