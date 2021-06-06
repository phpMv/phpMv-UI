<?php
namespace Ajax\semantic\html\modules;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\JsUtils;
use Ajax\service\JArray;
use Ajax\common\html\BaseHtml;
use Ajax\semantic\html\elements\HtmlButton;
use Ajax\semantic\html\elements\HtmlImage;
use Ajax\semantic\html\elements\HtmlIcon;

/**
 *
 * @author jc
 *
 */
class HtmlModal extends HtmlSemDoubleElement {

	protected $_params = [];

	protected $_paramParts = [];

	public function __construct($identifier, $header = '', $content = '', $actions = null) {
		parent::__construct($identifier, 'div', 'ui modal');
		if (isset($header)) {
			$this->setHeader($header);
		}
		if (isset($content)) {
			$this->setContent($content);
		}
		if (isset($actions)) {
			$this->setActions($actions);
		}
	}

	public function setHeader($value) {
		$this->content['header'] = new HtmlSemDoubleElement('header-' . $this->identifier, 'a', 'header', $value);
		return $this;
	}

	public function setContent($value) {
		$this->content['content'] = new HtmlSemDoubleElement('content-' . $this->identifier, 'div', 'content', $value);
		return $this;
	}

	/**
	 * Adds the modal actions (buttons).
	 *
	 * @param string|array $actions
	 * @return HtmlButton[]
	 */
	public function setActions($actions): array {
		$this->content['actions'] = new HtmlSemDoubleElement('content-' . $this->identifier, 'div', 'actions');
		$r = [];
		if (\is_array($actions)) {
			foreach ($actions as $action) {
				$r[] = $this->addAction($action);
			}
		} else {
			$r[] = $this->addAction($actions);
		}
		return $r;
	}

	/**
	 *
	 * @param string|BaseHtml $action
	 * @return HtmlButton
	 */
	public function addAction($action) {
		if (! $action instanceof BaseHtml) {
			$class = '';
			if (\array_search($action, [
				'Okay',
				'Yes',
				'Validate'
			]) !== false) {
				$class = 'approve';
			}
			if (\array_search($action, [
				'Close',
				'Cancel',
				'No'
			]) !== false) {
				$class = 'cancel';
			}
			$action = new HtmlButton('action-' . $this->identifier . '-' . JArray::count($this->content['actions']->getContent()), $action);
			if ($class !== '')
				$action->addToProperty('class', $class);
		}
		return $this->addElementInPart($action, 'actions');
	}

	/**
	 *
	 * @param int $index
	 * @return HtmlButton
	 */
	public function getAction($index) {
		return $this->content['actions']->getContent()[$index];
	}

	public function addContent($content, $before = false) {
		$this->content['content']->addContent($content, $before);
		return $this;
	}

	public function addImageContent($image, $description = NULL) {
		$content = $this->content['content'];
		if (isset($description)) {
			$description = new HtmlSemDoubleElement('description-' . $this->identifier, 'div', 'description', $description);
			$content->addContent($description, true);
		}
		if ($image !== '') {
			$img = new HtmlImage('image-' . $this->identifier, $image, '', 'medium');
			$content->addContent($img, true);
			$content->addToProperty('class', 'image');
		}
		return $this;
	}

	public function addIconContent($icon, $description = NULL) {
		$content = $this->content['content'];
		if (isset($description)) {
			$description = new HtmlSemDoubleElement('description-' . $this->identifier, 'div', 'description', $description);
			$content->addContent($description, true);
		}
		if ($icon !== '') {
			$img = new HtmlIcon('image-' . $this->identifier, $icon);
			$content->addContent($img, true);
			$content->addToProperty('class', 'image');
		}
		return $this;
	}

	private function addElementInPart($element, $part) {
		$this->content[$part]->addContent($element);
		return $element;
	}

	public function showDimmer($value) {
		$value = $value ? 'show' : 'hide';
		$this->_paramParts[] = [
			"'" . $value . " dimmer'"
		];
		return $this;
	}

	public function setInverted($recursive = true) {
		$this->_params['inverted'] = true;
		return $this;
	}

	public function setBasic() {
		return $this->addToProperty('class', 'basic');
	}

	public function setTransition($value) {
		$this->_paramParts[] = [
			"'setting'",
			"'transition'",
			"'" . $value . "'"
		];
	}

	/**
	 * render the content of an existing view : $controller/$action and set the response to the modal content
	 *
	 * @param JsUtils $js
	 * @param object $initialController
	 * @param string $viewName
	 * @param array $params
	 *        	The parameters to pass to the view
	 */
	public function renderView(JsUtils $js, $initialController, $viewName, $params = array()) {
		return $this->setContent($js->renderContent($initialController, $viewName, $params));
	}

	/**
	 * render the content of $controller::$action and set the response to the modal content
	 *
	 * @param JsUtils $js
	 * @param object $initialControllerInstance
	 * @param string $controllerName
	 *        	the controller name
	 * @param string $actionName
	 *        	the action name
	 * @param array $params
	 */
	public function forward(JsUtils $js, $initialControllerInstance, $controllerName, $actionName, $params = NULL) {
		return $this->setContent($js->forward($initialControllerInstance, $controllerName, $actionName, $params));
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Ajax\semantic\html\base\HtmlSemDoubleElement::compile()
	 */
	public function compile(JsUtils $js = NULL, &$view = NULL) {
		$this->content = JArray::sortAssociative($this->content, [
			'header',
			'content',
			'actions'
		]);
		if (isset($this->_params['inverted']) && $this->_params['inverted']) {
			parent::setInverted(true);
		}
		return parent::compile($js, $view);
	}

	/*
	 * (non-PHPdoc)
	 * @see BaseHtml::run()
	 */
	public function run(JsUtils $js) {
		if (isset($this->_bsComponent) === false)
			$this->_bsComponent = $js->semantic()->modal('#' . $this->identifier, $this->_params, $this->_paramParts);
		$this->addEventsOnRun($js);
		return $this->_bsComponent;
	}

	public function jsDo($behavior) {
		return "$('#" . $this->identifier . "').modal('" . $behavior . "');";
	}

	public function jsHide() {
		return $this->jsDo('hide');
	}

	public function onHidden($js) {
		$this->_params['onHidden'] = $js;
	}
}
