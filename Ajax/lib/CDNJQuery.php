<?php

namespace Ajax\lib;

use Ajax\service\PhalconUtils;

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
		return PhalconUtils::javascriptInclude($url, $this->local);
	}
}