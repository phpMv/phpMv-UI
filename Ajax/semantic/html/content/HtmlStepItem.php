<?php

namespace Ajax\semantic\html\content;

use Ajax\service\JArray;
use Ajax\semantic\html\base\constants\StepStatus;

class HtmlStepItem extends HtmlAbsractItem {

	public function __construct($identifier, $content) {
		parent::__construct($identifier,"step",$content);
	}
	protected function initContent($content){
		if(\is_array($content)){
			$icon=JArray::getValue($content, "icon", 0);
			$title=JArray::getValue($content, "title", 1);
			$desc=JArray::getValue($content, "description", 2);
			$status=JArray::getValue($content, "status", 3);
			if(isset($icon)){
				$this->setIcon($icon);
			}
			if(isset($status)){
				$this->setStatus($status);
			}
			if(isset($title)){
				$this->setTitle($title,$desc);
			}
		}else{
			$this->setContent($content);
		}
	}

	public function setActive($value=true){
		if($value)
			$this->setStatus(StepStatus::ACTIVE);
		else
			$this->setStatus(StepStatus::NONE);
		return $this;
	}

	public function setCompleted(){
		$this->removePropertyValues("class", [StepStatus::COMPLETED,StepStatus::DISABLED]);
		return $this->setStatus(StepStatus::COMPLETED);
	}

	public function setStatus($status){
		return $this->addToPropertyCtrl("class", $status, StepStatus::getConstants());
	}

	public function removeStatus(){
		$this->removePropertyValues("class", StepStatus::getConstants());
	}
}
