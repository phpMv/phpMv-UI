<?php

namespace Ajax\common\html;

use Ajax\service\JArray;

class PropertyWrapper {

	public static function wrap($input, $js=NULL, $view=null, $separator=' ', $valueQuote='"') {
		if (is_string($input)) {
			return $input;
		}
		$output="";
		if (\is_array($input)) {
			if (sizeof($input) > 0) {
				if (self::containsElement($input) === false) {
					$output=self::wrapStrings($input, $separator=' ', $valueQuote='"');
				} else {
					$output=self::wrapObjects($input, $js, $view, $separator, $valueQuote);
				}
			}
		}
		return $output;
	}

	private static function containsElement($input) {
		foreach ( $input as $v ) {
			if (\is_object($v) || \is_array($v))
				return true;
		}
		return false;
	}

	public static function wrapStrings($input, $separator=' ', $valueQuote='"') {
		if (JArray::isAssociative($input) === true) {
			$result=implode($separator, array_map(function ($v, $k) use($valueQuote) {
				return $k . '=' . $valueQuote . $v . $valueQuote;
			}, $input, array_keys($input)));
		} else {
			$result=implode($separator, $input);
		}
		return $result;
	}

	public static function wrapObjects($input, $js=NULL, $view=null, $separator=' ', $valueQuote='"') {
		return implode($separator, array_map(function ($v) use($js, $view,$separator, $valueQuote) {
			if(\is_string($v)){
				return $v;
			}
			if ($v instanceof BaseHtml){
				return $v->compile($js,$view);
			}
			if (\is_array($v)) {
				return self::wrap($v, $js, $view,$separator, $valueQuote);
			}
			if(!\is_callable($v)){
				return $v;
			}
		}, $input));
	}

	protected static function wrapValue($value,$js=NULL, $separator=' ', $valueQuote='"'){
		if (\is_array($value)) {
			return self::wrap($value, $js, $separator, $valueQuote);
		}
		if ($value instanceof BaseHtml){
			return $value->compile($js);
		}
		if(!\is_callable($value)){
			return $value;
		}
		return '';
	}
}
