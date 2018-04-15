<?php

namespace Ajax\semantic\html\content;

use Ajax\JsUtils;
use Ajax\service\JString;
use Ajax\semantic\html\base\HtmlSemDoubleElement;

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
		$this->html= $html;
		return $this;
	}

	public function setAttributes($variation=NULL,$params=array()){
		$this->variation=$variation;
		$this->params=$params;
	}

	public function onShow($jsCode){
		$this->params["onShow"]=$jsCode;
	}

	public function compile(JsUtils $js=NULL){
		if(JString::isNotNull($this->title)){
			$this->semElement->setProperty("data-title", $this->title);
		}
		if(JString::isNotNull($this->content)){
			$this->semElement->setProperty("data-content", $this->content);
		}
		$this->_compileHtml($js);
		if(JString::isNotNull($this->variation)){
			$this->semElement->setProperty("data-variation", $this->variation);
		}
	}

	private function _compileHtml(JsUtils $js=NULL){
		if(JString::isNotNull($this->html)){
			$html=$this->html;
			if(\is_array($html)){
				\array_walk($html, function(&$item) use($js){
					if($item instanceof HtmlSemDoubleElement){
						$comp=$item->compile($js);
						if(isset($js)){
							$bs=$item->run($js);
							if(isset($bs))
								$this->params['onShow']=$bs->getScript();
						}
						$item=$comp;
					}
				});
				$html=\implode("",$html);
			}
			$html=\str_replace("\"", "'", $html);
			$this->semElement->addToProperty("data-html", $html);
		}
	}

	public function run(JsUtils $js){
		$js->semantic()->popup("#".$this->semElement->getIdentifier(),$this->params);
	}

}
