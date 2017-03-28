<?php

namespace Ajax\lib;

use Ajax\service\PhalconUtils;

class CDNCoreCss extends CDNBase {
	protected $cssUrl;
	protected $localCss;
	protected $framework;

	public function __construct($framework,$version, $provider="MaxCDN") {
		parent::__construct($version, $provider);
		$this->framework=$framework;
		$this->data=$this->data [$framework];
	}

	public function getUrl() {
		return $this->getUrlOrCss($this->jsUrl, "core");
	}

	public function getCss() {
		return $this->getUrlOrCss($this->cssUrl, "css");
	}

	public function __toString() {
		$url=$this->getUrl();
		$css=$this->getCss();
		return PhalconUtils::javascriptInclude($url, $this->local)."\n".PhalconUtils::stylesheetLink($css, $this->localCss);
		;
	}

	public function setCssUrl($cssUrl, $local=null) {
		$this->cssUrl=$cssUrl;
		if (isset($local)===false) {
			$local=PhalconUtils::startsWith($cssUrl, "http")===false;
		}
		$this->setLocalCss($local);
		return $this;
	}

	public function getLocalCss() {
		return $this->localCss;
	}

	public function setLocalCss($localCss) {
		$this->localCss=$localCss;
		return $this;
	}

	public function getFramework() {
		return $this->framework;
	}

}
