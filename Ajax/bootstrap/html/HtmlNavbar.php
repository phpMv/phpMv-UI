<?php

namespace Ajax\bootstrap\html;

use Ajax\JsUtils;
use Ajax\bootstrap\components\Scrollspy;
use Ajax\bootstrap\html\content\HtmlNavzone;
use Ajax\bootstrap\html\base\CssNavbar;
use Ajax\common\html\BaseHtml;
use Ajax\common\html\html5\HtmlImg;
/**
 * Twitter Bootstrap HTML Navbar component
 * @author jc
 * @version 1.001
 */
class HtmlNavbar extends BaseHtml {
	protected $navZones;
	protected $class="navbar-default";
	protected $brand="Brand";
	protected $brandHref="#";
	protected $brandTarget="_self";
	protected $brandImage="";
	protected $scrollspy;
	protected $hasScrollspy=false;
	protected $scrollspyId="body";
	protected $fluid="container-fluid";

	/**
	 *
	 * @param string $identifier the id
	 */
	public function __construct($identifier, $brand="Brand", $brandHref="#") {
		parent::__construct($identifier);
		$this->_template=include 'templates/tplNavbar.php';
		$this->navZones=array ();
		$this->class="navbar-default";
		$this->brand=$brand;
		$this->brandHref=$brandHref;

	}

	public function setClass($class) {
		$this->class=$class;
		return $this;
	}

	public function setBrand($brand) {
		$this->brand=$brand;
		return $this;
	}

	public function setBrandHref($brandHref) {
		$this->brandHref=$brandHref;
		return $this;
	}

	public function setBrandTarget($brandTarget) {
		$this->brandTarget=$brandTarget;
		return $this;
	}

	public function setBrandImage($imageSrc) {
		$this->brandImage=new HtmlImg("brand-img-".$this->_identifier,$imageSrc,$this->brand);
		$this->brand="";
		return $this;
	}

	/**
	 * adds a new zone of type $type
	 * @param string $type one of nav, form, btn, right, left
	 * @param string $identifier
	 * @return HtmlNavzone
	 */
	public function addZone($type="nav", $identifier=NULL) {
		if (!isset($identifier)) {
			$nb=sizeof($this->navZones)+1;
			$identifier=$this->identifier."-navzone-".$nb;
		}
		$zone=HtmlNavzone::$type($identifier);
		$this->navZones []=$zone;
		return $zone;
	}

	public function addElement($element, HtmlNavzone $zone=NULL) {
		$zone=$this->getZoneToInsertIn($zone);
		if ($element instanceof HtmlDropdown)
			$element->setMTagName("li");
		$zone->addElement($element);
	}

	public function addElements($elements, HtmlNavzone $zone=NULL) {
		$zone=$this->getZoneToInsertIn($zone);
		$zone->addElements($elements);
		return $zone;
	}

	/**
	 * /* (non-PHPdoc)
	 * @see BaseHtml::addProperties()
	 */
	public function fromArray($array) {
		return parent::fromArray($array);
	}

	public function setNavZones($navZones) {
		if (\is_array($navZones)) {
			foreach ( $navZones as $zoneType => $zoneArray ) {
				if (is_string($zoneType)) {
					$zone=$this->addZone($zoneType);
					$zone->fromArray($zoneArray);
				} else if (is_string($zoneArray))
					$this->addElement($zoneArray);
				else
					$this->addElements($zoneArray);
			}
		}
	}

	/**
	 *
	 * @param HtmlNavzone $zone
	 * @return HtmlNavzone
	 */
	public function getZoneToInsertIn($zone=NULL) {
		if (!isset($zone)) {
			$nb=sizeof($this->navZones);
			if ($nb<1)
				$zone=$this->addZone();
			else
				$zone=$this->navZones [$nb-1];
		}
		return $zone;
	}

	/**
	 *
	 * @param int $index
	 * @return HtmlNavzone
	 */
	public function getZone($index) {
		$zone=null;
		$nb=sizeof($this->navZones);
		if (is_int($index)) {
			if ($index<$nb)
				$zone=$this->navZones [$index];
		} else {
			for($i=0; $i<$nb; $i++) {
				if ($this->navZones [$i]->getIdentifier()===$index) {
					$zone=$this->navZones [$i];
					break;
				}
			}
		}
		return $zone;
	}

	public function run(JsUtils $js) {
		foreach ( $this->navZones as $zone ) {
			$zone->run($js);
		}
		if ($this->hasScrollspy) {
			$this->scrollspy=new Scrollspy($js);
			$this->scrollspy->attach($this->scrollspyId);
			$this->scrollspy->setTarget("#".$this->identifier);
			$this->scrollspy->compile($js);
		}
	}

	public function cssInverse() {
		$this->addToMember($this->class, CssNavbar::NAVBAR_INVERSE);
		return $this;
	}

	public function scrollspy($attachTo="body") {
		$this->hasScrollspy=true;
		$this->scrollspyId=$attachTo;
	}

	public function setFluid($fluid) {
		if($fluid===true){
			$this->fluid="container-fluid";
		}else{
			$this->fluid="container";
		}
		return $this;
	}

	/* (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\BaseHtml::fromDatabaseObject()
	 */
	public function fromDatabaseObject($object, $function) {
		$this->addElement($function($object));
	}

}
