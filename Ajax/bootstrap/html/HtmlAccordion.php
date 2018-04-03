<?php

namespace Ajax\bootstrap\html;

use Ajax\bootstrap\html\base\HtmlBsDoubleElement;
use Ajax\JsUtils;


/**
 * Composant Twitter Bootstrap Accordion
 * @see http://getbootstrap.com/javascript/#collapse-example-accordion
 * @author jc
 * @version 1.001
 */
class HtmlAccordion extends HtmlBsDoubleElement {

	public function __construct($identifier, $tagName="div") {
		parent::__construct($identifier, $tagName);
		$this->setClass("panel-group");
		$this->setRole("tablist");
		$this->setProperty("aria-multiselectable", "true");
		$this->content=array ();
	}

	public function addPanel($title, $content) {
		$nb=sizeof($this->content)+1;
		$panel=new HtmlPanel("panel-".$this->identifier."-".$nb);
		$link=new HtmlLink("lnk-panel-".$this->identifier."-".$nb);
		$link->setProperty("data-toggle", "collapse");
		$link->setProperty("data-parent", "#".$this->identifier);
		$link->setHref("#collapse-panel-".$this->identifier."-".$nb);
		$link->setContent($title);
		$panel->addHeader($link);
		$panel->setContent($content);
		$panel->setCollapsable(true);
		$this->content []=$panel;
		return $panel;
	}

	/**
	 * render the content of an existing view : $controller/$action and set the response to a new panel
	 * @param JsUtils $js
	 * @param string $title The panel title
	 * @param object $initialController
	 * @param string $viewName
	 * @param array $params The parameters to pass to the view
	 */
	public function renderViewPanel(JsUtils $js,$title,$initialController, $viewName, $params=array()) {
		return $this->addPanel($title, $js->renderContent($initialController, $viewName,$params));
	}

		/**
	 * render the content of $controller::$action and set the response to a new panel
	 * @param JsUtils $js
	 * @param string $title The panel title
	 * @param object $initialController
	 * @param string $controller a Phalcon controller
	 * @param string $action a Phalcon action
	 * @param array $params
	 */
	public function forwardPanel(JsUtils $js,$title,$initialController,$controller,$action,$params=array()){
		return $this->addPanel($title, $js->forward($initialController, $controller, $action,$params));
	}

	public function run(JsUtils $js) {
		foreach ( $this->content as $content ) {
			$content->run($js);
		}
	}

	public function getPanel($index) {
		if ($index<sizeof($this->content))
			return $this->content [$index];
	}
}
