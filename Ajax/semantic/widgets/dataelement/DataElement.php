<?php

namespace Ajax\semantic\widgets\dataelement;

use Ajax\common\Widget;

/**
 * DataElement widget for displaying an instance of model
 * @version 1.0
 * @author jc
 * @since 2.2
 *
 */
class DataElement extends Widget {

	public function __construct($identifier, $model, $modelInstance=NULL) {
		parent::__construct($identifier, $model, $modelInstance=NULL);
	}

	public function getHtmlComponent() {
		// TODO Auto-generated method stub
	}
}