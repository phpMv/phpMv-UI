<?php

namespace Ajax\lib;


use Ajax\service\JString;
use Ajax\common\html\html5\HtmlUtils;

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
		return HtmlUtils::javascriptInclude($url)."\n".HtmlUtils::stylesheetInclude($css);
	}

	public function setCssUrl($cssUrl, $local=null) {
		$this->cssUrl=$cssUrl;
		if (isset($local)===false) {
			$local=JString::startsWith($cssUrl, "http")===false;
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
