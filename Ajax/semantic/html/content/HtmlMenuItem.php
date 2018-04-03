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
			}else{
				$icon=@$content["icon"];
				$title=@$content["title"];
			}
			if(isset($icon)){
				$this->addIcon($icon);
			}
			if(isset($title)){
				$this->setTitle($title);
			}
		}else{
			$this->setContent($content);
		}
	}
}
