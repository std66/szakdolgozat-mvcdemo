<?php

/**
 * Ez az osztály a szülője minden modell osztálynak.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
abstract class Model {
	/**
	 * Ez a tömb tárolja a rekord mezőinek értékeit.
	 * @var array
	 * @since 0.1
	 */
	private $fields = array();
	
	/**
	 * Ez tárolja, hogy a rekord létezik-e már az adatbázisban, vagy sem.
	 * @var boolean
	 * @since 0.1
	 */
	private $isNewRecord;
	
	/**
	 * A tábla felépítéséről szóló információkat adja meg.
	 * 
	 * @return array A tábla szerkezetét tároló tömb
	 * @since 0.1
	 */
	public static function getModelInformations() { return array(); }
	
	/**
	 * Létrehoz egy új model példányt, amely nem szerepel még az adatbázisban.
	 * 
	 * @since 0.1
	 */
	public function __construct() {
		$this->isNewRecord = true;
	}
	
	/**
	 * Lekérdezi a rekord egy adott mezőjének értékét, vagy egy relációs
	 * hivatkozás eredményét adja vissza.
	 * 
	 * @param string $name A mező neve
	 * @return mixed A mező vagy lekérdezés vagy NULL, ha nincs beállítva ilyen érték
	 * @throws InvalidArgumentException Ha nincs $name nevű mezője a rekordnak
	 * @since 0.1
	 */
	public function __get($name) {
		//Ha van ilyen nevű mezője a rekordnak
		if (self::hasField($name)) {
			return (!isset($this->fields[$name])) ? NULL : $this->fields[$name];
		}
		
		//Ha van ilyen relációja a rekordnak
		$TableInfo = static::getModelInformations();
		if (array_key_exists($name, $TableInfo["Relations"])) {
			$Relation = $TableInfo["Relations"][$name];
			
			$Attributes = array();
			foreach ($Relation["Attributes"] as $FieldName => $ForeignFieldName) {
				$Attributes[$ForeignFieldName] = $this->$FieldName;
			}
			
			return $Relation["ClassName"]::getAllByAttributes($Attributes);
		}
		
		throw new InvalidArgumentException("Nincs ilyen mezője a táblának: $name");
	}
	
	/**
	 * Lekérdezi a rekord egy adott mezőjének értékét.
	 * 
	 * @param string $name A mező neve
	 * @param mixed A mező értéke vagy NULL érték
	 * @throws InvalidArgumentException Ha nincs $name nevű mezője a rekordnak
	 * @since 0.1
	 */
	public function __set($name, $value) {
		if (!self::hasField($name)) {
			throw new InvalidArgumentException("Nincs ilyen mezője a táblának: $name");
		}
		
		$this->fields[$name] = $value;
	}
	
	/**
	 * Megadja, hogy egy adott mezőnek van-e értéke vagy sem.
	 * 
	 * @param string $name A mező neve
	 * @return boolean True, ha van értéke, false ha nincs
	 * @throws InvalidArgumentException Ha nincs $name nevű mezője a rekordnak
	 * @since 0.1
	 */
	public function __isset($name) {
		if (!self::hasField($name)) {
			throw new InvalidArgumentException("Nincs ilyen mezője a táblának: $name");
		}
		
		return isset($this->fields[$name]);
	}
	
	/**
	 * A modellben tárolt adatokat asszociatív tömbként adja vissza. Amelyik
	 * érték nincs megadva, az NULL érték lesz. Az adatok megjelenítéséhez
	 * ajánlott ezt használni ahelyett, hogy közvetlenül adnánk át a nézetnek
	 * a modell példányt.
	 * 
	 * @param array $Fields A kiválasztandó mezők. NULL esetén minden mező bele lesz foglalva
	 * @return array A modellben tárolt adatok tömbje
	 * @since 0.1
	 */
	public function toArray($Fields = NULL) {
		$TableInfo = static::getModelInformations();
		$Result = array();
		
		if ($Fields == NULL) {
			foreach (array_keys($TableInfo["Fields"]) as $Field) {
				$Result[$Field] = $this->$Field;
			}
		}
		else {
			foreach ($Fields as $Field) {
				$Result[$Field] = $this->$Field;
			}
		}
		
		return $Result;
	}
	
	/**
	 * Egy tömbből betölti a mezők értékeit. Nem kötelező minden mező megadása.
	 * 
	 * @param array $Values A mezők nevét és értékeit tároló asszociatív tömb
	 * @throws ArgumentNullException Ha a $Values értéke NULL
	 * @since 0.1
	 */
	public function importAttributes($Values = array()) {
		if ($Values == NULL) {
			throw new ArgumentNullException("Values");
		}
		
		foreach ($Values as $Field => $Value) {
			if (static::hasField($Field)) {
				$this->$Field = $Value;
			}
		}
	}
	
	/**
	 * Beállítja, hogy ez a rekord az adatbázisból lett lekérdezve.
	 * 
	 * @since 0.1
	 */
	private function notNewRecord() {
		$this->isNewRecord = false;
	}
	
	/**
	 * Megadja, hogy van-e a táblának egy adott nevű mezője.
	 * 
	 * @param string $Name A kérdéses mező neve
	 * @throws ArgumentNullException Ha a Name értéke NULL
	 * @return boolean True, ha van ilyen mező a táblában, false ha nincs
	 * @since 0.1
	 */
	private static function hasField($Name) {
		if ($Name == NULL) {
			throw new ArgumentNullException("Name");
		}
		
		$TableInfo = static::getModelInformations();
		
		foreach ($TableInfo["Fields"] as $FieldName => $FieldInfo) {
			if ($FieldName == $Name) {
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	/**
	 * Elmenti a rekordot az adatbázisba.
	 * 
	 * @return boolean True, ha sikerült a rekord mentése, false ha nem
	 * @since 0.1
	 */
	public function save() {
		if (!$this->validateFields()) {
			throw new ValidationException("A rekord adatai nem felelnek meg a validálási szabályoknak.");
		}
		
		if ($this->isNewRecord) {
			$Result = $this->insert();
			if ($Result) {
				$this->notNewRecord();
				
				$PrimaryKey = static::getPrimaryKey();
				if (static::isAutoIncrementField($PrimaryKey)) {
					$this->fields[$PrimaryKey] = Application::DB()->insert_id;
				}
			}
			return $Result;
		}
		else {
			return $this->update();
		}
	}
	
	/**
	 * Megadja a tábla elsődleges kulcsának a nevét.
	 * 
	 * @return string A tábla elsődleges kulcsának neve
	 * @since 0.1
	 */
	private static function getPrimaryKey() {
		$TableInfo = static::getModelInformations();
		foreach ($TableInfo["Fields"] as $Field => $FieldInfo) {
			if (isset($FieldInfo["PrimaryKey"]) && $FieldInfo["PrimaryKey"] == TRUE) {
				return $Field;
			}
		}
		
		return NULL;
	}
	
	/**
	 * Validálja a rekord értékeit a táblainformációban megadottak alapján.
	 * 
	 * @return boolean True, ha a megadott értékek érvényesek, false ha nem
	 * @since 0.1
	 */
	private function validateFields() {
		$TableInfo = static::getModelInformations();
		
		foreach ($TableInfo["Fields"] as $FieldName => $FieldInfo) {
			if (isset($FieldInfo["Validation"])) {
				if (isset($FieldInfo["AutoIncrement"]) && !isset($this->$FieldName)) {
					continue;
				}
				
				$Validator = new $FieldInfo["Validation"]["Validator"](
					$FieldInfo["Validation"]["Parameters"]
				);
				
				if (!$Validator->isValid($this->fields[$FieldName])) {
					return FALSE;
				}
			}
		}
		
		return TRUE;
	}
	
	/**
	 * Törli a rekordot a táblából.
	 * 
	 * @return boolean True, ha sikerült a rekord törlése, false ha nem
	 * @throws DatabaseException Ha az elsődleges kulcs értéke NULL
	 * @since 0.1
	 */
	public function delete() {
		$PrimaryKey = static::getPrimaryKey();
		
		if (!isset($this->$PrimaryKey)) {
			throw new DatabaseException("Az elsődleges kulcs ($PrimaryKey) értéke NULL");
		}
		
		$TableInfo = static::getModelInformations();
		
		$Sql = new SqlDeleteBuilder($TableInfo["TableName"]);
		$Sql->where(static::getPrimaryKey(), "=", static::escape($this->$PrimaryKey));
		$Query = $Sql->buildSql();
		
		return Application::DB()->query($Query) === TRUE;
	}
	
	/**
	 * Beszúrja az új rekordot az adatbázisba.
	 * 
	 * @return boolean True, ha sikerült a beszúrás, false ha nem
	 * @since 0.1
	 */
	private function insert() {
		$TableInfo = static::getModelInformations();
		
		$Sql = new SqlInsertBuilder($TableInfo["TableName"]);
		$Row = array();
		foreach ($TableInfo["Fields"] as $Field => $FieldInfo) {
			if (!isset($this->fields[$Field]) && self::isAutoIncrementField($Field)) {
				$Row[$Field] = NULL;
			}
			else {
				$Row[$Field] = self::escape($this->fields[$Field]);
			}
		}
		
		$Sql->add($Row);
		
		$Query = $Sql->buildSql();
		return Application::DB()->query($Query) === TRUE;
	}
	
	/**
	 * Frissíti a rekord adatait az adatbázisban. Az elsődleges kulcs nem lesz
	 * frissítve még akkor sem, ha a modellben megváltozott az értéke.
	 * 
	 * @return boolean True, ha sikerült a frissítés, false ha nem
	 * @since 0.1
	 */
	private function update() {
		$TableInfo = static::getModelInformations();
		$PrimaryKey = static::getPrimaryKey();
		
		$Sql = new SqlUpdateBuilder($TableInfo["TableName"]);
		$Sql->where($PrimaryKey, "=", $this->$PrimaryKey);
		
		foreach ($TableInfo["Fields"] as $Field => $FieldInfo) {
			if ($Field != $PrimaryKey) {
				$Sql->set($Field, self::escape($this->$Field));
			}
		}
		
		$Query = $Sql->buildSql();
		return Application::DB()->query($Query) === TRUE;
	}
	
	/**
	 * Egy adott mezőről megmondja, hogy az AutoIncrement mező-e.
	 * 
	 * @param string $Field A kérdéses mező neve
	 * @return boolean True, ha auto-increment mező, false ha nem
	 * @since 0.1
	 */
	private static function isAutoIncrementField($Field) {
		$TableInfo = static::getModelInformations();
		
		$FieldInfo = $TableInfo["Fields"][$Field];
		return isset($FieldInfo["AutoIncrement"]) && $FieldInfo["AutoIncrement"] == TRUE;
	}
	
	/**
	 * Visszaadja a tábla összes rekordját.
	 * 
	 * @return array A tábla rekordjait reprezentáló modell példányok tömbje
	 * @since 0.1
	 */
	public static function getAll() {
		$DB = Application::DB();
		
		$TableInfo = static::getModelInformations();
		
		$QueryBuilder = new SqlSelectBuilder($TableInfo["TableName"]);
		foreach ($TableInfo["Fields"] as $FieldName => $FieldInfo) {
			$QueryBuilder->selectField($FieldName);
		}
		$Query = $QueryBuilder->buildSql();
		
		$ResultSet = $DB->query($Query);
		if ($ResultSet === FALSE) {
			throw new DatabaseException(sprintf(
				"<pre>MySQL Error %d: %s\nQuery: %s</pre>",
				$DB->errno,
				$DB->error,
				$Query
			));
		}
		
		$Result = array();
		while ($ResultObject = $ResultSet->fetch_object($TableInfo["ClassName"])) {
			$ResultObject->notNewRecord();
			$Result[] = $ResultObject;
		}
		
		$ResultSet->close();
		
		return $Result;
	}
	
	/**
	 * Elsődleges kulcs alapján kiválaszt egy rekordot a táblából.
	 * 
	 * @param mixed $PrimaryKeyValue Az elsődleges kulcs értéke
	 * @return mixed A kiválasztott rekordot reprezentáló modell példány vagy false, ha nem sikerült a lekérdezés
	 * @throws ArgumentNullException Ha a $PrimaryKeyValue értéke NULL
	 * @since 0.1
	 */
	public static function getByPk($PrimaryKeyValue) {
		if ($PrimaryKeyValue == NULL) {
			throw new ArgumentNullException("PrimaryKeyValue");
		}
		
		$TableInfo = static::getModelInformations();
		
		$Sql = new SqlSelectBuilder($TableInfo["TableName"]);
		foreach ($TableInfo["Fields"] as $FieldName => $FieldInfo) {
			$Sql->selectField($FieldName);
		}
		$Sql->where(static::getPrimaryKey(), "=", static::escape($PrimaryKeyValue));
		
		$ResultSet = Application::DB()->query($Sql->buildSql());
		
		if ($ResultSet) {
			$Result = $ResultSet->fetch_object($TableInfo["ClassName"]);
			$Result->notNewRecord();
			$ResultSet->close();
			
			return $Result;
		}
		else {
			return false;
		}
	}
	
	/**
	 * Adott feltételek alapján választ ki egy vagy több rekordot.
	 * 
	 * @param array $Attributes Az attribútumokat és értékeiket tároló asszociatív tömb
	 * @return array A tábla rekordjait reprezentáló modell példányok tömbje
	 * @throws ArgumentNullException Ha a $Attributes értéke NULL
	 * @since 0.1
	 */
	public static function getAllByAttributes($Attributes) {
		if ($Attributes == NULL) {
			throw new ArgumentNullException("Attributes");
		}
		
		$TableInfo = static::getModelInformations();
		
		$QueryBuilder = new SqlSelectBuilder($TableInfo["TableName"]);
		foreach ($TableInfo["Fields"] as $FieldName => $FieldInfo) {
			$QueryBuilder->selectField($FieldName);
		}
		foreach ($Attributes as $Field => $Value) {
			if (!static::hasField($Field)) {
				throw new InvalidArgumentException("Nincs ilyen mezője a táblának: $Field");
			}
			
			if ($QueryBuilder->hasConditions()) {
				$QueryBuilder->andWhere($Field, "=", $Value);
			}
			else {
				$QueryBuilder->where($Field, "=", $Value);
			}
		}
		$Query = $QueryBuilder->buildSql();
		
		$ResultSet = Application::DB()->query($Query);
		
		$Result = array();
		while ($ResultObject = $ResultSet->fetch_object($TableInfo["ClassName"])) {
			$ResultObject->notNewRecord();
			$Result[] = $ResultObject;
		}
		
		$ResultSet->close();
		
		return $Result;
	}
	
	/**
	 * Elsődleges kulcs alapján töröl egy rekordot a táblából.
	 * 
	 * @param mixed $PrimaryKeyValue Az elsődleges kulcs értéke. Nem lehet NULL.
	 * @throws ArgumentNullException Ha a PrimaryKeyValue értéke NULL
	 * @returns boolean True ha sikerült a törlés, false ha nem
	 * @since 0.1
	 */
	public static function deleteByPk($PrimaryKeyValue) {
		$TableInfo = static::getModelInformations();
		
		$Query = new SqlDeleteBuilder($TableInfo["TableName"]);
		$Query->where(static::getPrimaryKey(), "=", static::escape($PrimaryKeyValue));
		
		return Application::DB()->query($Query->buildSql()) === TRUE;
	}
	
	/**
	 * Attribútumok alapján töröl rekordokat.
	 * 
	 * @param array $Attributes A feltételben foglalt mezők és hozzá tartozó értékek tömbje
	 * @return boolean True, ha sikerült a rekordok törlése, false ha nem
	 * @throws ArgumentNullException Ha a $Attributes értéke NULL
	 * @since 0.1
	 */
	public static function deleteByAttributes($Attributes) {
		if ($Attributes == NULL) {
			throw new ArgumentNullException("Attributes");
		}
		
		$TableInfo = static::getModelInformations();
		
		$Query = new SqlDeleteBuilder($TableInfo["TableName"]);
		foreach ($Attributes as $Field => $Value) {
			if (!$Query->hasConditions()) {
				$Query->where($Field, "=", static::escape($Value));
			}
			else {
				$Query->andWhere($Field, "=", static::escape($Value));
			}
		}
		
		return Application::DB()->query($Query->buildSql()) === TRUE;
	}
	
	/**
	 * Escape-el egy megadott értéket. Ajánlott ezt használni az értékek
	 * lekérdezésbe való illesztése előtt.
	 * 
	 * @param string $Value Az escape-elendő érték
	 * @return string Az escape-elt érték
	 * @since 0.1 
	 */
	private static function escape($Value) {
		return Application::DB()->escape_string($Value);
	}
}