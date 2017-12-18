<?php

namespace Ajax\common\html;

use Ajax\service\JArray;

class PropertyWrapper {

	public static function wrap($input, $js=NULL, $separator=' ', $valueQuote='"') {
		if (is_string($input)) {
			return $input;
		}
		$output="";
		if (\is_array($input)) {
			if (sizeof($input) > 0) {
				if (self::containsElement($input) === false) {
					$output=self::wrapStrings($input, $separator=' ', $valueQuote='"');
				} else {
					$output=self::wrapObjects($input, $js, $separator, $valueQuote);
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

	public static function wrapObjects($input, $js=NULL, $separator=' ', $valueQuote='"') {
		return implode($separator, array_map(function ($v) use($js, $separator, $valueQuote) {
			if(\is_string($v)){
				return $v;
			}
			if ($v instanceof BaseHtml){
				return $v->compile($js);
			}
			if (\is_array($v)) {
				return self::wrap($v, $js, $separator, $valueQuote);
			}
			if(!\is_callable($v)){
				return $v;
			}
		}, $input));
		/*$result='';
		foreach ($input as $value) {
			if($result!==''){
				$result.=$separator;
			}
			if(\is_string($value)){
				$result.=$value;
			}else{
				$result.=self::wrapValue($value,$js,$separator,$valueQuote);
			}
		}
		return $result;*/
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
