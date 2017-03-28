<?php

namespace Ajax\bootstrap\html;

use Ajax\JsUtils;
use Ajax\bootstrap\html\base\HtmlElementAsContent;
use Ajax\bootstrap\html\base\HtmlBsDoubleElement;
use Ajax\common\html\HtmlDoubleElement;

/**
 * Twitter Bootstrap Collapse component
 * @see http://getbootstrap.com/components/#collapse
 * @author jc
 * @version 1.001
 */
class HtmlCollapse extends HtmlElementAsContent {
	protected $collapse;

	public function __construct($element=NULL) {
		parent::__construct($element);
		$this->_template="%element%%collapse%";
		$this->element->setProperty("data-toogle", "collapse");
		$this->element->setProperty("aria-expanded", "false");
	}

	public function attachTo($identifier) {
		$this->element->setProperty("aria-controls", $identifier);
		if ($this->element->getTagName()==="a")
			$this->element->setProperty("href", "#".$identifier);
		else
			$this->element->setProperty("data-target", "#".$identifier);
	}

	public function getAttachedZone() {
		$id=$this->element->getProperty("aria-controls");
		if (!isset($id))
			if ($this->element->getTagName()==="a")
				$id=$this->element->getProperty("href");
		if (!isset($id)||$id==="#") {
			$id="collapse-".$this->element->getIdentifier();
			$this->attachTo($id);
		}
		$id=$this->cleanIdentifier($id);
		return $id;
	}

	public function setAttachedZone(HtmlDoubleElement $element) {
		$this->attachTo($element->getIdentifier());
		$this->collapse=$element;
	}

	public function createCollapsedZone($content="", $attachTo=NULL) {
		if (isset($attachTo))
			$this->attachTo($attachTo);
		$collapsedZone=new HtmlBsDoubleElement($this->getAttachedZone());
		$collapsedZone->setProperty("class", "collapse");
		$collapsedZone->setContent($content);
		return $collapsedZone;
	}

	public function addCollapsedZone($content="", $attachTo=NULL) {
		$this->collapse=$this->createCollapsedZone($content, $attachTo);
		return $this->collapse;
	}

	/*
	 * (non-PHPdoc)
	 * @see BaseHtml::run()
	 */
	public function run(JsUtils $js) {
		$this->_bsComponent=$js->bootstrap()->collapse("#".$this->element->getIdentifier());
		$this->addEventsOnRun($js);
		return $this->_bsComponent;
	}

	public function __toString() {
		return $this->compile();
	}
}
