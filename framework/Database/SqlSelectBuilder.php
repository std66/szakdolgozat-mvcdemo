<?php

/**
 * Egy SELECT lekérdezést épít fel.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class SqlSelectBuilder extends SqlConditionalQuery {
	/**
	 * A főtábla neve
	 * @var string
	 */
	private $TableName;
	
	/**
	 * A lekérdezendő oszlopok
	 * @var array
	 */
	private $SelectColumns = array();
	
	/**
	 * Az összekapcsolandó táblák
	 * @var array 
	 */
	private $JoinWith = array();
	
	/**
	 * Létrehozza az SqlSelectBuilder osztály egy példányát.
	 * 
	 * @param string $TableName A főtábla neve
	 * @throws InvalidArgumentException Ha a $TableName értéke NULL vagy üres string
	 * @since 0.1
	 */
	public function __construct($TableName) {
		if (empty($TableName)) {
			throw new InvalidArgumentException('A TableName nem lehet üres');
		}
		
		parent::__construct($TableName);

		$this->TableName = $TableName;
	}
	
	/**
	 * Felvesz egy új, lekérdezendő mezőt.
	 * 
	 * @param string $Field A mező neve
	 * @param string $FromTable A mezőt tartalmazó tábla neve. Ha NULL, az aktuális tábla lesz használva
	 * @param string $Alias Ha nem NULL, akkor alias nevet rendel a mezőhöz
	 * @since 0.1
	 */
	public function selectField($Field, $FromTable = NULL, $Alias = NULL) {
		$Result = "";
		$Table = (isset($FromTable)) ? $FromTable : $this->TableName;
		$Result .= "`$Table`.";
		$Result .= "`$Field`";
		if (isset($Alias)) {
			$Result .= " AS '$Alias'";
		}
		
		$this->SelectColumns[] = $Result;
	}
	
	/**
	 * Inner join-nal összekapcsolja a táblát egy megadott táblával.
	 * 
	 * @param string $Table Az összekapcsolandó tábla neve
	 * @param string $PrimaryKey Az elsődleges kulcsot tartalmazó mező neve
	 * @param string $ForeignKey Az idegen kulcsot tartalmazó mező neve
	 * @since 0.1
	 */
	public function innerJoin($Table, $PrimaryKey, $ForeignKey) {
		$this->join("INNER", $Table, $PrimaryKey, $ForeignKey);
	}
	
	/**
	 * Left join-nal összekapcsolja a táblát egy megadott táblával.
	 * 
	 * @param string $Table Az összekapcsolandó tábla neve
	 * @param string $PrimaryKey Az elsődleges kulcsot tartalmazó mező neve
	 * @param string $ForeignKey Az idegen kulcsot tartalmazó mező neve
	 * @since 0.1
	 */
	public function leftJoin($Table, $PrimaryKey, $ForeignKey) {
		$this->join("LEFT", $Table, $PrimaryKey, $ForeignKey);
	}
	
	/**
	 * Right join-nal összekapcsolja a táblát egy megadott táblával.
	 * 
	 * @param string $Table Az összekapcsolandó tábla neve
	 * @param string $PrimaryKey Az elsődleges kulcsot tartalmazó mező neve
	 * @param string $ForeignKey Az idegen kulcsot tartalmazó mező neve
	 * @since 0.1
	 */
	public function rightJoin($Table, $PrimaryKey, $ForeignKey) {
		$this->join("RIGHT", $Table, $PrimaryKey, $ForeignKey);
	}
	
	/**
	 * Felépíti a JOIN részét a lekérdezésnek.
	 * 
	 * @param string $JoinType Az összekapcsolás típusa
	 * @param string $Table Az összekapcsolandó tábla neve
	 * @param string $PrimaryKey Az elsődleges kulcsot tartalmazó mező neve
	 * @param string $ForeignKey Az idegen kulcsot tartalmazó mező neve
	 * @since 0.1
	 */
	private function join($JoinType, $Table, $PrimaryKey, $ForeignKey) {
		$this->JoinWith[] = sprintf(
			"%s JOIN `%s` ON (`%s`.`%s` = `%s`.`%s`)",
			$JoinType,
			$Table,
			$this->TableName,
			$PrimaryKey,
			$Table,
			$ForeignKey
		);
	}
	
	/**
	 * Elkészíti a lekérdezést.
	 * 
	 * @return string Az elkészített lekérdezés
	 * @since 0.1
	 */
	public function buildSql() {
		//SELECT rész
		$Result = array("SELECT");
		$Result[] = implode(', ', $this->SelectColumns);
		
		//FROM rész
		$Result[] = "FROM";
		$Result[] = "`{$this->TableName}`";
		$Result[] = implode(' ', $this->JoinWith);
		
		//WHERE rész
		if ($this->hasConditions()) {
			$Result[] = "WHERE";
			$Result[] = $this->getConditions();
		}
		
		return implode(' ', $Result) . ';';
	}
}
