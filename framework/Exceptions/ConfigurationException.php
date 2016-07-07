<?php

/**
 * Egy, az alkalmazás-konfigurációt érintő kivételt reprezentál.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class ConfigurationException extends ApplicationException {
	/**
	 * Új ConfigurationException példányt hoz létre.
	 * 
	 * @param string $message A kivétel üzenete
	 * @param array $errors A kivételhez társított hibák tömbje
	 */
	public function __construct($message, $errors = array()) {
		$result = $message;
		
		foreach ($errors as $error) {
			$result .= "<br>" . $error;
		}
		
		parent::__construct($result);
	}
}