<?php

namespace Ajax\bootstrap\html;

use Ajax\bootstrap\html\HtmlDropdown;
use Ajax\bootstrap\html\base\CssRef;
use Ajax\JsUtils;

/**
 * Twitter Bootstrap HTML Splitbutton component
 * @author jc
 * @version 1.001
 */
class HtmlSplitbutton extends HtmlDropdown {

	public function __construct($identifier, $value="&nbsp;", $items=array(), $cssStyle="btn-default", $onClick=null) {
		parent::__construct($identifier, $value, $items, $cssStyle, $onClick);
		$this->asButton($cssStyle);
		$this->_template=include 'templates/tplSplitbutton.php';
		$this->mClass="btn-group";
	}

	/**
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\HtmlDropdown::setSize()
	 * @return HtmlSplitbutton
	 */
	public function setSize($size) {
		if (is_int($size)) {
			return $this->addToMember($this->mClass, CssRef::sizes("btn-group")[$size]);
		}
		return $this->addToMemberCtrl($this->mClass, $size, CssRef::sizes("btn-group"));
	}

	public function onButtonClick($jsCode) {
		$this->addEvent("buttonClick", $jsCode);
	}

	/*
	 * (non-PHPdoc)
	 * @see BaseHtml::run()
	 */
	public function run(JsUtils $js) {
		if ($this->getProperty("role")==="nav") {
			foreach ( $this->items as $dropdownItem ) {
				$dropdownItem->runNav($js);
			}
		}
		$this->_bsComponent=$js->bootstrap()->splitbutton("#".$this->identifier);
		$this->addEventsOnRun($js);
		return $this->_bsComponent;
	}
}
