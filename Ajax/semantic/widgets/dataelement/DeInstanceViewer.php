<?php

namespace Ajax\semantic\widgets\dataelement;

use Ajax\semantic\widgets\base\InstanceViewer;
use Ajax\semantic\html\collections\form\HtmlFormField;
use Ajax\semantic\html\base\HtmlSemDoubleElement;

class DeInstanceViewer extends InstanceViewer {

	public function __construct($identifier, $instance=NULL, $captions=NULL) {
		parent::__construct($identifier, $instance, $captions);
	}

	public function getValue($index){
		$result=parent::getValue($index);
		if($result instanceof HtmlFormField){
			$lbl=new HtmlSemDoubleElement("lbl-".$this->widgetIdentifier."-".$index,"label","",$this->getCaption($index));
			$lbl->setProperty("for", $result->getDataField()->getIdentifier());
			$this->captions[$index]=$lbl;
		}
		return $result;
	}
}
