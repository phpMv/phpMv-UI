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
			if(JArray::isAssociative($content)===false){
				$icon=@$content[0];
				$title=@$content[1];
				$desc=@$content[2];
				$status=@$content[3];
			}else{
				$icon=@$content["icon"];
				$title=@$content["title"];
				$desc=@$content["description"];
				$status=@$content["status"];
			}
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
