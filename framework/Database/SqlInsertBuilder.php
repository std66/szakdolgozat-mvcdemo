<?php

/**
 * SQL Insert Into lekérdezést építő osztály
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class SqlInsertBuilder {
	/**
	 * A tábla neve, amelybe az új rekordokat vesszük fel.
	 * @var string
	 * @since 0.1
	 */
	private $Table;
	
	/**
	 * A táblába beszúrandó értékeket tároló tömb
	 * @var array
	 * @since 0.1;
	 */
	private $Values = array();
	
	/**
	 * Létrehozza az SqlInsertBuilder osztály egy új példányát a megadott
	 * táblanév alapján.
	 * 
	 * @param string $Table A tábla neve, amelybe beszúrjuk a rekordokat
	 * @since 0.1
	 */
	public function __construct($Table) {
		$this->Table = $Table;
	}
	
	/**
	 * Felvesz egy új beszúrandó rekordot. Paraméterként egy olyan tömböt kell
	 * megadni, amelynek kulcsai a rekord mezői, és értékei a mezők értékei.
	 * Fontos, hogy a mezők mindig ugyanolyan sorrendben szerepeljenek.
	 * 
	 * @param array $RowValues A rekord kulcsai és értékei.
	 * @since 0.1
	 */
	public function add($RowValues) {
		$Row = array();
		
		foreach ($RowValues as $Column => $Value) {
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
		
			$Row[$Column] = $Value;
		}
		
		$this->Values[] = $Row;
	}
	
	/**
	 * Elkészíti az Insert Into lekérdezés szövegét.
	 * 
	 * @return string A lekérdezés szövege, vagy üres string, ha egy rekord sem lett hozzáadva
	 * @since 0.1
	 */
	public function buildSql() {
		if (count($this->Values) == 0) {
			return "";
		}
		
		$Result = array("INSERT INTO");
		$Result[] = "`{$this->Table}`";
		
		$Columns = array();
		foreach (array_keys($this->Values[0]) as $Column) {
			$Columns[] = "`$Column`";
		}
		
		$Result[] = sprintf('(%s)', implode(', ', $Columns));
		$Result[] = "VALUES";
		
		$Inserts = array();
		foreach ($this->Values as $CurrentRow) {
			$Inserts[] = '(' . implode(', ', array_values($CurrentRow)) . ')';
		}
		
		$Result[] = implode(', ', $Inserts);
		
		return implode(' ', $Result);
	}
}
