<?php
namespace Ajax\semantic\html\collections;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\elements\HtmlIcon;
use Ajax\JsUtils;
use Ajax\semantic\html\base\constants\Style;
use Ajax\semantic\html\base\traits\AttachedTrait;
use Ajax\semantic\html\base\traits\HasTimeoutTrait;

/**
 * Semantic Message component
 *
 * @see http://semantic-ui.com/collections/message.html
 * @author jc
 * @version 1.001
 */
class HtmlMessage extends HtmlSemDoubleElement {
	use AttachedTrait,HasTimeoutTrait;

	protected $icon;

	protected $close;

	public function __construct($identifier, $content = "") {
		parent::__construct($identifier, "div");
		$this->_template = '<%tagName% id="%identifier%" %properties%>%close%%icon%%wrapContentBefore%%content%%wrapContentAfter%</%tagName%>';
		$this->setClass("ui message");
		$this->setContent($content);
	}

	/**
	 * Adds an header to the message
	 *
	 * @param string|HtmlSemDoubleElement $header
	 * @return \Ajax\semantic\html\collections\HtmlMessage
	 */
	public function addHeader($header) {
		$headerO = $header;
		if (\is_string($header)) {
			$headerO = new HtmlSemDoubleElement("header-" . $this->identifier, "div");
			$headerO->setClass("header");
			$headerO->setContent($header);
		}
		return $this->addContent($headerO, true);
	}

	public function setHeader($header) {
		return $this->addHeader($header);
	}

	public function setIcon($icon) {
		$this->addToProperty("class", "icon");
		$this->wrapContent("<div class='content'>", "</div>");
		if (\is_string($icon)) {
			$this->icon = new HtmlIcon("icon-" . $this->identifier, $icon);
		} else {
			$this->icon = $icon;
		}
		return $this;
	}

	/**
	 * Performs a get to $url on the click event on close button
	 * and display it in $responseElement
	 *
	 * @param string $url
	 *        	The url of the request
	 * @param string $responseElement
	 *        	The selector of the HTML element displaying the answer
	 * @param array $parameters
	 *        	default : array("preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>null,"headers"=>null,"historize"=>false)
	 * @return $this
	 */
	public function getOnClose($url, $responseElement = "", $parameters = array()) {
		if (isset($this->close)) {
			$this->close->getOnClick($url, $responseElement, $parameters);
		}
	}

	public function addLoader($loaderIcon = "notched circle") {
		$this->setIcon($loaderIcon);
		$this->icon->addToIcon("loading");
		return $this;
	}

	public function setDismissable($dismiss = true) {
		if ($dismiss === true)
			$this->close = new HtmlIcon("close-" . $this->identifier, "close");
		else
			$this->close = NULL;
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Ajax\semantic\html\base\HtmlSemDoubleElement::run()
	 */
	public function run(JsUtils $js) {
		if (! isset($this->_bsComponent)) {
			if (isset($this->close)) {
				$js->execOn("click", "#" . $this->identifier . " .close", "$(this).closest('.message').transition({$this->_closeTransition}).trigger('close-message');");
			}
			if (isset($this->_timeout)) {
				$js->exec("setTimeout(function() { $('#{$this->identifier}').transition({$this->_closeTransition}).trigger('close-message'); }, {$this->_timeout});", true);
			}
		}
		return parent::run($js);
	}

	public function setState($visible = true) {
		$visible = ($visible === true) ? "visible" : "hidden";
		return $this->addToPropertyCtrl("class", $visible, array(
			"visible",
			"hidden"
		));
	}

	public function setVariation($value = "floating") {
		return $this->addToPropertyCtrl("class", $value, array(
			"floating",
			"compact"
		));
	}

	public function setStyle($style) {
		return $this->addToPropertyCtrl("class", $style, Style::getConstants());
	}

	public function setError() {
		return $this->setStyle("error");
	}

	public function setWarning() {
		return $this->setStyle("warning");
	}

	public function setMessage($message) {
		if (\is_array($this->content)) {
			$this->content[\sizeof($this->content) - 1] = $message;
		} else
			$this->setContent($message);
	}
}
