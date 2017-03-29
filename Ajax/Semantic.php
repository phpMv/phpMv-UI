<?php

namespace Ajax;

use Ajax\common\BaseGui;
use Ajax\semantic\traits\SemanticComponentsTrait;
use Ajax\semantic\traits\SemanticHtmlElementsTrait;
use Ajax\semantic\traits\SemanticHtmlCollectionsTrait;
use Ajax\semantic\traits\SemanticHtmlModulesTrait;
use Ajax\semantic\traits\SemanticHtmlViewsTrait;
use Ajax\semantic\traits\SemanticWidgetsTrait;

class Semantic extends BaseGui {
	use SemanticComponentsTrait,SemanticHtmlElementsTrait,SemanticHtmlCollectionsTrait,
	SemanticHtmlModulesTrait,SemanticHtmlViewsTrait,SemanticWidgetsTrait;

	private $language;

	public function __construct($autoCompile=true) {
		parent::__construct($autoCompile);
	}


	public function setLanguage($language){
		if($language!==$this->language){
			$file=\realpath(dirname(__FILE__)."/semantic/components/validation/languages/".$language.".js");
			if(\file_exists($file)){
				$script=\file_get_contents($file);
				$this->js->exec($script,true);
				$this->language=$language;
			}
		}
	}
}
