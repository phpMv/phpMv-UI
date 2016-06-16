<?php

namespace Ajax\semantic\html\content;


use Ajax\service\JArray;
use Ajax\semantic\html\elements\HtmlList;
class HtmlListItem extends HtmlAbsractItem {
	protected $image;

	public function __construct($identifier, $content=NULL) {
		parent::__construct($identifier,"item",$content);
	}
	protected function initContent($content){
		if(\is_array($content)){
			if(JArray::isAssociative($content)===false){
				$icon=@$content[0];
				$title=@$content[1];
				$desc=@$content[2];
			}else{
				$icon=@$content["icon"];
				$image=@$content["image"];
				$title=@$content["title"];
				$header=@$content["header"];
				$desc=@$content["description"];
				$items=@$content["items"];
			}
			if(isset($icon)===true){
				$this->setIcon($icon);
			}
			if(isset($image)===true){
				$this->setImage($image);
			}
			if(isset($title)===true){
				$this->setTitle($title,$desc);
			}elseif (isset($header)===true){
				$this->setTitle($header,$desc,"header");
			}
			if(isset($items)===true){
				$this->addList($items);
			}
		}else{
			$this->setContent($content);
		}
	}
	public function addList($items=array()) {
		$list=new HtmlList("", $items);
		$list->setClass("list");
		$this->content["list"]=$list;
		return $list;
	}

	public function getList(){
		return $this->content["list"];
	}

	public function getItem($index){
		return $this->getList()->getItem($index);
	}
}