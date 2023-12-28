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

    private $style;

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

    public function compile($internal = false) {
        if($this->style!=null){
            parent::compile($internal);
        }else {
            if ($internal === false && $this->autoCompile === true)
                throw new \Exception("Impossible to compile if autoCompile is set to 'true'");
            $style=$this->style;
            foreach ($this->components as $component) {
                $component->addToProperty("class", $style);
                $component->compile();
            }
        }
    }

    public function setStyle($style='inverted'){
        $this->style=$style;
    }

    public function getStyle(){
        return $this->style;
    }
}
