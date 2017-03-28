<?php

namespace Ajax\semantic\html\base\traits;

use Ajax\semantic\html\elements\HtmlIcon;
use Ajax\semantic\html\base\constants\Direction;

/**
 * @author jc
 * @property string $identifier
 * @property string $tagName
 */
trait LabeledIconTrait {

	abstract public function addToProperty($name, $value, $separator=" ");
	abstract public function addContent($content,$before=false);

	/**
	 * Adds an icon before or after
	 * @param string|HtmlIcon $icon
	 * @param boolean $before
	 * @param boolean $labeled
	 * @return \Ajax\semantic\html\elements\HtmlIcon
	 */
	public function addIcon($icon,$before=true,$labeled=false){
		$iconO=$icon;
		if(\is_string($icon)){
			$iconO=new HtmlIcon("icon-".$this->identifier, $icon);
		}
		if($labeled!==false){
			$direction=($before===true)?Direction::LEFT:Direction::RIGHT;
			$this->addToProperty("class", $direction." labeled icon");
			$this->tagName="div";
		}
		$this->addContent($iconO,$before);
		return $iconO;
	}
}
