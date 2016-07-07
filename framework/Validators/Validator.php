<?php

/**
 * A validátorok alapját képező absztrakt osztály.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
abstract class Validator {
	/**
	 * A validátor konfigurációját tároló tömb
	 * @var array
	 * @since 0.1
	 */
	private $Configuration;
	
	/**
	 * Beállítja az új validátor példány konfigurációját.
	 * 
	 * @param array $Configuration A validátor konfigurációja
	 * @throws ArgumentNullException A $Configuration nem lehet NULL
	 * @since 0.1
	 */
	public function __construct($Configuration) {
		if ($Configuration == NULL) {
			throw new ArgumentNullException("Configuration");
		}
		
		$this->Configuration = $Configuration;
	}
	
	/**
	 * Visszaadja a konfigurációs jegyzékből a megadott nevű bejegyzés értékét.
	 * 
	 * @param string $name A bejegyzés neve
	 * @return mixed A bejegyzés értéke
	 * @throws EntryNotFoundException Akkor váltódik ki, ha a bejegyzés nem létezik
	 * @since 0.1
	 */
	public function __get($name) {
		if (!isset($this->Configuration[$name])) {
			throw new EntryNotFoundException($name);
		}
		
		return $this->Configuration[$name];
	}
	
	/**
	 * Ellenőrzi, hogy a konfigurációs jegyzékben létezik-e az adott bejegyzés.
	 * 
	 * @param string $name A bejegyzés neve
	 * @return boolean True ha létezik, false ha nem
	 * @since 0.1
	 */
	public function __isset($name) {
		return isset($this->Configuration[$name]);
	}
	
	/**
	 * Validál egy megadott értéket.
	 * 
	 * @param mixed $Value A validálandó érték
	 * @return boolean True, ha az érték érvényes, false ha nem.
	 * @since 0.1
	 */
	public abstract function isValid($Value);
}
