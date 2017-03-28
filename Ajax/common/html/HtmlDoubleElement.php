<?php

namespace Ajax\common\html;


use Ajax\JsUtils;
class HtmlDoubleElement extends HtmlSingleElement {
	/**
	 *
	 * @var mixed
	 */
	protected $content;
	protected $wrapContentBefore="";
	protected $wrapContentAfter="";

	public function __construct($identifier, $tagName="p") {
		parent::__construct($identifier, $tagName);
		$this->_template="<%tagName% id='%identifier%' %properties%>%wrapContentBefore%%content%%wrapContentAfter%</%tagName%>";
	}

	public function setContent($content) {
		$this->content=$content;
		return $this;
	}

	public function getContent() {
		return $this->content;
	}

	public function addContent($content,$before=false) {
		if (!\is_array($this->content)) {
			if(isset($this->content))
				$this->content=array ($this->content);
			else
				$this->content=array();
		}
		if($before)
			array_unshift($this->content,$content);
		else
			$this->content []=$content;
		return $this;
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\HtmlSingleElement::run()
	 */
	public function run(JsUtils $js) {
		parent::run($js);
		if ($this->content instanceof HtmlDoubleElement) {
			$this->content->run($js);
		} else if (\is_array($this->content)) {
			foreach ( $this->content as $itemContent ) {
				if ($itemContent instanceof HtmlDoubleElement) {
					$itemContent->run($js);
				}
			}
		}
	}

	public function setValue($value) {
	}

	public function wrapContent($before, $after="") {
		$this->wrapContentBefore.=$before;
		$this->wrapContentAfter=$after.$this->wrapContentAfter;
		return $this;
	}

	public function getContentInstances($class){
		return $this->_getContentInstances($class,$this->content);
	}

	protected function _getContentInstances($class,$content){
		$instances=[];
		if($content instanceof $class){
			$instances[]=$content;
		}elseif($content instanceof HtmlDoubleElement){
			$instances=\array_merge($instances,$content->getContentInstances($class));
		}elseif (\is_array($content)){
			foreach ($content as $element){
				$instances=\array_merge($instances,$this->_getContentInstances($class, $element));
			}
		}
		return $instances;
	}
}
