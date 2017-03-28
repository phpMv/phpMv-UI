<?php

namespace Ajax\semantic\components\search;

use Ajax\service\JArray;

class SearchResults extends AbstractSearchResult implements ISearch {
	private $elements;

	public function __construct($objects=NULL) {
		$this->elements=array ();
		if (isset($objects)) {
			if (\is_array($objects)) {
				$this->addResults($objects);
			} else {
				$this->addResult($objects);
			}
		}
	}

	public function addResult($object) {
		if ($object instanceof SearchResult) {
			$this->elements[]=$object;
			return $this;
		}
		if (\is_array($object) === false) {
			$object=[ "title" => $object ];
		}
		$this->elements[]=new SearchResult($object);
		return $this;
	}

	public function addResults($objects) {
		if (!\is_array($objects)) {
			return $this->addResult($objects);
		}
		if (JArray::dimension($objects) === 1) {
			foreach ( $objects as $object ) {
				$this->addResult([ "title" => $object ]);
			}
		} else
			$this->elements=\array_merge($this->elements, $objects);
		return $this;
	}

	public function _search($query, $field="title") {
		$result=array ();
		foreach ( $this->elements as $element ) {
			if ($element instanceof SearchResult) {
				if ($element->search($query, $field) !== false)
					$result[]=$element->asArray();
			} else {
				if (\array_key_exists($field, $element)) {
					$value=$element[$field];
					if (\stripos($value, $query) !== false) {
						$result[]=$element;
					}
				}
			}
		}
		if (\sizeof($result) > 0) {
			return $result;
		}
		return false;
	}

	public function search($query, $field="title") {
		$result=$this->_search($query, $field);
		if ($result === false)
			$result=NULL;
		return new SearchResults($result);
	}

	public function __toString() {
		$result="\"results\": " . \json_encode($this->elements);
		return $result;
	}

	public function count() {
		return \sizeof($this->elements);
	}

	public function getResponse() {
		return "{" . $this . "}";
	}

	/**
	 * Loads results from a collection of DB objects
	 * @param array $objects the collection of objects
	 * @param callable $function return an array or an instance of SearchResult
	 */
	public function fromDatabaseObjects($objects, $function) {
		parent::fromDatabaseObjects($objects, $function);
	}

	protected function fromDatabaseObject($object, $function) {
		$this->addResult($function($object));
	}
}
