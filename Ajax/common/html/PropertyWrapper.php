<?php

namespace Ajax\common\html;

use Ajax\service\JArray;

class PropertyWrapper {

	public static function wrap($input, $js=NULL, $separator=' ', $valueQuote='"') {
		$output="";
		if (is_string($input)) {
			$output=$input;
		}
		if (is_array($input)) {
			if (sizeof($input) > 0) {
				if (self::containsElement($input) === false) {
					$output=self::wrapStrings($input, $js, $separator=' ', $valueQuote='"');
				} else {
					$output=self::wrapObjects($input, $js, $separator, $valueQuote);
				}
			}
		}
		return $output;
	}

	private static function containsElement($input) {
		foreach ( $input as $v ) {
			if (\is_object($v) === true || \is_array($v))
				return true;
		}
		return false;
	}

	public static function wrapStrings($input, $js, $separator=' ', $valueQuote='"') {
		if (JArray::isAssociative($input) === true) {
			$result=implode($separator, array_map(function ($v, $k) use($valueQuote) {
				return $k . '=' . $valueQuote . $v . $valueQuote;
			}, $input, array_keys($input)));
		} else {
			$result=implode($separator, array_values($input));
		}
		return $result;
	}

	public static function wrapObjects($input, $js=NULL, $separator=' ', $valueQuote='"') {
		return implode($separator, array_map(function ($v) use($js, $separator, $valueQuote) {
			if (is_object($v))
				return $v->compile($js);
			elseif (\is_array($v)) {
				return self::wrap($v, $js, $separator, $valueQuote);
			} else
				return $v;
		}, $input));
	}
}