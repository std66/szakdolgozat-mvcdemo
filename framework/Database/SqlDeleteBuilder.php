<?php

/**
 * Ez az osztály egy Delete SQL lekérdezést épít fel.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class SqlDeleteBuilder extends SqlConditionalQuery {
	/**
	 * A tábla nevét tárolja, amelyből törlünk.
	 * @var string
	 * @since 0.1
	 */
	private $TableName;
	
	/**
	 * Létrehozza az SqlDeleteBuilder egy új példányát, amely a megadott táblának
	 * rekordjait fogja törölni.
	 * 
	 * @param string $TableName A tábla neve, amelyből törlünk
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
	 * Felépíti a Delete lekérdezést
	 * 
	 * @return string A Delete lekérdezés szövege
	 * @since 0.1
	 */
	public function buildSql() {
		$Result = array("DELETE FROM");
		$Result[] = '`'.$this->TableName.'`';
		
		if ($this->hasConditions()) {
			$Result[] = "WHERE";
			$Result[] = $this->getConditions();
		}
		
		return implode(' ', $Result);
	}
}
