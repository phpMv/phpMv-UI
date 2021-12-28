<?php
namespace Ajax\common\traits;

use Ajax\common\BaseGui;

trait JsUtilsInternalTrait {

	protected $jquery_code_for_compile = array();

	protected $jquery_code_for_compile_at_last = array();

	protected function _addToCompile($jsScript) {
		$this->jquery_code_for_compile[] = $jsScript;
	}

	/**
	 *
	 * @param BaseGui $library
	 * @param mixed $view
	 */
	protected function _compileLibrary(BaseGui $library, &$view = NULL) {
		if (isset($view))
			$library->compileHtml($this, $view);
		if ($library->isAutoCompile()) {
			$library->compile(true);
		}
	}

	protected function defer($script) {
		$result = "window.defer=function (method) {if (window.jQuery) method(); else setTimeout(function() { defer(method) }, 50);};";
		$result .= "window.defer(function(){" . $script . "})";
		return $result;
	}

	protected function ready($script) {
		$result = '$(document).ready(function() {' . "\n";
		$result .= $script . '})';
		return $result;
	}

	protected function minify($input) {
		if (trim($input) === "")
			return $input;
		$input = preg_replace(array(
			// Remove comment(s)
			'#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
			// Remove white-space(s) outside the string and regex
			'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
			// Remove the last semicolon
			// '#;+\}#',
			// Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
			'#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
			// --ibid. From `foo['bar']` to `foo.bar`
			'#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
		), array(
			'$1',
			'$1$2',
			// '}',
			'$1$3',
			'$1.$3'
		), $input);
		$input = str_replace("}$", "};$", $input);
		return $input;
	}

	/**
	 * Outputs an opening <script>
	 *
	 * @param string $src
	 * @return string
	 */
	protected function _open_script($src = '') {
		$str = '<script ';
		$str .= ($src == '') ? '>' : ' src="' . $src . '">';
		return $str;
	}

	/**
	 * Outputs an closing </script>
	 *
	 * @param string $extra
	 * @return string
	 */
	protected function _close_script($extra = "\n") {
		return "</script>$extra";
	}

	protected function conflict() {
		$this->_addToCompile("var btn = $.fn.button.noConflict();$.fn.btn = btn;");
	}

	public function addToCompile($jsScript) {
		$this->_addToCompile($jsScript);
	}
}
