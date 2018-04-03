<?php

namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\semantic\html\content\HtmlStepItem;
use Ajax\JsUtils;

use Ajax\common\html\HtmlDoubleElement;
use Ajax\semantic\html\base\constants\Side;
use Ajax\common\html\HtmlCollection;

class HtmlStep extends HtmlSemCollection{
	protected $_activeStep;
	protected $_startStep;

	public function __construct( $identifier,$steps=array()){
		parent::__construct( $identifier,"div", "ui steps");
		$this->addItems($steps);
	}


	/**
	 * {@inheritDoc}
	 * @see HtmlCollection::createItem()
	 */
	protected function createItem($value) {
		$itemO=new HtmlStepItem("item-".\sizeof($this->content),$value);
		return $itemO;
	}

	/**
	 * @param string|array $step
	 * @return HtmlStepItem
	 */
	public function addStep($step){
		return $this->addItem($step);
	}

	public function setOrdered(){
		return $this->addToProperty("class", "ordered");
	}

	public function isOrdered(){
		return $this->propertyContains("class", "ordered");
	}

	public function setVertical(){
		return $this->addToProperty("class", "vertical");
	}

	protected function defineActiveStep(){
		$activestep=$this->_activeStep;
		$count=$this->count();
		if(!$this->isOrdered()){
			for($i=$this->_startStep;$i<$count;$i++){
				$step=$this->content[$i];
				$step->removeStatus();
				if($i<$activestep)
					$step->setCompleted();
				elseif ($i===$activestep)
					$step->setActive();
				else
					$step->setDisabled();
			}
		}else{
			foreach ($this->content as $step){
				$step->removeStatus();
			}
			if($activestep<$count)
				$this->content[$activestep]->setActive();
		}
		return $this;
	}

	public function compile(JsUtils $js=NULL, &$view=NULL) {
		if(isset($this->_activeStep) && \is_numeric($this->_activeStep))
			$this->defineActiveStep();
		return parent::compile($js,$view);
	}

	public function setActiveStep($_activeStep) {
		$this->_activeStep=$_activeStep;
		return $this;
	}

	public function setAttached($side="",HtmlDoubleElement $toElement=NULL){
		if(isset($toElement)){
			$toElement->addToPropertyCtrl("class", "attached",array("attached"));
		}
		return $this->addToPropertyCtrl("class", $side." attached",Side::getConstantValues("attached"));
	}

	public function setStartStep($_startStep) {
		$this->_startStep=$_startStep;
		return $this;
	}

}
