<?php

namespace Ajax\lib;


use Ajax\common\html\html5\HtmlUtils;

class CDNJQuery extends CDNBase {

	public function __construct($version, $provider="Google") {
		parent::__construct($version, $provider);
		$this->data=$this->data ["JQuery"];
	}

	public function getUrl() {
		return $this->getUrlOrCss($this->jsUrl, "url");
	}

	public function __toString() {
		$url=$this->getUrl();
		return HtmlUtils::javascriptInclude($url);
	}
}
