<?php
namespace Ajax\service;

class JArray {

	public static function isAssociative($array) {
		return (array_values($array) !== $array);
	}

	public static function getValue($array, $key, $pos) {
		if (array_key_exists($key, $array)) {
			return $array[$key];
		}
		$values = array_values($array);
		if ($pos < sizeof($values))
			return $values[$pos];
	}

	public static function getConditionalValue($array, $key, $condition) {
		$result = NULL;
		if (array_key_exists($key, $array)) {
			$result = $array[$key];
			if ($condition($result) === true)
				return $result;
		}
		$values = array_values($array);
		foreach ($values as $val) {
			if ($condition($val) === true)
				return $val;
		}
		return $result;
	}

	public static function getDefaultValue($array, $key, $default = NULL) {
		if (array_key_exists($key, $array)) {
			return $array[$key];
		} else
			return $default;
	}

	public static function implode($glue, $pieces) {
		$result = "";
		if (\is_array($glue)) {
			$size = \sizeof($pieces);
			if ($size > 0) {
				for ($i = 0; $i < $size - 1; $i ++) {
					$result .= $pieces[$i] . @$glue[$i];
				}
				$result .= $pieces[$size - 1];
			}
		} else {
			$result = \implode($glue, $pieces);
		}
		return $result;
	}

	public static function dimension($array) {
		if (\is_array(reset($array))) {
			$return = self::dimension(reset($array)) + 1;
		} else {
			$return = 1;
		}
		return $return;
	}

	public static function sortAssociative($array, $sortedKeys = array()) {
		$newArray = array();
		foreach ($sortedKeys as $key) {
			if (\array_key_exists($key, $array)) {
				$newArray[$key] = $array[$key];
			}
		}
		return $newArray;
	}

	public static function moveElementTo(&$array, $from, $to) {
		$result = false;
		if (isset($array)) {
			if (isset($array[$from])) {
				$out = array_splice($array, $from, 1);
				array_splice($array, $to, 0, $out);
				$result = true;
			}
		}
		return $result;
	}

	public static function swapElements(&$array, $index1, $index2) {
		$result = false;
		if (isset($array)) {
			if (isset($array[$index1]) && isset($array[$index2])) {
				$tmp = $array[$index1];
				$array[$index1] = $array[$index2];
				$array[$index2] = $tmp;
				$result = true;
			}
		}
		return $result;
	}

	public static function modelArray($objects, $identifierFunction = NULL, $modelFunction = NULL) {
		$result = [];
		if (isset($modelFunction) === false) {
			$modelFunction = "__toString";
		}
		if (isset($identifierFunction) === false) {
			foreach ($objects as $object) {
				$result[] = self::callFunction($object, $modelFunction);
			}
		} else {
			foreach ($objects as $object) {
				$result[self::callFunction($object, $identifierFunction)] = self::callFunction($object, $modelFunction);
			}
		}
		return $result;
	}

	private static function callFunction($object, $callback) {
		if (\is_string($callback))
			return \call_user_func(array(
				$object,
				$callback
			), []);
		else if (\is_callable($callback)) {
			return $callback($object);
		}
	}

	public static function count($array) {
		if (\is_array($array)) {
			return \sizeof($array);
		}
		return 0;
	}

	public static function removeByKeys($array, $keys) {
		$assocKeys = [];
		foreach ($keys as $key) {
			$assocKeys[$key] = true;
		}
		return \array_diff_key($array, $assocKeys);
	}
}
