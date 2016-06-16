<?php
namespace Ajax\common\html\html5;
use Ajax\common\html\HtmlDoubleElement;
/**
 * HTML Select
 * @author jc
 * @version 1.002
 */

class HtmlOption extends HtmlDoubleElement {
	protected $value;
	protected $selected;
	public function __construct($identifier,$caption,$value="") {
		parent::__construct($identifier, "option");
		$this->_template='<option id="%identifier%" value="%value%" %selected% %properties%>%content%</option>';
		$this->content=$caption;
		$this->value=$value;
		$this->selected="";
	}

	public function select(){
		$this->selected="selected";
		return $this;
	}

	public function getValue() {
		return $this->value;
	}
	public function setValue($value) {
		$this->value = $value;
		return $this;
	}

}
