<?php

namespace Ajax\service;

class JArray {

	public static function isAssociative($array) {
		return (array_values($array) !== $array);
		// return (array_keys($array)!==range(0, count($array)-1));
	}

	public static function getValue($array, $key, $pos) {
		if (array_key_exists($key, $array)) {
			return $array[$key];
		}
		$values=array_values($array);
		if ($pos < sizeof($values))
			return $values[$pos];
	}

	public static function getConditionalValue($array, $key, $condition) {
		$result=NULL;
		if (array_key_exists($key, $array)) {
			$result=$array[$key];
			if ($condition($result) === true)
				return $result;
		}
		$values=array_values($array);
		foreach ( $values as $val ) {
			if ($condition($val) === true)
				return $val;
		}
		return $result;
	}

	public static function getDefaultValue($array, $key, $default=NULL) {
		if (array_key_exists($key, $array)) {
			return $array[$key];
		} else
			return $default;
	}

	public static function implode($glue, $pieces) {
		$result="";
		if (\is_array($glue)) {
			$size=\sizeof($pieces);
			if ($size > 0) {
				for($i=0; $i < $size - 1; $i++) {
					$result.=$pieces[$i] . @$glue[$i];
				}
				$result.=$pieces[$size - 1];
			}
		} else {
			$result=\implode($glue, $pieces);
		}
		return $result;
	}

	public static function dimension($array) {
		if (is_array(reset($array))) {
			$return=self::dimension(reset($array)) + 1;
		} else {
			$return=1;
		}
		return $return;
	}

	public static function sortAssociative($array, $sortedKeys=array()) {
		$newArray=array ();
		foreach ( $sortedKeys as $key ) {
			if (\array_key_exists($key, $array)) {
				$newArray[$key]=$array[$key];
			}
		}
		return $newArray;
	}
}