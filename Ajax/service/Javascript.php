<?php
namespace Ajax\service;

class Javascript {

	public static $preventDefault = "\nif(event && event.preventDefault) event.preventDefault();\n";

	public static $stopPropagation = "\nif(event && event.stopPropagation) event.stopPropagation();\n";

	public static function draggable($attr = "id") {
		return 'var dt=event.dataTransfer || event.originalEvent.dataTransfer;dt.setData("text/plain",JSON.stringify({id:$(event.target).attr("id"),data:$(event.target).attr("' . $attr . '")}));';
	}

	public static function dropZone($jqueryDone, $jsCallback = "") {
		$done = ($jqueryDone != null) ? '$(event.target).' . $jqueryDone . '($("#"+_data.id));' : '';
		return 'var dt=event.dataTransfer || event.originalEvent.dataTransfer;var _data=JSON.parse(dt.getData("text/plain"));' . $done . 'var data=_data.data;' . $jsCallback;
	}

	public static function fileDropZone($jsCallback = "") {
		$done = 'event.target.upload=formData;$(event.target).trigger("upload");';
		return 'var dt=event.dataTransfer || event.originalEvent.dataTransfer;var files=dt.files;var formData = new FormData();for (var i = 0; i < files.length; i++) {formData.append("file-"+i,files[i]);}' . $done . $jsCallback;
	}

	public static function containsCode($expression) {
		return \strrpos($expression, 'this') !== false || \strrpos($expression, 'event') !== false || \strrpos($expression, 'self') !== false;
	}

	public static function isFunction($jsCode) {
		return JString::startswith($jsCode, "function");
	}

	public static function fileUploadBehavior($id = '') {
		return "$('input:text, .ui.button', '#{$id}').on('click', function (e) {e.preventDefault();\$('input:file', '#{$id}').click();});
				$('input:file', '#{$id}').on('change', function (e) {if(e.target.files.length){var name = e.target.files[0].name;$('input:text', $(e.target).parent()).val(name);}});";
	}

	/**
	 * Puts HTML element in quotes for use in jQuery code
	 * unless the supplied element is the Javascript 'this'
	 * object, in which case no quotes are added
	 *
	 * @param string $element
	 * @return string
	 */
	public static function prep_element($element) {
		if (self::containsCode($element) === false) {
			$element = '"' . addslashes($element) . '"';
		}
		return $element;
	}

	/**
	 * Puts HTML values in quotes for use in jQuery code
	 * unless the supplied value contains the Javascript 'this' or 'event'
	 * object, in which case no quotes are added
	 *
	 * @param string $value
	 * @return string
	 */
	public static function prep_value($value) {
		if (\is_array($value)) {
			$value = implode(",", $value);
		}
		if (self::containsCode($value) === false) {
			$value = \str_replace([
				"\\",
				"\""
			], [
				"\\\\",
				"\\\""
			], $value);
			$value = '"' . $value . '"';
		}
		return trim($value, "%");
	}

	public static function prep_jquery_selector($value) {
		if (JString::startswith($value, '$(') === false) {
			return '$(' . self::prep_value($value) . ')';
		}
		return $value;
	}
}
