<?php
namespace Ajax\semantic\html\modules;

use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\semantic\html\elements\HtmlSegment;
use Ajax\semantic\html\collections\menus\HtmlMenu;
use Ajax\semantic\html\base\constants\Side;
use Ajax\JsUtils;

class HtmlTab extends HtmlSemCollection{
	protected $params=array("debug"=>true);

	public function __construct( $identifier, $tabs=array()){
		parent::__construct( $identifier, "div", "");
		$menu=new HtmlMenu("menu".$this->identifier);
		$menu->asTab(false)->setAttachment(NULL,Side::TOP);
		$this->content["menu"]=$menu;
		$this->addItems($tabs);
	}

	protected function createItem($value){
		$count=$this->count();
		$title=$value;
		$content=NULL;
		if(\is_array($value)){
			$title=@$value[0];$content=@$value[1];
		}
		$menuItem=$this->content["menu"]->addItem($title);
		$menuItem->addToProperty("data-tab", $menuItem->getIdentifier());
		$menuItem->removeProperty("href");
		$segment=new HtmlSegment("item-".$this->identifier."-".$count, $content);
		$segment->setAttachment(NULL,Side::BOTTOM)->addToProperty("class", "tab")->addToProperty("data-tab",$menuItem->getIdentifier());
		return $segment;
	}

	public function activate($index){
		$this->content["menu"]->getItem($index)->setActive(true);
		$this->content[$index]->setActive(true);
		return $this;
	}

	public function addPanel($title,$content){
		return $this->addItem([$title,$content]);
	}

	/**
	 * render the content of $controller::$action and set the response to a new panel
	 * @param JsUtils $js
	 * @param string $title The panel title
	 * @param Controller $initialController
	 * @param string $controller a Phalcon controller
	 * @param string $action a Phalcon action
	 * @param array $params
	 */
	public function forwardPanel(JsUtils $js,$title,$initialController,$controller,$action,$params=array()){
		return $this->addPanel($title, $js->forward($initialController, $controller, $action,$params));
	}

	/**
	 * render the content of an existing view : $controller/$action and set the response to a new panel
	 * @param JsUtils $js
	 * @param string $title The panel title
	 * @param Controller $initialController
	 * @param string $viewName
	 * @param $params The parameters to pass to the view
	 */
	public function renderViewPanel(JsUtils $js,$title,$initialController, $viewName, $params=array()) {
		return $this->addPanel($title, $js->renderContent($initialController, $viewName,$params));
	}

	/*
	 * (non-PHPdoc)
	 * @see BaseHtml::run()
	 */
	public function run(JsUtils $js) {
		if(isset($this->_bsComponent)===false)
			$this->_bsComponent=$js->semantic()->tab("#".$this->identifier." .item",$this->params);
			$this->addEventsOnRun($js);
			return $this->_bsComponent;
	}
	public function compile(JsUtils $js=NULL, &$view=NULL) {
		if($this->content["menu"]->count()>0)
			$this->activate(0);
		return parent::compile($js,$view);
	}
}