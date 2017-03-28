<?php

namespace Ajax\semantic\html\base\traits;

use Ajax\semantic\html\base\constants\TextAlignment;

trait TextAlignmentTrait {

	abstract public function addToPropertyCtrl($name, $value, $typeCtrl);

	/**
	 * @param string $value
	 * @return \Ajax\semantic\html\base\HtmlSemDoubleElement
	 */
	public function setTextAlignment($value=TextAlignment::LEFT){
		return $this->addToPropertyCtrl("class", $value,TextAlignment::getConstants());
	}

	public function textCenterAligned(){
		return $this->setTextAlignment(TextAlignment::CENTER);
	}

	public function textJustified(){
		return $this->setTextAlignment(TextAlignment::JUSTIFIED);
	}

	public function textRightAligned(){
		return $this->setTextAlignment(TextAlignment::RIGHT);
	}

	public function textLeftAligned(){
		return $this->setTextAlignment();
	}
}
