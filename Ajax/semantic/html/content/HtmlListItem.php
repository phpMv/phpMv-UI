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
			if(isset($icon)){
				$this->setIcon($icon);
			}
			if(isset($image)){
				$this->setImage($image);
			}
			if(isset($title)){
				$this->setTitle($title,$desc);
			}elseif (isset($header)){
				$this->setTitle($header,$desc,"header");
			}
			if(isset($items)){
				$this->addList($items);
			}
		}else{
			$this->setContent($content);
		}
	}
	public function addList($items=array(),$ordered=false) {
		$list=new HtmlList("", $items);
		if($ordered)
			$list->setOrdered();
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
