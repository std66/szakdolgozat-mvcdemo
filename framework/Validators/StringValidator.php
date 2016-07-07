<?php

/**
 * Egy értéket string-ként validál.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class StringValidator extends TypeValidator {
	
	/**
	 * Létrehozza a StringValidator egy új példányát a megadott konfigurációval.
	 * A konfigurációban kötelezően szerepelnie kell: allowNull (boolean), allowEmpty (boolean).
	 * Szerepelhet továbbá: pattern (string), minLength (integer), maxLength (integer).
	 * 
	 * @param array $Configuration A validátor konfigurációja
	 * @throws ValidatorException Ha az "allowNull" vagy az "allowEmpty" nincs megadva
	 * @since 0.1
	 */
	public function __construct($Configuration) {
		$Configuration["type"] = "string";
		parent::__construct($Configuration);
		
		if (!isset($this->allowEmpty)) {
			throw new ValidatorException("Az allowEmpty megadása kötelező.");
		}
	}
	
	/**
	 * Megvizsgálja, hogy az adott érték string-e, illetve megfelel a
	 * konfigurációban írtaknak.
	 * 
	 * @param mixed $Value A vizsgálandó érték
	 * @return boolean True ha az érték string és megfelel a konfigurációnak, false ha nem
	 * @since 0.1
	 */
	public function isValid($Value) {
		if (!parent::isValid($Value)) {
			return false;
		}
		
		if (!$this->emptyValidation($Value)) {
			return false;
		}
		
		if (!$this->regexValidation($Value)) {
			return false;
		}
		
		if (!$this->lengthValidation($Value)) {
			return false;
		}
		
		return true;
	}

	/**
	 * Megvizsgálja, hogy az adott string megfelel-e az "allowEmpty" feltételnek.
	 * 
	 * @param string $Value A vizsgálandó string
	 * @return boolean True, ha megfelel, false ha nem
	 * @since 0.1
	 */
	private function emptyValidation($Value) {
		if (!$this->allowEmpty && empty($Value)) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Megvizsgálja, hogy az adott string illeszkedik-e az adott regex-re.
	 * 
	 * @param string $Value A vizsgálandó string
	 * @return boolean True, ha illeszkedik vagy nem kell vizsgálni, false ha kell vizsgálni de nem illeszkedik
	 * @since 0.1
	 */
	private function regexValidation($Value) {
		if (!isset($this->pattern)) {
			return true;
		}
		
		return preg_match($this->pattern, $Value);
	}
	
	/**
	 * Megvizsgálja, hogy a string megfelel-e a hosszával támasztott
	 * követelményeknek.
	 * 
	 * @param string $Value A vizsgálandó string
	 * @return boolean True ha megfelel vagy nem kell vizsgálni, false ha kell vizsgálni de nem felel meg
	 * @since 0.1
	 */
	private function lengthValidation($Value) {
		$length = strlen($Value);
		
		if (isset($this->minLength) && $length < $this->minLength) {
			return false;
		}
		
		if (isset($this->maxLength) && $length > $this->maxLength) {
			return false;
		}
		
		return true;
	}
}