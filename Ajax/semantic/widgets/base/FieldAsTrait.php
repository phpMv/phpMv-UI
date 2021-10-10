<?php
namespace Ajax\semantic\widgets\base;

use Ajax\semantic\widgets\datatable\DataTable;
use Ajax\service\JString;
use Ajax\semantic\html\elements\HtmlImage;
use Ajax\semantic\html\base\constants\Size;
use Ajax\semantic\html\elements\HtmlLabel;
use Ajax\semantic\html\modules\HtmlProgress;
use Ajax\semantic\html\modules\HtmlRating;
use Ajax\semantic\html\elements\HtmlHeader;
use Ajax\semantic\html\collections\form\HtmlFormCheckbox;
use Ajax\semantic\html\collections\form\HtmlFormInput;
use Ajax\semantic\html\collections\form\HtmlFormDropdown;
use Ajax\semantic\html\collections\form\HtmlFormTextarea;
use Ajax\semantic\html\collections\form\HtmlFormFields;
use Ajax\semantic\html\collections\HtmlMessage;
use Ajax\semantic\html\elements\HtmlButton;
use Ajax\service\JArray;
use Ajax\semantic\html\elements\html5\HtmlLink;
use Ajax\semantic\html\elements\HtmlFlag;
use Ajax\common\html\BaseHtml;
use Ajax\semantic\html\collections\form\HtmlFormField;
use Ajax\semantic\html\collections\form\HtmlFormRadio;
use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\elements\HtmlIcon;
use Ajax\semantic\html\elements\HtmlList;

/**
 * trait used in Widget
 *
 * @author jc
 * @property InstanceViewer $_instanceViewer
 * @property boolean $_edition
 * @property mixed $_modelInstance
 * @property boolean $_hasRules
 */
trait FieldAsTrait {

	abstract protected function _getFieldIdentifier($prefix, $name = "");

	abstract public function setValueFunction($index, $callback);

	abstract protected function _getFieldName($index);

	abstract protected function _getFieldCaption($index);

	abstract protected function _buttonAsSubmit(BaseHtml &$button, $event, $url, $responseElement = NULL, $parameters = NULL);

	private $_speProperties;

	/**
	 *
	 * @param HtmlFormField $element
	 * @param array $attributes
	 */
	protected function _applyAttributes(BaseHtml $element, &$attributes, $index, $instance = null) {
		if (isset($attributes["jsCallback"])) {
			$callback = $attributes["jsCallback"];
			if (\is_callable($callback)) {
				$callback($element, $instance, $index, InstanceViewer::$index);
			}
		}
		unset($attributes["rules"]);
		unset($attributes["ajax"]);
		unset($attributes["visibleHover"]);
		$element->fromArray($attributes);
	}

	private function _getLabelField($caption, $icon = NULL) {
		$label = new HtmlLabel($this->_getFieldIdentifier("lbl"), $caption, $icon);
		return $label;
	}

	protected function _addRules(HtmlFormField $element, &$attributes) {
		if (isset($attributes["rules"])) {
			$this->_hasRules = true;
			$rules = $attributes["rules"];
			if (\is_array($rules)) {
				$element->addRules($rules);
			} else {
				$element->addRule($rules);
			}
			unset($attributes["rules"]);
		}
	}

	protected function _prepareFormFields(HtmlFormField &$field, $name, &$attributes) {
		$field->setName($name);
		$this->_addRules($field, $attributes);
		return $field;
	}

	protected function _fieldAs($elementCallback, &$index, $attributes = NULL, $prefix = null) {
		$this->setValueFunction($index, function ($value, $instance, $index, $rowIndex) use (&$attributes, $elementCallback, $prefix) {
			$caption = $this->_getFieldCaption($index);
			$name = $this->_getFieldName($index);
			$id = $this->_getFieldIdentifier($prefix, $name);
			if (isset($attributes["name"])) {
				$name = $attributes["name"];
				unset($attributes["name"]);
			}
			$element = $elementCallback($id, $name, $value, $caption);
			if (isset($this->_speProperties[$index])) {
				$attributes ??= [];
				$attributes += $this->_speProperties[$index];
			}
			if (\is_array($attributes)) {
				$this->_applyAttributes($element, $attributes, $index, $instance);
			}
			$element->setDisabled(! $this->_edition);
			return $element;
		});
		return $this;
	}

	/**
	 * Defines the values for the fields for a property (or html attribute).
	 *
	 * @param int|string $property
	 *        	the property to update
	 * @param array $indexValues
	 *        	array of field=>value
	 */
	public function setPropertyValues($property, $indexValues) {
		foreach ($indexValues as $index => $value) {
			$ind = $this->_getIndex($index);
			$this->_speProperties[$ind][$property] = $value;
		}
	}

	public function fieldAsProgress($index, $label = NULL, $attributes = array()) {
		$this->setValueFunction($index, function ($value) use ($label, $attributes) {
			$pb = new HtmlProgress($this->_getFieldIdentifier("pb"), $value, $label, $attributes);
			return $pb;
		});
		return $this;
	}

	public function fieldAsRating($index, $max = 5, $icon = "") {
		$this->setValueFunction($index, function ($value) use ($max, $icon) {
			$rating = new HtmlRating($this->_getFieldIdentifier("rat"), $value, $max, $icon);
			return $rating;
		});
		return $this;
	}

	public function fieldAsLabel($index, $icon = NULL, $attributes = NULL) {
		return $this->_fieldAs(function ($id, $name, $value) use ($icon) {
			$lbl = new HtmlLabel($id, $value);
			if (isset($icon))
				$lbl->addIcon($icon);
			return $lbl;
		}, $index, $attributes, "label");
	}

	public function fieldAsHeader($index, $niveau = 1, $icon = NULL, $attributes = NULL) {
		return $this->_fieldAs(function ($id, $name, $value) use ($niveau, $icon) {
			$header = new HtmlHeader($id, $niveau, $value);
			if (isset($icon))
				$header->asIcon($icon, $value);
			return $header;
		}, $index, $attributes, "header");
	}

	public function fieldAsImage($index, $size = Size::MINI, $circular = false) {
		$this->setValueFunction($index, function ($img) use ($size, $circular) {
			$image = new HtmlImage($this->_getFieldIdentifier("image"), $img);
			$image->setSize($size);
			if ($circular)
				$image->setCircular();
			return $image;
		});
		return $this;
	}

	public function fieldAsFlag($index) {
		$this->setValueFunction($index, function ($flag) {
			$flag = new HtmlFlag($this->_getFieldIdentifier("flag"), $flag);
			return $flag;
		});
		return $this;
	}

	public function fieldAsIcon($index) {
		$this->setValueFunction($index, function ($icon) {
			$icon = new HtmlIcon($this->_getFieldIdentifier("icon"), $icon);
			return $icon;
		});
		return $this;
	}

	public function fieldAsAvatar($index, $attributes = NULL) {
		return $this->_fieldAs(function ($id, $name, $value) {
			$img = new HtmlImage($id, $value);
			$img->asAvatar();
			return $img;
		}, $index, $attributes, "avatar");
	}

	public function fieldAsRadio($index, $attributes = NULL) {
		return $this->_fieldAs(function ($id, $name, $value) use ($attributes) {
			$input = new HtmlFormRadio($id, $name, $value, $value);
			return $this->_prepareFormFields($input, $name, $attributes);
		}, $index, $attributes, "radio");
	}

	public function fieldAsRadios($index, $elements = [], $attributes = NULL) {
		return $this->_fieldAs(function ($id, $name, $value, $caption) use ($elements) {
			return HtmlFormFields::radios($name, $elements, $caption, $value);
		}, $index, $attributes, "radios");
	}

	public function fieldAsList($index, $classNames = "", $attributes = NULL) {
		return $this->_fieldAs(function ($id, $name, $value, $caption) use ($classNames) {
			$result = new HtmlList($name, $value);
			$result->addClass($classNames);
			return $result;
		}, $index, $attributes, "list");
	}

	public function fieldAsInput($index, $attributes = NULL) {
		return $this->_fieldAs(function ($id, $name, $value, $caption) use ($attributes) {
			$input = new HtmlFormInput($id, $caption, "text", $value);
			return $this->_prepareFormFields($input, $name, $attributes);
		}, $index, $attributes, "input");
	}

	public function fieldAsLabeledInput($index, $attributes = NULL) {
		return $this->_fieldAs(function ($id, $name, $value, $caption) use ($attributes) {
			$input = new HtmlFormInput($id, '', 'text', $value, $caption);
			$required = '';
			if (isset($attributes['rules'])) {
				$rules = json_encode($attributes['rules']);
				if (strpos($rules, 'empty') !== false) {
					$required = 'required';
				}
			}
			$input->getField()
				->labeled($caption)
				->setTagName('label')
				->addClass($required);
			return $this->_prepareFormFields($input, $name, $attributes);
		}, $index, $attributes, 'input');
	}

	public function fieldAsDataList($index, ?array $items = [], $attributes = NULL) {
		return $this->_fieldAs(function ($id, $name, $value, $caption) use ($attributes, $items) {
			$input = new HtmlFormInput($id, $caption, "text", $value);
			$input->getField()
				->addDataList($items);
			return $this->_prepareFormFields($input, $name, $attributes);
		}, $index, $attributes, "input");
	}

	public function fieldAsFile($index, $attributes = NULL) {
		if (isset($this->_form)) {
			$this->_form->setProperty('enctype', 'multipart/form-data');
		}
		return $this->_fieldAs(function ($id, $name, $value, $caption) use ($attributes) {
			$input = new HtmlFormInput($id, $caption);
			$input->asFile();
			return $this->_prepareFormFields($input, $name, $attributes);
		}, $index, $attributes, "input");
	}

	public function fieldAsTextarea($index, $attributes = NULL) {
		return $this->_fieldAs(function ($id, $name, $value, $caption) use ($attributes) {
			$textarea = new HtmlFormTextarea($id, $caption, $value);
			return $this->_prepareFormFields($textarea, $name, $attributes);
		}, $index, $attributes, "textarea");
	}

	public function fieldAsElement($index, $tagName = "div", $baseClass = "", $attributes = NULL) {
		return $this->_fieldAs(function ($id, $name, $value, $caption) use ($attributes, $tagName, $baseClass) {
			$div = new HtmlSemDoubleElement($id, $tagName, $baseClass);
			$div->setContent(\htmlentities($value));
			$textarea = new HtmlFormField("field-" . $id, $div, $caption);
			return $this->_prepareFormFields($textarea, $name, $attributes);
		}, $index, $attributes, "element");
	}

	public function fieldAsHidden($index, $attributes = NULL) {
		if (! \is_array($attributes)) {
			$attributes = [];
		}
		$attributes["inputType"] = "hidden";
		$attributes["style"] = "display:none;";
		return $this->fieldAsInput($index, $attributes);
	}

	public function fieldAsCheckbox($index, $attributes = NULL) {
		return $this->_fieldAs(function ($id, $name, $value, $caption) use ($attributes) {
			if ($caption === null || $caption === "")
				$caption = "";
			$input = new HtmlFormCheckbox($id, $caption, $this->_instanceViewer->getIdentifier());
			$input->setChecked(JString::isBooleanTrue($value));
			return $this->_prepareFormFields($input, $name, $attributes);
		}, $index, $attributes, "ck");
	}

	public function fieldAsDropDown($index, $elements = [], $multiple = false, $attributes = NULL) {
		return $this->_fieldAs(function ($id, $name, $value, $caption) use ($elements, $multiple, $attributes) {
			$dd = new HtmlFormDropdown($id, $elements, $caption, $value ?? '');
			$dd->asSelect($name, $multiple);
			return $this->_prepareFormFields($dd, $name, $attributes);
		}, $index, $attributes, "dd");
	}

	public function fieldAsMessage($index, $attributes = NULL) {
		return $this->_fieldAs(function ($id, $name, $value, $caption) {
			$mess = new HtmlMessage("message-" . $id, $caption);
			$mess->addHeader($value);
			return $mess;
		}, $index, $attributes, "message");
	}

	public function fieldAsLink($index, $attributes = NULL) {
		return $this->_fieldAs(function ($id, $name, $value, $caption) {
			$lnk = new HtmlLink("message-" . $id, "#", $caption);
			return $lnk;
		}, $index, $attributes, "link");
	}

	/**
	 * Change fields type
	 *
	 * @param array $types
	 *        	an array or associative array $type=>$attributes
	 */
	public function fieldsAs(array $types) {
		$i = 0;
		if (JArray::isAssociative($types)) {
			foreach ($types as $type => $attributes) {
				if (\is_int($type))
					$this->fieldAs($i ++, $attributes, []);
				else {
					$type = preg_replace('/\d/', '', $type);
					$this->fieldAs($i ++, $type, $attributes);
				}
			}
		} else {
			foreach ($types as $type) {
				$this->fieldAs($i ++, $type);
			}
		}
	}

	public function fieldAs($index, $type, $attributes = NULL) {
		$method = "fieldAs" . \ucfirst($type);
		if (\method_exists($this, $method)) {
			if (! \is_array($attributes)) {
				$attributes = [
					$index
				];
			} else {
				\array_unshift($attributes, $index);
			}
			\call_user_func_array([
				$this,
				$method
			], $attributes);
		}
	}

	public function fieldAsSubmit($index, $cssStyle = NULL, $url = NULL, $responseElement = NULL, $attributes = NULL) {
		return $this->_fieldAs(function ($id, $name, $value, $caption) use ($url, $responseElement, $cssStyle, $attributes) {
			$button = new HtmlButton($id, $caption, $cssStyle);
			$this->_buttonAsSubmit($button, "click", $url, $responseElement, $attributes["ajax"] ?? []);
			return $button;
		}, $index, $attributes, "submit");
	}

	public function fieldAsButton($index, $cssStyle = NULL, $attributes = NULL) {
		return $this->_fieldAs(function ($id, $name, $value, $caption) use ($cssStyle) {
			$button = new HtmlButton($id, $value, $cssStyle);
			return $button;
		}, $index, $attributes, "button");
	}

	public function fieldAsDataTable($index, $model, $instances = null, $fields = [], $attributes = NULL) {
		return $this->_fieldAs(function ($id, $name, $value, $caption) use ($model, $instances, $fields, $index) {
			$dt = new DataTable($id, $model, $instances);
			$dt->setNamePrefix($index);
			$dt->setFields($fields);
			$dt->setEdition(true);
			$dt->addDeleteButton(false, [], function ($bt) use ($index) {
				$bt->addClass('mini circular')
					->wrap('<input value="" class="_status" type="hidden" name="' . $index . '._status[]">');
			});
			if ($caption != null) {
				$dt->setFormCaption($caption);
			}
			$dt->onPreCompile(function () use (&$dt) {
				$dt->getHtmlComponent()
					->colRightFromRight(0);
			});
			$dt->wrap('<input type="hidden" name="' . $index . '">');
			return $dt;
		}, $index, $attributes, "dataTable");
	}
}
