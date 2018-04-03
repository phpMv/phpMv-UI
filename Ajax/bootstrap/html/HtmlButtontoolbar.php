<?php

namespace Ajax\bootstrap\html;

/**
 * Twitter Bootstrap HTML Button toolbar
 * @see http://getbootstrap.com/components/#btn-groups-toolbar
 * @author jc
 * @version 1.001
 */

class HtmlButtontoolbar extends HtmlButtongroups {

	public function __construct($identifier, $elements=array(), $cssStyle=NULL, $size=NULL, $tagName="div") {
		parent::__construct($identifier, $elements, $cssStyle, $size, $tagName);
		$this->setClass("btn-toolbar");
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\HtmlButtongroups::addElement()
	 */
	public function addElement($element) {
		if ($element instanceof HtmlButtongroups) {
			$this->elements []=$element;
		} else {
			$this->getLastButtonGroup()->addElement($element);
		}
	}

	/**
	 * Add and return a new buttongroup
	 * @return \Ajax\bootstrap\html\HtmlButtongroups
	 */
	public function addGroup() {
		$nb=sizeof($this->elements);
		$bg=new HtmlButtongroups($this->identifier."-buttongroups-".$nb);
		$this->elements []=$bg;
		return $bg;
	}

	/**
	 *
	 * @return HtmlButtongroups
	 */
	private function getLastButtonGroup() {
		$nb=sizeof($this->elements);
		if ($nb>0)
			$bg=$this->elements [$nb-1];
		else {
			$bg=new HtmlButtongroups($this->identifier."-buttongroups-".$nb);
			$this->elements []=$bg;
		}
		return $bg;
	}

	/**
	 * return the Buttongroups at position $index
	 * @return \Ajax\bootstrap\html\HtmlButtongroups
	 */
	public function getGroup($index) {
		return parent::getElement($index);
	}

	public function getLastGroup() {
		$bg=null;
		$nb=sizeof($this->elements);
		if ($nb>0)
			$bg=$this->elements [$nb-1];
		return $bg;
	}

	
	/**
	 * @return HtmlButtongroups|HtmlButton
	 */
	public function getElement($index) {
		$element=null;
		$i=0;
		if (is_int($index)) {
			$elements=array ();
			foreach ( $this->elements as $group ) {
				$elements=array_merge($elements, $group->getElements());
			}
			if ($index<sizeof($elements)) {
				$element=$elements [$index];
			}
		} else {
			while ( $element===null && $i<sizeof($this->elements) ) {
				$element=$this->elements [$i]->getElement($index);
				$i++;
			}
		}
		return $element;
	}
}
