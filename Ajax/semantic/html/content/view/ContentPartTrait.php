<?php
namespace Ajax\semantic\html\content\view;
use Ajax\semantic\html\base\HtmlSemDoubleElement;

/**
 * @author jc
 * @property mixed $content
 */
trait ContentPartTrait{
	public function addElementInPart($element,$partKey,$before=false,$force=false){
		$part=$this->getPart($partKey,null,$force);
		if($part instanceof  HtmlSemDoubleElement){
			$this->content[$partKey]=$part;
			$part->addContent($element,$before);
		}
		return $this;
	}

	public function getPart($partKey, $index=NULL,$force=false) {
		if (\array_key_exists($partKey, $this->content)) {
			if (isset($index))
				return $this->content[$partKey][$index];
			return $this->content[$partKey];
		}
		if($force){
			return new HtmlSemDoubleElement($partKey."-".$this->identifier,"div",$partKey);
		}
		return NULL;
	}
}
