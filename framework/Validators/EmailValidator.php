<?php

/**
 * Egy értéket e-mail címként validál. Ez a validátor a PHP filter_var
 * függvényét használja fel.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class EmailValidator extends StringValidator {
	
	/**
	 * Létrehozza az EmailValidator egy új példányát a megadott konfigurációval.
	 * A konfigurációban kötelezően szerepelnie kell: allowNull (boolean), allowEmpty (boolean).
	 * 
	 * @param array $Configuration A validátor konfigurációja
	 * @throws ValidatorException Ha az "allowNull" vagy az "allowEmpty" nincs megadva
	 * @since 0.1
	 */
	public function __construct($Configuration) {
		parent::__construct($Configuration);
	}
	
	/**
	 * Megvizsgálja, hogy a megadott érték szabályos e-mail cím-e.
	 * 
	 * @param mixed $Value A vizsgálandó érték
	 * @return boolean True, ha szabályos e-mail cím, false ha nem.
	 * @since 0.1
	 * @todo Ez vajon üres string-re mit mond?
	 */
	public function isValid($Value) {
		if (!parent::isValid($Value)) {
			return false;
		}
		
		return filter_var($Value, FILTER_VALIDATE_EMAIL);
	}
}