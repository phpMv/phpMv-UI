<?php
namespace Ajax\service;

class JString {

	public static function contains($hay, $needle) {
		return strpos($hay, "$needle") !== false;
	}

	public static function startswith($hay, $needle) {
		return substr($hay, 0, strlen($needle)) === $needle;
	}

	public static function endswith($hay, $needle) {
		return substr($hay, - strlen($needle)) === $needle;
	}

	public static function isNull($s) {
		return (! isset($s) || NULL === $s || "" === $s);
	}

	public static function isNotNull($s) {
		return (isset($s) && NULL !== $s && "" !== $s);
	}

	public static function isBoolean($value) {
		return \is_bool($value) || $value == 1 || $value == 0;
	}

	public static function isBooleanTrue($value) {
		return $value == 1 || $value;
	}

	public static function isBooleanFalse($value) {
		return $value == 0 || ! $value;
	}

	public static function camelCaseToSeparated($input, $separator = " ") {
		return strtolower(preg_replace('/(?<!^)[A-Z]/', $separator . '$0', $input));
	}

	public static function replaceAtFirst($subject, $from, $to) {
		$from = '/\A' . preg_quote($from, '/') . '/';
		return \preg_replace($from, $to, $subject, 1);
	}

	public static function replaceAtLast($subject, $from, $to) {
		$from = '/' . preg_quote($from, '/') . '\z/';
		return \preg_replace($from, $to, $subject, 1);
	}

	public static function replaceAtFirstAndLast($subject, $fromFirst, $toFirst, $fromLast, $toLast) {
		$s = self::replaceAtFirst($subject, $fromFirst, $toFirst);
		return self::replaceAtLast($s, $fromLast, $toLast);
	}

	public static function getValueBetween(&$str, $before = "{{", $after = "}}") {
		$matches = [];
		$result = null;
		$_before = \preg_quote($before);
		$_after = \preg_quote($after);
		if (\preg_match('/' . $_before . '(.*?)' . $_after . '/s', $str, $matches) === 1) {
			$result = $matches[1];
			$str = \str_replace($before . $result . $after, "", $str);
		}
		return $result;
	}

	public static function doubleBackSlashes($value) {
		if (\is_string($value))
			return \str_replace("\\", "\\\\", $value);
		return $value;
	}

	public static function cleanIdentifier($id) {
		return \preg_replace('/[^a-zA-Z0-9\-\_]/s', '', $id);
	}
}
