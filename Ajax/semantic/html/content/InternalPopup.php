<?php

namespace Ajax\semantic\html\content;

use Ajax\JsUtils;
use Ajax\service\JString;
class InternalPopup {
	protected $title;
	protected $content;
	protected $html;
	protected $variation;
	protected $params;
	protected $semElement;
	public function __construct($semElement,$title="",$content="",$variation=NULL,$params=array()){
		$this->semElement=$semElement;
		$this->title=$title;
		$this->content=$content;
		$this->setAttributes($variation,$params);
	}

	public function setHtml($html) {
		$this->html=$html;
		return $this;
	}

	public function setAttributes($variation=NULL,$params=array()){
		$this->variation=$variation;
		$this->params=$params;
	}

	public function onShow($jsCode){
		$this->params["onShow"]=$jsCode;
	}

	public function compile(){
		if(JString::isNotNull($this->title)){
			$this->semElement->addToProperty("data-title", $this->title);
		}
		if(JString::isNotNull($this->content)){
			$this->semElement->addToProperty("data-content", $this->content);
		}
		if(JString::isNotNull($this->html)){
			$this->semElement->addToProperty("data-html", $this->html);
		}
		if(JString::isNotNull($this->variation)){
			$this->semElement->addToProperty("data-variation", $this->variation);
		}
	}

	public function run(JsUtils $js){
		$js->semantic()->popup("#".$this->semElement->getIdentifier(),$this->params);
	}

}