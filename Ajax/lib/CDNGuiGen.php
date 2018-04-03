<?php

namespace Ajax\lib;


use Ajax\service\JString;
use Ajax\common\html\html5\HtmlUtils;

class CDNGuiGen extends CDNBase {
	protected $theme;
	protected $cssUrl;
	protected $localCss;

	public function __construct($version, $theme=NULL, $provider="Google") {
		parent::__construct($version, $provider);
		$this->data=$this->data ["JQueryUI"];
		if (is_int($theme)) {
			$themes=$this->getThemes();
			if (sizeof($themes)>$theme-1)
				$this->theme=$themes [$theme-1];
			else
				throw new \Exception("CDNGuiGen : Le numéro de thème demandé n'existe pas");
		}
		$this->theme=$theme;
		$this->cssUrl=null;
	}

	public function getCssUrl() {
		return $this->cssUrl;
	}

	public function getThemes($provider=NULL) {
		if (isset($provider))
			return $this->data [$provider] ["themes"];
		else
			return $this->data [$this->provider] ["themes"];
	}

	public function getFirstTheme($provider=NULL) {
		$themes=$this->getThemes($provider);
		if (sizeof($themes)>0)
			return $themes [0];
		return "";
	}

	public function getUrl() {
		return $this->getUrlOrCss($this->jsUrl, "core");
	}

	public function getCss() {
		if (isset($this->cssUrl))
			return $this->cssUrl;
		$version=$this->version;
		if (array_search($version, $this->getVersions())===false)
			$version=$this->getLastVersion();
		$theme=$this->theme;
		if (array_search($theme, $this->getThemes())===false)
			$theme=$this->getFirstTheme();
		return $this->replaceVersionAndTheme($this->data [$this->provider] ["css"], $version, $theme);
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
}
