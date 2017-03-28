<?php

namespace Ajax\bootstrap\components;

use Ajax\JsUtils;
use Ajax\common\components\SimpleExtComponent;

/**
 * Composant Twitter Bootstrap Tab
 * @author jc
 * @version 1.001
 */
class Tabs extends SimpleExtComponent {
	/**
	 *
	 * @var array of Tab
	 */
	protected $tabs;

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->tabs=array ();
	}

	public function getTabs() {
		return $this->tabs;
	}

	public function setTabs(array $tabs) {
		$this->tabs=$tabs;
		return $this;
	}

	public function addTab($tab) {
		$this->tabs []=$tab;
	}

	public function getTab($index) {
		if ($index>0&&$index<sizeof($this->tabs))
			return $this->tabs [$index];
	}

	public function show($index) {
		$this->tabs [$index]->show();
	}

	/**
	 * This event fires on tab show, but before the new tab has been shown.
	 * Use event.target and event.relatedTarget to target the active tab and the previous active tab (if available) respectively.
	 * @param int $index tab index
	 * @param string $jsCode
	 * @return $this
	 */
	public function onShow($index, $jsCode) {
		$tab=$this->getTab($index);
		if (isset($tab))
			return $tab->onShow($jsCode);
	}

	/**
	 * This event fires on tab show after a tab has been shown.
	 * Use event.target and event.relatedTarget to target the active tab and the previous active tab (if available) respectively.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onShown($index, $jsCode) {
		$tab=$this->getTab($index);
		if (isset($tab))
			return $tab->onShown($jsCode);
	}

	/**
	 * This event fires when a new tab is to be shown (and thus the previous active tab is to be hidden).
	 * Use event.target and event.relatedTarget to target the current active tab and the new soon-to-be-active tab, respectively.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onHide($index, $jsCode) {
		$tab=$this->getTab($index);
		if (isset($tab))
			return $tab->onShow($jsCode);
	}

	/**
	 * This event fires after a new tab is shown (and thus the previous active tab is hidden).
	 * Use event.target and event.relatedTarget to target the previous active tab and the new active tab, respectively.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onHidden($index, $jsCode) {
		$tab=$this->getTab($index);
		if (isset($tab))
			return $tab->onShow($jsCode);
	}
}
