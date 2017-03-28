<?php

namespace Ajax\config;

use Ajax\config\Config;

class DefaultConfig extends Config {

	public function __construct() {
		parent::__construct(array (
				"formElementsPrefix" => array (
						"txt" => "input_text",
						"btn" => "button",
						"ck" => "checkbox",
						"cmb" => "select_1",
						"list" => "select_5",
						"_" => "input_hidden",
						"f" => "input_file",
						"radio" => "radio",
						"mail" => "input_email" 
				) 
		));
	}
}
