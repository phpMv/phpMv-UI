<?php

namespace Ajax\semantic\traits;

use Ajax\semantic\html\views\HtmlCard;
use Ajax\semantic\html\views\HtmlCardGroups;

trait SemanticHtmlViewsTrait {

	public abstract function addHtmlComponent($htmlComponent);

	/**
	 *
	 * @param string $identifier
	 */
	public function htmlCard($identifier) {
		return $this->addHtmlComponent(new HtmlCard($identifier));
	}

	public function htmlCardGroups($identifier, $cards=array()) {
		return $this->addHtmlComponent(new HtmlCardGroups($identifier, $cards));
	}
}