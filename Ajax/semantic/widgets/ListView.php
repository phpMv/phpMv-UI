<?php

namespace Ajax\semantic\widgets;

use Ajax\common\Widget;
use Ajax\JsUtils;
use Ajax\semantic\html\collections\HtmlTable;

class ListView extends Widget {


	public function run(JsUtils $js){
		parent::run($js);
	}

	public function __construct($identifier,$model,$modelInstance=NULL) {
		parent::__construct($identifier, $model,$modelInstance);
		$this->_instanceViewer=new InstanceViewer();
	}

	public function compile(JsUtils $js=NULL,&$view=NULL){
		$this->_instanceViewer->setInstance($this->_model);
		$captions=$this->_instanceViewer->getCaptions();
		$table=new HtmlTable($this->identifier,0,\sizeof($captions));
		$table->setHeaderValues($captions);
		$table->fromDatabaseObjects($this->_modelInstance, function($instance){
			$this->_instanceViewer->setInstance($instance);
			return $this->_instanceViewer->getValues();
		});
		$this->content=$table;
		return parent::compile($js,$view);
	}

	public function getInstanceViewer() {
		return $this->_instanceViewer;
	}

	public function setInstanceViewer($_instanceViewer) {
		$this->_instanceViewer=$_instanceViewer;
		return $this;
	}

	public function setCaptions($captions){
		$this->_instanceViewer->setCaptions($captions);
		return $this;
	}

	public function setFields($fields){
		$this->_instanceViewer->setVisibleProperties($fields);
		return $this;
	}

	public function setValueFunction($index,$callback){
		$this->_instanceViewer->setValueFunction($index, $callback);
		return $this;
	}

}