<?php

/**
 * Ez az osztály biztosítja a feltételes lekérdezések kezelésének módját.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
abstract class SqlConditionalQuery {
	/**
	 * A feltételeket tárolja
	 * @var array
	 * @since 0.1
	 */
	private $Where = array();
	
	/**
	 * A tábla nevét tárolja
	 * @var string
	 * @since 0.1
	 */
	private $TableName;
	
	/**
	 * Beállítja az SqlConditionalQuery osztály által kezelt tábla nevét.
	 * 
	 * @param string $TableName
	 * @since 0.1
	 */
	public function __construct($TableName) {
		$this->TableName = $TableName;
	}
	
	/**
	 * Megadja a legelső feltételt. Ha már előtte voltak megadva feltételek,
	 * azok törlődni fognak.
	 * 
	 * @param string $Column A mező neve, amelyre feltételt szabunk meg
	 * @param string $Operator Az SQL operátor
	 * @param mixed $Expression A kifejezés, amelyre vizsgáljuk a mező értékét
	 * @param string $Table A tábla neve vagy NULL, ha az aktuális táblára vonatkozik
	 * @since 0.1
	 */
	public function where($Column, $Operator, $Expression, $Table = NULL) {
		$this->Where = array(
			$this->buildWhere($Column, $Operator, $Expression, $Table)
		);
	}
	
	/**
	 * A már meglévő feltételekhez egy új feltételt kapcsol AND operátorral.
	 * 
	 * @param string $Column A mező neve, amelyre feltételt szabunk meg
	 * @param string $Operator Az SQL operátor
	 * @param mixed $Expression A kifejezés, amelyre vizsgáljuk a mező értékét
	 * @param string $Table A tábla neve vagy NULL, ha az aktuális táblára vonatkozik
	 * @since 0.1
	 */
	public function andWhere($Column, $Operator, $Expression, $Table = NULL) {
		$this->Where[] = "AND " . $this->buildWhere($Column, $Operator, $Expression, $Table);
	}
	
	/**
	 * A már meglévő feltételekhez egy új feltételt kapcsol OR operátorral.
	 * 
	 * @param string $Column A mező neve, amelyre feltételt szabunk meg
	 * @param string $Operator Az SQL operátor
	 * @param mixed $Expression A kifejezés, amelyre vizsgáljuk a mező értékét
	 * @param string $Table A tábla neve vagy NULL, ha az aktuális táblára vonatkozik
	 * @since 0.1
	 */
	public function orWhere($Column, $Operator, $Expression, $Table = NULL) {
		$this->Where[] = "OR " . $this->buildWhere($Column, $Operator, $Expression, $Table);
	}
	
	/**
	 * Megadja, hogy a lekérdezésnek vannak-e feltételei
	 * 
	 * @return boolean TRUE ha van feltétele, FAlSE ha nincs
	 * @since 0.1
	 */
	public function hasConditions() {
		return count($this->Where) > 0;
	}
	
	/**
	 * Elkészíti és visszaadja a lekérdezés feltétel-részét.
	 * 
	 * @return string A lekérdezés feltételei
	 * @since 0.1
	 */
	public function getConditions() {
		return implode(' ', $this->Where);
	}
	
	/**
	 * Felépít egy feltételt.
	 * 
	 * @param string $Column A mező neve, amelyre feltételt szabunk meg
	 * @param string $Operator Az SQL operátor
	 * @param mixed $Expression A kifejezés, amelyre vizsgáljuk a mező értékét
	 * @param string $Table A tábla neve vagy NULL, ha az aktuális táblára vonatkozik
	 * @return string Az elkészített feltétel
	 * @since 0.1
	 */
	private function buildWhere($Column, $Operator, $Expression, $Table = NULL) {
		if ($Table == NULL) {
			$Table = $this->TableName;
		}
		
		switch (gettype($Expression)) {
			case "string":
				$Expression = "'$Expression'";
			break;
		
			case "NULL":
				$Expression = "NULL";
			break;
		
			case "boolean":
				$Expression = ($Expression === TRUE) ? "TRUE" : "FALSE";
			break;
		}
		
		return sprintf("`%s`.`%s` %s %s",
			$Table,
			$Column,
			$Operator,
			$Expression
		);
	}
}
