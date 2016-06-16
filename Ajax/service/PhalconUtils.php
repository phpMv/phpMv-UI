<?php

namespace Ajax\service;

use Phalcon\Text;
use Phalcon\Tag;

class PhalconUtils {

	public static function endsWith($str, $end, $ignoreCase=null) {
		return Text::endsWith($str, $end, $ignoreCase);
	}

	public static function startsWith($str, $start, $ignoreCase=null) {
		return Text::startsWith($str, $start, $ignoreCase);
	}

	public static function image($parameters=null, $local=null) {
		return Tag::image($parameters, $local);
	}

	public static function javascriptInclude($parameters=null, $local=null) {
		return Tag::javascriptInclude($parameters, $local);
	}

	public static function stylesheetLink($parameters=null, $local=null) {
		return Tag::stylesheetLink($parameters, $local);
	}
}