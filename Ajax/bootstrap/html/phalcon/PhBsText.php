<?php

namespace Ajax\bootstrap\html\phalcon;

use Ajax\bootstrap\html\phalcon\PhBsElement;
use Ajax\bootstrap\html\HtmlInput;
use Phalcon\Forms\Element\Text;

/**
 *
 * @author jc
 *
 */
class PhBsText extends PhBsElement {

	public function __construct($name, array $attributes=null) {
		parent::__construct($name, $attributes);
		$this->renderer=new PhBsRenderer(new Text($name, $attributes), new HtmlInput($name));
	}
}