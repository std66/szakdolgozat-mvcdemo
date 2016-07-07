<?php

/**
 * Ez a kivétel egy nem létező bejegyzés kérése esetén váltódik ki.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class EntryNotFoundException extends ApplicationException {
	/**
	 * A kért nem létező bejegyzés neve
	 * @var string
	 * @since 0.1
	 */
	private $RequestedEntry;
	
	/**
	 * Létrehozza az EntryNotFoundException osztály egy új példányát.
	 * 
	 * @param string $RequestedEntry A nem létező bejegyzés neve
	 * @since 0.1
	 */
	public function __construct($RequestedEntry) {
		parent::__construct(
			"A(z) $RequestedEntry nem található."
		);
		
		$this->RequestedEntry = $RequestedEntry;
	}
	
	/**
	 * Visszaadja a kért nem létező bejegyzés nevét
	 * 
	 * @return string A nem létező bejegyzés neve
	 * @since 0.1
	 */
	public function getRequestedEntry() {
		return $this->RequestedEntry;
	}
}