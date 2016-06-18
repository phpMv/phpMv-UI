<?php


use Ajax\php\cakephp\_JsUtils;
use Cake\Controller\Component;
use Ajax\Semantic;

class JsUtilsComponent extends Component {
	/**
	 * @var Ajax\php\cakephp\_JsUtils
	 */
	public $jquery;
		\extract($config);
		$this->jquery=new _JsUtils();
		if(isset($semantic)){
			$this->jquery->semantic(new Semantic());
		}
	}
}
