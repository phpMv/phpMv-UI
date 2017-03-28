<?php
namespace Ajax\semantic\html\base\constants;
use Ajax\common\BaseEnum;
abstract class Transition extends BaseEnum {
	const SCALE="scale",
	FADE="fade",FADE_UP="fadeUp",FADE_DOWN="fadeDown",FADE_LEFT="fadeLeft",FADE_RIGHT="fadeRight",
	HORIZONTAL_FLIP="horizontalFlip",VERTICAL_FLIP="verticalFlip",
	DROP="drop",
	FLY_LEFT="flyLeft",FLY_RIGHT="flyRight",FLY_UP="flyUp",FLY_DOWN="flyDown",
	SWING_LEFT="swingLeft",SWING_RIGHT="swingRight",SWING_UP="swingUp",SWING_DOWN="swingDown",
	BROWSE="browse",BROWSE_RIGHT="browseRight",
	SLIDE_LEFT="slideLeft",SLIDE_RIGHT="slideRight",SLIDE_UP="slideUp",SLIDE_DOWN="slideDown",
	JIGGLE="jiggle",FLASH="flash",SHAKE="shake",PULSE="pulse",TADA="tada",BOUNCE="bounce";
}
