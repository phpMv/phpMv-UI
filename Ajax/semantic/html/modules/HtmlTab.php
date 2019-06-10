<?php
namespace Ajax\semantic\html\modules;

use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\semantic\html\elements\HtmlSegment;
use Ajax\semantic\html\collections\menus\HtmlMenu;
use Ajax\semantic\html\base\constants\Side;
use Ajax\JsUtils;
use Ajax\semantic\html\base\constants\Direction;
use Ajax\semantic\html\content\HtmlMenuItem;

/**
 * Semantic Tab component
 *
 * @see http://semantic-ui.com/collections/tab.html
 * @author jc
 * @version 1.02
 */
class HtmlTab extends HtmlSemCollection {

	protected $params = [];

	protected $_activated = false;

	public function __construct($identifier, $tabs = array()) {
		parent::__construct($identifier, "div", "");
		$menu = new HtmlMenu("menu" . $this->identifier);
		$menu->asTab(false)->setAttachment(NULL, Side::TOP);
		$this->content["menu"] = $menu;
		$this->addItems($tabs);
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Ajax\common\html\HtmlCollection::createItem()
	 * @return HtmlSegment
	 */
	protected function createItem($value) {
		$count = $this->count();
		$title = $value;
		$content = NULL;
		if (\is_array($value)) {
			$title = @$value[0];
			$content = @$value[1];
		}
		$menuItem = $this->content["menu"]->addItem($title);
		$menuItem->addToProperty("data-tab", $menuItem->getIdentifier());
		$menuItem->removeProperty("href");
		$result = $this->createSegment($count, $content, $menuItem->getIdentifier());
		$result->menuTab = $menuItem;
		return $result;
	}

	/**
	 *
	 * @param int $count
	 * @param string $content
	 * @param string $datatab
	 * @return \Ajax\semantic\html\elements\HtmlSegment
	 */
	private function createSegment($count, $content, $datatab) {
		$segment = new HtmlSegment("item-" . $this->identifier . "-" . $count, $content);
		$segment->setAttachment(NULL, Side::BOTTOM)
			->addToProperty("class", "tab")
			->addToProperty("data-tab", $datatab);
		return $segment;
	}

	/**
	 * Sets the content of the tab at position $index
	 *
	 * @param int $index
	 *        	index of the tab
	 * @param String $content
	 *        	new content
	 * @return \Ajax\semantic\html\modules\HtmlTab
	 */
	public function setTabContent($index, $content) {
		$menu = $this->content["menu"];
		if ($index < $menu->count()) {
			if (isset($this->content[$index]) === false) {
				$this->content[$index] = $this->createSegment($index, $content, $menu->getItem($index)
					->getIdentifier());
			} else
				$this->content[$index]->setContent($content);
		}
		return $this;
	}

	/**
	 * Sets all contents of tabs
	 *
	 * @param array $contents
	 * @return \Ajax\semantic\html\modules\HtmlTab
	 */
	public function setTabsContent($contents) {
		$size = \sizeof($contents);
		for ($i = 0; $i < $size; $i ++) {
			$this->setTabContent($i, $contents[$i]);
		}
		return $this;
	}

	/**
	 * Activates the tab element at $index
	 *
	 * @param int $index
	 * @return \Ajax\semantic\html\modules\HtmlTab
	 */
	public function activate($index) {
		$item = $this->content["menu"]->getItem($index);
		if ($item != null) {
			$item->setActive(true);
			$this->content[$index]->setActive(true);
			$this->_activated = true;
		}
		return $this;
	}

	/**
	 * Adds a new tab
	 *
	 * @param string $title
	 * @param string $content
	 * @return \Ajax\semantic\html\elements\HtmlSegment
	 */
	public function addTab($title, $content) {
		return $this->addItem([
			$title,
			$content
		]);
	}

	/**
	 * Renders the content of $controller::$action and sets the response to the tab at $index position
	 *
	 * @param int $index
	 * @param JsUtils $js
	 * @param string $title
	 *        	The panel title
	 * @param object $initialController
	 * @param string $controller
	 *        	a controller
	 * @param string $action
	 *        	an action
	 * @param array $params
	 * @return \Ajax\semantic\html\elements\HtmlSegment
	 */
	public function forwardTab($index, JsUtils $js, $title, $initialController, $controller, $action, $params = array()) {
		if (\array_key_exists($index, $this->content)) {
			$this->content[$index] = $js->forward($initialController, $controller, $action, $params);
			return $this->content[$index];
		}

		return $this->addAndForwardTab($js, $title, $initialController, $controller, $action, $params);
	}

	/**
	 * Renders the content of an existing view : $controller/$action and sets the response to the tab at $index position
	 *
	 * @param
	 *        	$index
	 * @param JsUtils $js
	 * @param string $title
	 *        	The panel title
	 * @param object $initialController
	 * @param string $viewName
	 * @param array $params
	 *        	The parameters to pass to the view
	 * @return \Ajax\semantic\html\elements\HtmlSegment
	 */
	public function renderViewTab($index, JsUtils $js, $title, $initialController, $viewName, $params = array()) {
		if (\array_key_exists($index, $this->content)) {
			$this->content[$index] = $js->renderContent($initialController, $viewName, $params);
			return $this->content[$index];
		}
		return $this->addAndRenderViewTab($js, $title, $initialController, $viewName, $params);
	}

	/**
	 * render the content of $controller::$action and set the response to a new tab
	 *
	 * @param JsUtils $js
	 * @param string $title
	 *        	The panel title
	 * @param object $initialController
	 * @param string $controller
	 *        	a controller
	 * @param string $action
	 *        	an action
	 * @param array $params
	 * @return \Ajax\semantic\html\elements\HtmlSegment
	 */
	public function addAndForwardTab(JsUtils $js, $title, $initialController, $controller, $action, $params = array()) {
		\ob_start();
		$js->forward($initialController, $controller, $action, $params);
		$content = \ob_get_clean();
		return $this->addTab($title, $content);
	}

	/**
	 * render the content of an existing view : $controller/$action and set the response to a new tab
	 *
	 * @param JsUtils $js
	 * @param string $title
	 *        	The panel title
	 * @param object $initialController
	 * @param string $viewName
	 * @param array $params
	 *        	The parameters to pass to the view
	 * @return \Ajax\semantic\html\elements\HtmlSegment
	 */
	public function addAndRenderViewTab(JsUtils $js, $title, $initialController, $viewName, $params = array()) {
		return $this->addTab($title, $js->renderContent($initialController, $viewName, $params));
	}

	public function setPointing($value = Direction::NONE) {
		return $this->content["menu"]->setPointing($value);
	}

	public function setSecondary() {
		return $this->content["menu"]->setSecondary();
	}

	/**
	 * Returns the menu item at position $index
	 *
	 * @param int $index
	 * @return HtmlMenuItem
	 */
	public function getMenuTab($index) {
		return $this->content["menu"]->getItem($index);
	}

	/**
	 * Returns the tab at position $index
	 *
	 * @param int $index
	 * @return HtmlSegment
	 */
	public function getTab($index) {
		return $this->content[$index];
	}

	/**
	 * Sets the menu of tabs
	 *
	 * @param HtmlMenu $menu
	 * @return \Ajax\semantic\html\modules\HtmlTab
	 */
	public function setMenu($menu) {
		$contentSize = \sizeof($this->content);
		for ($i = 0; $i < $contentSize; $i ++) {
			if ($menu->getItem($i) !== NULL) {
				if (isset($this->content[$i])) {
					$menu->getItem($i)->addToProperty("data-tab", $this->content[$i]->getProperty("data-tab"));
				}
			}
		}
		$menuSize = $menu->count();
		for ($i = 0; $i < $menuSize; $i ++) {
			$menu->getItem($i)->removeProperty("href");
			if (isset($this->content[$i]) === false) {
				$this->content[$i] = $this->createSegment($i, "New content", $menu->getItem($i)
					->getIdentifier());
			}
			$menu->getItem($i)->addToProperty("data-tab", $this->content[$i]->getProperty("data-tab"));
		}

		$this->content["menu"] = $menu;
		return $this;
	}

	/*
	 * (non-PHPdoc)
	 * @see BaseHtml::run()
	 */
	public function run(JsUtils $js) {
		if (isset($this->_bsComponent) === false)
			$this->_bsComponent = $js->semantic()->tab("#" . $this->identifier . " .item", $this->params);
		$this->addEventsOnRun($js);
		return $this->_bsComponent;
	}

	public function compile(JsUtils $js = NULL, &$view = NULL) {
		if (! $this->_activated && $this->content["menu"]->count() > 0 && \sizeof($this->content) > 1)
			$this->activate(0);
		return parent::compile($js, $view);
	}
}
