<?php

namespace Ajax\semantic\html\base\constants\icons;

use Ajax\common\BaseEnum;

abstract class PaymentOptions extends BaseEnum {
	const AMERICAN_EXPRESS="american express", DISCOVER="discover", GOOGLE_WALLET="google wallet", MASTERCARD="mastercard", PAYPAL_CARD="paypal card", PAYPAL="paypal", STRIPE="stripe", VISA="visa";
}
