<?php

namespace Ajax\bootstrap\html;

use Ajax\bootstrap\html\base\HtmlBsDoubleElement;
use Ajax\bootstrap\html\base\CssRef;
use Ajax\JsUtils;
use Ajax\service\JString;

/**
 * Composant Twitter Bootstrap panel
 * @see http://getbootstrap.com/components/#panels
 * @author jc
 * @version 1.001
 */
class HtmlPanel extends HtmlBsDoubleElement {
	protected $header;
	protected $footer;
	protected $_collapsable;
	protected $collapseBegin;
	protected $collapseEnd;
	protected $_showOnStartup;

	public function __construct($identifier, $content=NULL, $header=NULL, $footer=NULL) {
		parent::__construct($identifier, "div");
		$this->_template=include 'templates/tplPanel.php';
		$this->setProperty("class", "panel panel-default");
		$this->_collapsable=false;
		$this->_showOnStartup=false;
		if ($content!==NULL) {
			$this->setContent($content);
		}
		if ($header!==NULL) {
			$this->addHeader($header);
		}
		if ($footer!==NULL) {
			$this->addFooter($footer);
		}
	}

	public function getHeader() {
		return $this->header;
	}

	public function setHeader($header) {
		$this->header=$header;
		return $this;
	}

	public function getFooter() {
		return $this->footer;
	}

	public function setFooter($footer) {
		$this->footer=$footer;
		return $this;
	}

	public function addHeader($content) {
		$header=new HtmlBsDoubleElement("header-".$this->identifier);
		$header->setTagName("div");
		$header->setClass("panel-heading");
		$header->setContent($content);
		$this->header=$header;
		return $header;
	}

	public function addHeaderH($content, $niveau="1") {
		$headerH=new HtmlBsDoubleElement("header-h-".$this->identifier);
		$headerH->setContent($content);
		$headerH->setTagName("h".$niveau);
		$headerH->setClass("panel-title");
		return $this->addHeader($headerH);
	}

	public function addFooter($content) {
		$footer=new HtmlBsDoubleElement("footer-".$this->identifier);
		$footer->setTagName("div");
		$footer->setClass("panel-footer");
		$footer->setContent($content);
		$this->footer=$footer;
		return $this;
	}

	/**
	 * define the Panel style
	 * avaible values : "panel-default","panel-primary","panel-success","panel-info","panel-warning","panel-danger"
	 * @param string|int $cssStyle
	 * @return \Ajax\bootstrap\html\HtmlPanel default : "panel-default"
	 */
	public function setStyle($cssStyle) {
		if (!JString::startsWith($cssStyle, "panel"))
			$cssStyle="panel".$cssStyle;
		return $this->addToPropertyCtrl("class", $cssStyle, CssRef::Styles("panel"));
	}

	/*
	 * (non-PHPdoc)
	 * @see BaseHtml::run()
	 */
	public function run(JsUtils $js) {
		if ($this->_collapsable) {
			$this->_bsComponent=$js->bootstrap()->collapse("#lnk-".$this->identifier);
			$this->_bsComponent->setCollapsed("#collapse-".$this->identifier);
			if ($this->_showOnStartup===true) {
				$this->_bsComponent->show();
			}
		}
		return $this->_bsComponent;
	}

	public function setCollapsable($_collapsable) {
		$this->_collapsable=$_collapsable;
		if ($_collapsable) {
			$this->header->setRole("tab");
			$lnk=new HtmlLink("lnk-".$this->identifier);
			$lnk->setHref("#collapse-".$this->identifier);
			$lnk->setContent($this->header->getContent());
			$this->header->setContent($lnk);
			$this->collapseBegin='<div id="collapse-'.$this->identifier.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="header-'.$this->identifier.'">';
			$this->collapseEnd="</div>";
		} else {
			$this->collapseBegin="";
			$this->collapseEnd="";
		}
		return $this;
	}

	/**
	 * Shows the panel body on startup if panel is collapsable.
	 * @param boolean $value
	 * @return $this default : false
	 */
	public function show($value) {
		if ($this->_collapsable)
			$this->_showOnStartup=$value;
	}
}
