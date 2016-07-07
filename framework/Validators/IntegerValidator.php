<?php

/**
 * Egy értéket egész számként validál.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class IntegerValidator extends TypeValidator {
	/**
	 * Létrehozza az IntegerValidator egy új példányát a megadott konfigurációval.
	 * A konfigurációban kötelezően szerepelnie kell: allowNull (boolean).
	 * Szerepelhet továbbá: minimum (integer), maximum (integer).
	 * 
	 * @param array $Configuration A validátor konfigurációja
	 * @throws ValidatorException Ha az "allowNull" nincs megadva
	 * @since 0.1
	 */
	public function __construct($Configuration) {
		$Configuration["type"] = "integer";
		parent::__construct($Configuration);
	}
	
	/**
	 * Megvizsgálja, hogy a megadott érték megfelel-e a konfigurációban foglaltaknak.
	 * 
	 * @param mixed $Value A vizsgálandó érték
	 * @return boolean True, ha megfelel, false ha nem
	 * @since 0.1
	 */
	public function isValid($Value) {
		if (!parent::isValid($Value)) {
			return false;
		}
		
		if (isset($this->minimum) && $Value < $this->minimum) {
			return false;
		}
			
		if (isset($this->maximum) && $Value > $this->maximum) {
			return false;
		}
			
		return true;
	}
}