<?php

/**
 * Ez a kivétel akkor váltódik ki, ha egy olyan argumentum értéke null, amelynél
 * az nem megengedett.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class ArgumentNullException extends InvalidArgumentException {
	/**
	 * Az argumentum neve, amely nem lehet NULL.
	 * @var string
	 * @since 0.1
	 */
	private $Argument;
	
	/**
	 * Létrehozza az ArgumentNullException osztály egy példányát.
	 * 
	 * @param string $Argument Az argumentum neve, amelynek értéke nem lehet NULL
	 * @throws ArgumentNullException Akkor váltódik ki, ha $Argument értéke NULL
	 * @since 0.1
	 */
	public function __construct($Argument) {
		if ($Argument == NULL)
			throw new ArgumentNullException("Argument");
		
		parent::__construct("A(z) $Argument argumentum értéke nem lehet NULL.");
		$this->Argument = $Argument;
	}
	
	/**
	 * Megadja annak az argumentumnak a nevét, amely nem lehet NULL.
	 * 
	 * @return string Az argumentum neve
	 * @since 0.1
	 */
	public function getArgument() {
		return $this->Argument;
	}
}
