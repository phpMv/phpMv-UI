<?php

namespace Ajax\semantic\components\search;

interface ISearch {

	public function search($query, $field="title");

	public function getResponse();
}
