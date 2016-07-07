<?php

/**
 * Egy értéket controller/action útvonal szerint validál.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class RouteValidator extends StringValidator {
	
	/**
	 * Létrehozza a RouteValidator egy új példányát a megadott konfigurációval.
	 * A konfigurációban kötelezően szerepelnie kell: allowNull (boolean), allowEmpty (boolean).
	 * 
	 * @param array $Configuration A validátor konfigurációja
	 * @throws ValidatorException Ha az "allowNull" vagy az "allowEmpty" nincs megadva
	 * @since 0.1
	 */
	public function __construct($Configuration) {
		$Configuration["pattern"] = "/[a-z_]+[a-zA-Z0-9_]*\/[a-zA-Z0-9_]+/";
		parent::__construct($Configuration);
	}
}