<?php

namespace Ajax\semantic\html\content;

use Ajax\service\JArray;
use Ajax\semantic\html\base\traits\MenuItemTrait;
use Ajax\semantic\html\base\HtmlSemDoubleElement;

class HtmlMenuItem extends HtmlSemDoubleElement {
	use MenuItemTrait;
	public function __construct($identifier, $content) {
		parent::__construct($identifier,"div","item",$content);
	}

	protected function initContent($content){
		if(\is_array($content)){
			if(JArray::isAssociative($content)===false){
				$icon=@$content[0];
				$title=@$content[1];
				$desc=@$content[2];
			}else{
				$icon=@$content["icon"];
				$title=@$content["title"];
				$desc=@$content["description"];
			}
			if(isset($icon)){
				$this->addIcon($icon);
			}
			if(isset($title)){
				$this->setTitle($title,$desc);
			}
		}else{
			$this->setContent($content);
		}
	}

/*	public function addIcon($icon, $before=true) {
		$content=$this->content;
		$this->content=new HtmlSemDoubleElement("content-" . $this->identifier, "div", "content");
		$this->content->setContent($content);
		$this->content->addContent(new HtmlIcon("icon" . $this->identifier, $icon), $before);
		return $this;
	}*/
}
