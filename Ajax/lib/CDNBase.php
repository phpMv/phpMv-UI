<?php

namespace Ajax\lib;


use Ajax\service\JString;

abstract class CDNBase {
	protected $version;
	protected $provider;
	protected $data;
	protected $local;
	protected $jsUrl;

	public function __construct($version, $provider) {
		$this->data=include 'CDN.php';
		$this->version=$version;
		$this->provider=$provider;
		$this->local=false;
		$this->jsUrl=null;
	}

	public function getJsUrl() {
		return $this->jsUrl;
	}

	public function setJsUrl($jsUrl, $local=null) {
		$this->jsUrl=$jsUrl;
		if (isset($local)===false) {
			$local=JString::startsWith($jsUrl, "http")===false;
		}
		$this->setLocal($local);
		return $this;
	}

	protected function getUrlOrCss($element, $key) {
		if (isset($element))
			return $element;
		$version=$this->version;
		if (array_search($version, $this->getVersions())===false)
			$version=$this->getLastVersion();
		return $this->replaceVersion($this->data [$this->provider] [$key], $version);
	}

	public function isLocal() {
		return $this->local;
	}

	public function setLocal($local) {
		$this->local=$local;
		return $this;
	}

	protected function replaceVersion($url, $version) {
		return str_ireplace("%version%", $version, $url);
	}

	protected function replaceTheme($url, $theme) {
		return str_ireplace("%theme%", $theme, $url);
	}

	protected function replaceVersionAndTheme($url, $version, $theme) {
		if (isset($theme))
			return str_ireplace(array (
					"%theme%",
					"%version%" 
			), array (
					$theme,
					$version 
			), $url);
		else
			return $this->replaceVersion($url, $version);
	}

	public function getProviders() {
		return array_keys($this->data);
	}

	public function getVersions($provider=NULL) {
		if (isset($provider))
			return $this->data [$provider] ["versions"];
		else
			return $this->data [$this->provider] ["versions"];
	}

	public function getLastVersion($provider=NULL) {
		return $this->getVersions($provider)[0];
	}

	abstract public function getUrl();
}
