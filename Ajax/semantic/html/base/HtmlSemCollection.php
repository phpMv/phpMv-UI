<?php

namespace Ajax\semantic\html\base;

use Ajax\common\html\HtmlCollection;
use Ajax\semantic\html\base\traits\BaseTrait;
use Ajax\JsUtils;
/**
 * Base class for Semantic Html collections
 * @author jc
 * @version 1.001
 */
abstract class HtmlSemCollection extends HtmlCollection{
	use BaseTrait;
	public function __construct( $identifier, $tagName="div",$baseClass=""){
		parent::__construct( $identifier, $tagName);
		$this->_baseClass=$baseClass;
		$this->setClass($baseClass);
	}

	public function run(JsUtils $js) {
		parent::run($js);
		$this->_bsComponent=$js->semantic()->generic("#".$this->identifier);
		$this->addEventsOnRun($js);
		return $this->_bsComponent;
	}
}
