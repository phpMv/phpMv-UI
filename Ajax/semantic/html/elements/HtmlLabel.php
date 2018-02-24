<?php

namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\base\constants\Direction;
use Ajax\semantic\html\base\traits\LabeledIconTrait;
use Ajax\semantic\html\base\constants\Side;
use Ajax\semantic\html\elements\html5\HtmlImg;
use Ajax\semantic\html\base\traits\HasTimeoutTrait;
use Ajax\common\html\HtmlDoubleElement;

/**
 * Semantic Label component
 * @see http://phpmv-ui.kobject.net/index/direct/main/46
 * @see http://semantic-ui.com/elements/label.html
 * @author jc
 * @version 1.001
 */
class HtmlLabel extends HtmlSemDoubleElement {
	use LabeledIconTrait,HasTimeoutTrait;

	public function __construct($identifier, $caption="", $icon=NULL, $tagName="div") {
		parent::__construct($identifier, $tagName, "ui label");
		$this->content=$caption;
		if (isset($icon))
			$this->addIcon($icon);
	}

	/**
	 *
	 * @param string $value
	 * @return HtmlLabel
	 */
	public function setPointing($value=Direction::NONE) {
		if($value==="left" || $value==="right")
			return $this->addToPropertyCtrl("class", $value." pointing", Direction::getConstantValues("pointing"));
		else
			return $this->addToPropertyCtrl("class", "pointing ".$value, Direction::getConstantValues("pointing",true));
	}

	/**
	 *
	 * @param string $side
	 * @return HtmlLabel
	 */
	public function toCorner($side="left") {
		return $this->addToPropertyCtrl("class", $side . " corner", array ("right corner","left corner" ));
	}

	public function setHorizontal(){
		return $this->addToPropertyCtrl("class", "hozizontal",array("horizontal"));
	}

	public function setFloating(){
		return $this->addToPropertyCtrl("class", "floating",array("floating"));
	}

	/**
	 *
	 * @return HtmlLabel
	 */
	public function asTag() {
		return $this->addToProperty("class", "tag");
	}

	public function setEmpty(){
		$this->content=NULL;
		return $this->addToPropertyCtrl("class", "empty",array("empty"));
	}

	public function setBasic() {
		return $this->addToProperty("class", "basic");
	}

	/**
	 * Adds an image to emphasize
	 * @param string $src
	 * @param string $alt
	 * @param boolean $before
	 * @return HtmlImg
	 */
	public function addEmphasisImage($src, $alt="", $before=true) {
		$this->addToProperty("class", "image");
		return $this->addImage($src,$alt,$before);
	}

	/**
	 * Adds an avatar image
	 * @param string $src
	 * @param string $alt
	 * @param boolean $before
	 * @return HtmlImg
	 */
	public function addAvatarImage($src, $alt="", $before=true) {
		$img=$this->addImage($src,$alt,$before);
		$img->setClass("ui image");
		$img->asAvatar();
		return $img;
	}

	/**
	*  Adds an image
	* @param string $src
	* @param string $alt
	* @param boolean $before
	* @return HtmlImg
	*/
	public function addImage($src, $alt="", $before=true) {
		$img=new HtmlImg("image-" . $this->identifier, $src, $alt);
		$img->setClass("");
		$this->addContent($img, $before);
		return $img;
	}

	/**
	 *
	 * @param string $detail
	 * @return HtmlDoubleElement
	 */
	public function addDetail($detail) {
		$div=new HtmlSemDoubleElement("detail-" . $this->identifier, $this->tagName,"detail");
		$div->setContent($detail);
		$this->addContent($div);
		return $div;
	}

	/**
	 * @param string $direction one of RIGHT="right", LEFT="left",DOWN="down",UP="up",NONE="",BELOW="below"
	 * @return HtmlLabel
	 */
	public function asRibbon($direction=Direction::NONE) {
		return $this->addToPropertyCtrl("class", $direction." ribbon", array ("ribbon","right ribbon","left ribbon" ));
	}

	public function setAttached($side=Side::TOP,$direction=Direction::NONE){
		if($direction!==Direction::NONE)
			return $this->addToPropertyCtrl("class", $side." ".$direction." attached",Side::getConstantValues($direction." attached"));
		else
			return $this->addToPropertyCtrl("class", $side." attached",Side::getConstantValues("attached"));
	}

	/**
	 * @param string $identifier
	 * @param string $caption
	 * @param string $direction one of RIGHT="right", LEFT="left",DOWN="down",UP="up",NONE="",BELOW="below"
	 * @return HtmlLabel
	 */
	public static function ribbon($identifier, $caption,$direction=Direction::NONE) {
		return (new HtmlLabel($identifier, $caption))->asRibbon($direction);
	}

	/**
	 * @param string $identifier
	 * @param string $caption
	 * @return HtmlLabel
	 */
	public static function tag($identifier, $caption) {
		return (new HtmlLabel($identifier, $caption))->asTag();
	}
}
