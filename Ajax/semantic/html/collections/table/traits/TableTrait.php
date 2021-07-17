<?php
namespace Ajax\semantic\html\collections\table\traits;

use Ajax\JsUtils;

/**
 *
 * @author jc
 * @property HtmlTable $_self
 */
trait TableTrait {

	abstract public function addEvent($event, $jsCode, $stopPropagation = false, $preventDefault = false);

	abstract public function getOn($event, $url, $responseElement = "", $parameters = array());

	protected function addToPropertyTable($property, $value) {
		return $this->_self->addToProperty($property, $value);
	}

	public function setCelled() {
		return $this->addToPropertyTable("class", "celled");
	}

	public function setBasic($very = false) {
		$table = $this->_self;
		if ($very)
			$table->addToPropertyCtrl("class", "very", array(
				"very"
			));
		return $table->addToPropertyCtrl("class", "basic", array(
			"basic"
		));
	}

	public function setCompact($very = false) {
		$table = $this->_self;
		if ($very)
			$table->addToPropertyCtrl("class", "very", array(
				"very"
			));
		return $table->addToPropertyCtrl("class", "compact", array(
			"compact"
		));
	}

	public function setCollapsing() {
		return $this->addToPropertyTable("class", "collapsing");
	}

	public function setDefinition() {
		return $this->addToPropertyTable("class", "definition");
	}

	public function setStructured() {
		return $this->addToPropertyTable("class", "structured");
	}

	public function setSortable($colIndex = NULL) {
		$table = $this->_self;
		if (isset($colIndex) && $table->hasPart("thead")) {
			$table->getHeader()->sort($colIndex);
		}
		return $table->addToProperty("class", "sortable");
	}

	public function setSingleLine() {
		return $this->addToPropertyTable("class", "single line");
	}

	public function setFixed() {
		return $this->addToPropertyTable("class", "fixed");
	}

	public function setSelectable() {
		return $this->addToPropertyTable("class", "selectable");
	}

	public function setStriped() {
		return $this->addToPropertyTable("class", "striped");
	}

	public function onRowClick($jsCode, $stopPropagation = false, $preventDefault = false) {
		return $this->onRow("click", $jsCode, $stopPropagation, $preventDefault);
	}

	public function onRow($event, $jsCode, $stopPropagation = false, $preventDefault = false) {
		return $this->_self->addEvent($event . "{{tbody tr}}", $jsCode, $stopPropagation, $preventDefault);
	}

	public function getOnRow($event, $url, $responseElement = "", $parameters = array()) {
		$activeClass = $this->_self->getActiveRowClass();
		$jsCondition = '(!$(this).closest("tr").hasClass("' . $activeClass . '") || event.target.tagName === "TR")';
		if (isset($parameters['jsCondition'])) {
			$jsCondition = '(' . $parameters['jsCondition'] . ' && ' . $jsCondition . ')';
		}
		$parameters = \array_merge($parameters, [
			"stopPropagation" => false,
			"preventDefault" => false,
			"jsCondition" => $jsCondition
		]);
		$selector = "tbody tr";
		if (isset($parameters["selector"])) {
			$selector = $parameters["selector"];
		}
		return $this->_self->getOn($event . "{{" . $selector . "}}", $url, $responseElement, $parameters);
	}

	public function onPageChange($jsCode) {
		$this->_self->_addEvent("pageChange", $jsCode);
		return $this;
	}

	public function onSearchTerminate($jsCode) {
		$this->_self->_addEvent("searchTerminate", $jsCode);
		return $this;
	}

	public function getEventsScript() {
		return $this->_self->getBsComponent()->getScript();
	}

	public function addEventsOnRun(JsUtils $js = NULL) {
		$script = parent::addEventsOnRun($js);
		$innerScript = $this->_self->getInnerScript();
		if (! isset($innerScript)) {
			$this->_self->setInnerScript($script);
		}
		return $script;
	}
}
