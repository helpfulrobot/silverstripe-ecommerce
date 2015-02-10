<?php

class EcommerceMoney extends Extension {

	/**
	 * returns the symbol for a currency, e.g. $
	 * @param String $currency
	 *
	 * @return String
	 */
	public static function get_default_symbol($currency) {
		$money = Money::create();
		return $money->getSymbol($currency);
	}

	/**
	 * returns the short symbol for a currency
	 * This is shorter than the default one.
	 * @param String $currency
	 *
	 * @return String
	 */
	public static function get_short_symbol($currency) {
		$symbol = self::get_default_symbol($currency);
		if($symbol) {
			$i = 0;
			while($i < mb_strlen($symbol) && $symbol[$i] === $currency[$i]) {
				$i++;
			}
			return substr($symbol, $i);
		}
	}

	/**
	 * returns the long symbol for a currency
	 * @param String $currency
	 *
	 * @return String
	 */
	public static function get_long_symbol($currency) {
		$symbol = self::get_default_symbol($currency);
		if($symbol && mb_strlen($symbol) < 3) {
			$symbol = substr($currency, 0, 3 - mb_strlen($symbol)) . $symbol;
		}
		return $symbol;
	}

	/**
	 * returns the default symbol for a site.
	 * with or without html
	 * @param Boolean $html
	 *
	 * @return String
	 */
	function NiceDefaultSymbol($html = true) {
		return self::get_default_symbol($this->owner->currency) == self::get_short_symbol($this->owner->currency) ? $this->NiceShortSymbol($html) : $this->NiceLongSymbol($html);
	}

	/**
	 * returns the short symbol for a site.
	 * with or without html
	 * @param Boolean $html
	 *
	 * @return String
	 */
	function NiceShortSymbol($html = true) {
		$symbol = self::get_short_symbol($this->owner->currency);
		if($html) {
			$symbol = "<span class=\"currencyHolder currencyHolderShort currency{$this->owner->currency}\"><span class=\"currencySymbol\">$symbol</span></span>";
		}
		$amount = $this->owner->getAmount();
		return (is_numeric($amount)) ? $this->owner->currencyLib->toCurrency($amount, array('symbol' => $symbol, 'display' => Zend_Currency::USE_SYMBOL)) : '';
	}

	/**
	 * returns the long symbol for a site.
	 * with or without html
	 * @param Boolean $html
	 *
	 * @return String
	 */
	function NiceLongSymbol($html = true) {
		$symbol = self::get_long_symbol($this->owner->currency);
		$short = self::get_short_symbol($this->owner->currency);
		$pre = substr($symbol, 0, mb_strlen($symbol) - mb_strlen($short));
		if($html) {
			$symbol = "<span class=\"currencyHolder currencyHolderLong currency{$this->owner->currency}\"><span class=\"currencyPreSymbol\">$pre</span><span class=\"currencySymbol\">$short</span></span>";
		}
		else {
			$symbol = $pre.$short;
		}
		$amount = $this->owner->getAmount();
		return (is_numeric($amount)) ? $this->owner->currencyLib->toCurrency($amount, array('symbol' => $symbol, 'display' => Zend_Currency::USE_SYMBOL)) : '';
	}

	/**
	 * returns a currency like this: 8,001.00 USD / 12.12 NZD
	 *
	 * @param Boolean $html
	 *
	 * @return String
	 */
	public function SymbolNumberAndCode($html = true){
		$symbol = self::get_short_symbol($this->owner->currency);
		if($html) {
			$symbol = "<span class=\"currencySymbol\">$symbol</span>";
		}
		$code = strtolower($this->owner->currency);
		if($html) {
			$code = "<span class=\"currencyHolder\">$code</span>";
		}
		$amount = $this->owner->getAmount();
		return (is_numeric($amount)) ?  $symbol.$this->owner->currencyLib->toCurrency($amount, array("symbol" => "")).$code : '';
	}

	/**
	 * returns the default format for a site for currency
	 *
	 * @param Boolean $html
	 *
	 * @return String
	 */
	function NiceDefaultFormat($html = true) {
		$function = EcommerceConfig::get('EcommerceMoney', 'default_format');
		return $this->owner->$function($html);
	}

}
