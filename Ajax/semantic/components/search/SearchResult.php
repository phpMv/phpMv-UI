<?php

namespace Ajax\semantic\components\search;

class SearchResult implements \JsonSerializable {
	private $id;
	private $title;
	private $description;
	private $image;
	private $price;

	public function __construct($id=NULL, $title=NULL, $description=NULL, $image=NULL, $price=NULL) {
		if (\is_array($id)) {
			$this->fromArray($id);
		} else {
			$this->id=$id;
			$this->title=$title;
			$this->description=$description;
			$this->image=$image;
			$this->price=$price;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id=$id;
		return $this;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title=$title;
		return $this;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription($description) {
		$this->description=$description;
		return $this;
	}

	public function getImage() {
		return $this->image;
	}

	public function setImage($image) {
		$this->image=$image;
		return $this;
	}

	public function getPrice() {
		return $this->price;
	}

	public function setPrice($price) {
		$this->price=$price;
		return $this;
	}

	public function fromArray($array) {
		foreach ( $array as $key => $value ) {
			$this->{$key}=$value;
		}
		return $this;
	}

	public function asArray() {
		return $this->JsonSerialize();
	}

	public function JsonSerialize() {
		$vars=get_object_vars($this);
		$result=array ();
		foreach ( $vars as $k => $v ) {
			if (isset($v))
				$result[$k]=$v;
		}
		return $result;
	}

	public function search($query, $field="title") {
		$value=$this->$field;
		return \stripos($value, $query) !== false;
	}
}
