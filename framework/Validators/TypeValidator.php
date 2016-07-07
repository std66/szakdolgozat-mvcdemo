<?php

/**
 * Egy értéket annak típusa alapján validál.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class TypeValidator extends Validator {
	/**
	 * A PHP beépített típusait tartalmazó tömb
	 * @var array
	 * @since 0.1
	 */
	private static $PHPTypes = array(
		"boolean",
		"integer",
		"double",
		"string",
		"object",
		"array",
		"NULL",
		"Resource"
	);
	
	/**
	 * Létrehozza a TypeValidator egy új példányát a megadott konfigurációval.
	 * A konfigurációs jegyzéknek tartalmaznia kell egy "type" (string) és egy 
	 * "allowNull" (boolean) kulcsú bejegyzést.
	 * 
	 * @param array $Configuration A validátor konfigurációs jegyzéke
	 * @throws ValidatorException Ha a "type" vagy "allowNull" hiányzik, vagy "type" értéke nem PHP beépített típus neve
	 * @throws ArgumentNullException A Configuration értéke NULL
	 * @since 0.1
	 */
	public function __construct($Configuration) {
		parent::__construct($Configuration);
		
		if (!isset($this->type)) {
			throw new ValidatorException("A type megadása kötelező.");
		}
		
		if (!in_array($this->type, self::$PHPTypes)) {
			throw new ValidatorException("Érvénytelen típusnév lett megadva.");
		}
		
		if (!isset($this->allowNull)) {
			throw new ValidatorException("Az allowNull megadása kötelező.");
		}
		else if (gettype($Configuration["allowNull"]) !== "boolean") {
			throw new ValidatorException("Az allowNull értékének boolean típusúnak kell lennie.");
		}
	}
	
	/**
	 * Validálja a megadott értéket.
	 * 
	 * @param mixed $Value A validálandó érték
	 * @return boolean True, ha az érték érvényes, false ha nem
	 */
	public function isValid($Value) {
		$ValueType = gettype($Value);
		
		if ($this->allowNull) {
			return $ValueType === $this->type || $ValueType === "NULL";
		}
		else {
			return $ValueType === $this->type;
		}
	}

}