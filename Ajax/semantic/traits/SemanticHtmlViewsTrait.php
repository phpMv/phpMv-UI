<?php

namespace Ajax\semantic\traits;

use Ajax\semantic\html\views\HtmlCard;
use Ajax\semantic\html\views\HtmlCardGroups;

trait SemanticHtmlViewsTrait {

	abstract public function addHtmlComponent($htmlComponent);

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
	 * @param array $cards
	 * @return HtmlCardGroups
	 */
	public function htmlCardGroups($identifier, $cards=array()) {
		return $this->addHtmlComponent(new HtmlCardGroups($identifier, $cards));
	}
}