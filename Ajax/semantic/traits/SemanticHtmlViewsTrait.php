<?php

namespace Ajax\semantic\traits;

use Ajax\semantic\html\views\HtmlCard;
use Ajax\semantic\html\views\HtmlCardGroups;
use Ajax\semantic\html\views\HtmlItems;
use Ajax\common\html\BaseHtml;

trait SemanticHtmlViewsTrait {

	abstract public function addHtmlComponent(BaseHtml $htmlComponent);

	/**
	 *
	 * @param string $identifier
	 * @return HtmlCard
	 */
	public function htmlCard($identifier) {
		return $this->addHtmlComponent(new HtmlCard($identifier));
	}

	/**
	 * @param string $identifier
	 * @param array $items
	 * @return HtmlCardGroups
	 */
	public function htmlCardGroups($identifier, $cards=array()) {
		return $this->addHtmlComponent(new HtmlCardGroups($identifier, $cards));
	}

	/**
	 * @param string $identifier
	 * @param array $items
	 * @return HtmlItems
	 */
	public function htmlItems($identifier, $items=array()) {
		return $this->addHtmlComponent(new HtmlItems($identifier, $items));
	}
}
