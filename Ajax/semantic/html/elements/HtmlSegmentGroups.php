<?php

namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\constants\SegmentType;
use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\semantic\html\base\constants\Sens;
use Ajax\JsUtils;

class HtmlSegmentGroups extends HtmlSemCollection{


	public function __construct( $identifier, $items=array()){
		parent::__construct( $identifier, "div","ui segments");
		$this->addItems($items);
	}


	protected function createItem($value){
		return new HtmlSegment("segment-".$this->count(),$value);
	}

	protected function createCondition($value){
		return !($value instanceof HtmlSegment);
	}

	/**
	 * Defines the group type
	 * @param string $type one of "raised","stacked","piled" default : ""
	 * @return \Ajax\semantic\html\elements\HtmlSegmentGroups
	 */
	public function setType($type){
		return $this->addToPropertyCtrl("class", $type, SegmentType::getConstants());
	}

	public function setSens($sens=Sens::VERTICAL){
		return $this->addToPropertyCtrl("class", $sens, Sens::getConstants());
	}

	public function run(JsUtils $js){
		$result= parent::run($js);
		return $result->setItemSelector(".ui.segment");
	}

	public static function group($identifier,$items=array(),$type="",$sens=Sens::VERTICAL){
		$group=new HtmlSegmentGroups($identifier,$items);
		$group->setSens($sens);
		return $group->setType($type);
	}

}
