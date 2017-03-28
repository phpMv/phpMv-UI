<?php

namespace Ajax\semantic\html\base\traits;

use Ajax\semantic\html\elements\HtmlIcon;
use Ajax\semantic\html\base\constants\Direction;


/**
 * @author jc
 * @property string $identifier
 * @property mixed $content
 */
trait IconTrait {
	private $_hasIcon=false;

	abstract protected function addToPropertyCtrl($name, $value, $typeCtrl);
	abstract public function addContent($content,$before=false);

	public function addIcon($icon,$direction=Direction::LEFT){
		if($this->_hasIcon===false){
			$iconO=$icon;
			if(\is_string($icon)){
				$iconO=new HtmlIcon("icon-".$this->identifier, $icon);
			}
			$this->addToPropertyCtrl("class", $direction." icon", Direction::getConstantValues("icon"));
			$this->addContent($iconO,$direction===Direction::LEFT);
			$this->_hasIcon=true;
		}else{
			$iconO=$this->getIcon();
			$iconO->setIcon($icon);
			$this->addToPropertyCtrl("class", $direction." icon", Direction::getConstantValues("icon"));
		}
		return $iconO;
	}

	public function getIcon(){
		if(\is_array($this->content)){
			foreach ($this->content as $item){
				if($item instanceof HtmlIcon)
					return $item;
			}
		}
	}
}
