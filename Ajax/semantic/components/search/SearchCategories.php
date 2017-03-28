<?php

namespace Ajax\semantic\components\search;

class SearchCategories extends AbstractSearchResult {
	private $categories;

	public function __construct() {
		$this->categories=array ();
	}

	public function add($results, $category) {
		$count=\sizeof($this->categories);
		if (\array_key_exists($category, $this->categories)) {
			$this->categories[$category]->addResults($results);
		} else {
			$categoryO=new SearchCategory("category" . $count, $category, $results);
			$this->categories[$category]=$categoryO;
		}
		return $this;
	}

	public function search($query, $field="title") {
		$result=array ();
		foreach ( $this->categories as $category ) {
			$r=$category->search($query, $field);
			if ($r !== false)
				$result[]=$r;
		}
		$this->categories=$result;
		return $this;
	}

	public function __toString() {
		return "{\"results\":{" . \implode(",", \array_values($this->categories)) . "}}";
	}

	public function getResponse() {
		return $this->__toString();
	}

	/**
	 * Loads results and categories from a collection of DB objects
	 * @param array $objects the collection of objects
	 * @param callable $function return an instance of SearchCategory
	 */
	public function fromDatabaseObjects($objects, $function) {
		parent::fromDatabaseObjects($objects, $function);
	}

	protected function fromDatabaseObject($object, $function) {
		$result=$function($object);
		if ($result instanceof SearchCategory) {
			$this->categories[]=$result;
		}
	}
}
