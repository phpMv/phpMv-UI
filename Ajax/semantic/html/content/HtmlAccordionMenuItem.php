<?php

namespace Ajax\semantic\html\content;


class HtmlAccordionMenuItem extends HtmlMenuItem {
	public function __construct($identifier,$title,$content) {
		parent::__construct($identifier, new HtmlAccordionItem("accordion-".$identifier, $title,$content));
	}

	public function setActive($value=true){
		$this->content->setActive($value);
		return $this;
	}
}
