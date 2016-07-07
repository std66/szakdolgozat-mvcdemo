<?php

/**
 * Ez az osztály egy Update SQL lekérdezést épít fel.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class SqlUpdateBuilder extends SqlConditionalQuery {
	/**
	 * A tábla nevét tárolja
	 * @var string
	 * @since 0.1
	 */
	private $TableName;
	
	/**
	 * A beállítandó értékek
	 * @var array
	 * @since 0.1
	 */
	private $Set = array();
	
	/**
	 * Létrehozza az SqlUpdateBuilder egy új példányát, amely a megadott táblában
	 * lévő rekordokat fogja frissíteni.
	 * 
	 * @param string $TableName A frissítendő tábla neve
	 * @throws InvalidArgumentException Ha a $TableName értéke NULL vagy üres string
	 * @since 0.1
	 */
	public function __construct($TableName) {
		if (empty($TableName)) {
			throw new InvalidArgumentException('A $TableName nem lehet NULL vagy üres string');
		}
		
		parent::__construct($TableName);
		
		$this->TableName = $TableName;
	}
	
	/**
	 * Beállítja egy frissítendő mező új értékét.
	 * 
	 * @param string $Column A mező neve
	 * @param mixed $Value A mező új értéke
	 * @since 0.1
	 */
	public function set($Column, $Value) {
		switch (gettype($Value)) {
			case "boolean":
				$Value = ($Value == TRUE) ? "TRUE" : "FALSE";
			break;
		
			case "string":
				$Value = "'$Value'";
			break;
		
			case "NULL":
				$Value = "NULL";
			break;
		}
		
		$this->Set[] = '`'.$this->TableName.'`.`'.$Column.'` = '.$Value;
	}
	
	/**
	 * Felépíti az Update lekérdezést.
	 * 
	 * @return string Az Update SQL lekérdezés
	 * @sincce 0.1
	 */
	public function buildSql() {
		$Result = array('UPDATE', "`{$this->TableName}`", "SET");
		$Result[] = implode(', ', $this->Set);
		
		if ($this->hasConditions()) {
			$Result[] = "WHERE";
			$Result[] = $this->getConditions();
		}
		
		return implode(' ', $Result);
	}
}
