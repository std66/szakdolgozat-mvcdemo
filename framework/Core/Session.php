<?php

/**
 * A munkamenet-kezelésért felelős osztály
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class Session {
	
	/**
	 * Elindítja a munkamenetet
	 * 
	 * @return boolean True, ha sikerült elindítani, false ha nem
	 * @throws SessionException Letiltott munkamenet-kezelés vagy már létező munkamenet esetén
	 * @since 0.1
	 */
	public static function start() {
		if (self::isDisabled()) {
			throw new SessionException("A munkamenetek le vannak tiltva");
		}
		
		if (self::isActive()) {
			throw new SessionException("Már van megnyitott munkamenet");
		}
		
		return session_start();
	}
	
	/**
	 * Visszaadja a munkamenet-azonosítót
	 * 
	 * @return string A munkamenet-azonosító
	 * @throws SessionException Letiltott munkamenet-kezelés vagy nem létező munkamenet esetén
	 * @since 0.1
	 */
	public static function getID() {
		if (self::isDisabled()) {
			throw new SessionException("A munkamenetek le vannak tiltva");
		}
		
		if (!self::isActive()) {
			throw new SessionException("Előbb el kell indítania egy munkamenetet");
		}
		
		return session_id();
	}
	
	/**
	 * Megadja, hogy le van-e tiltva a munkamenet-kezelés.
	 * 
	 * @return boolean True, ha le van tiltva, false ha nem
	 * @since 0.1
	 */
	public static function isDisabled() {
		return session_status() == PHP_SESSION_DISABLED;
	}
	
	/**
	 * Megadja, hogy van-e aktív munkamenet
	 * 
	 * @return boolean True, ha van aktív munkamenet, false ha nincs
	 * @since 0.1
	 */
	public static function isActive() {
		return session_status() == PHP_SESSION_ACTIVE;
	}
	
	/**
	 * Regisztrál egy új értéket a munkamenetben, vagy ha már létezik, felülírja
	 * a meglévőt.
	 * 
	 * @param string $Name Az érték neve
	 * @param mixed $Value Az érték
	 * @throws ArgumentNullException Ha a Name vagy a Value értéke NULL
	 * @throws SessionException Ha a munkamenet még nem lett elindítva
	 * @since 0.1
	 */
	public static function set($Name, $Value) {
		if (!isset($Name)) {
			throw new ArgumentNullException("Name");
		}
		else if (!isset($Value)) {
			throw new ArgumentNullException("Value");
		}
		
		if (!self::isActive()) {
			throw new SessionException("Előbb el kell indítania egy munkamenetet");
		}
		
		$_SESSION[$Name] = $Value;
	}
	
	/**
	 * Visszaad egy már korábban regisztrált névhez tartozó értéket
	 * 
	 * @param string $Name A lekérdezendő érték neve
	 * @return mixed Az érték
	 * @throws ArgumentNullException Ha a Name értéke NULL
	 * @throws SessionException Ha a munkamenet még nem lett elindítva
	 * @throws EntryNotFoundException Ha a Name érték még nem lett regisztrálva
	 * @since 0.1
	 */
	public static function get($Name) {
		if (!isset($Name)) {
			throw new ArgumentNullException("Name");
		}
		
		if (!self::isActive()) {
			throw new SessionException("Előbb el kell indítania egy munkamenetet");
		}
		
		if (!self::is_set($Name)) {
			throw new EntryNotFoundException($Name);
		}
		
		return $_SESSION[$Name];
	}
	
	/**
	 * Megadja, hogy egy adott nevű érték regisztrálva van-e.
	 * 
	 * @param string $Name Az ellenőrizendő név
	 * @return boolean True, ha már regisztrálva van, false ha még nincs
	 * @throws ArgumentNullException Ha a Name értéke NULL
	 * @throws SessionException Ha a munkamenet még nem lett elindítva
	 * @since 0.1
	 */
	public static function is_set($Name) {
		if (!isset($Name)) {
			throw new ArgumentNullException("Name");
		}
		
		if (!self::isActive()) {
			throw new SessionException("Előbb el kell indítania egy munkamenetet");
		}
		
		return isset($_SESSION[$Name]);
	}
	
	/**
	 * Törli a megadott névhez tartozó értéket.
	 * 
	 * @param string $Name Az érték neve
	 * @throws ArgumentNullException Ha a Name értéke NULL
	 * @throws SessionException Ha a munkamenet még nem lett elindítva
	 * @throws EntryNotFoundException Ha a Name nevű érték még nem lett regisztrálva
	 * @since 0.1
	 */
	public static function un_set($Name) {
		if (!isset($Name)) {
			throw new ArgumentNullException("Name");
		}
		
		if (!self::isActive()) {
			throw new SessionException("Előbb el kell indítania egy munkamenetet");
		}
		
		if (!self::is_set($Name)) {
			throw new EntryNotFoundException($Name);
		}
		
		unset($_SESSION[$Name]);
	}
	
	/**
	 * Törli a munkamenetben regisztrált összes értéket.
	 * 
	 * @throws SessionException Ha a munkamenet még nem lett elindítva
	 * @since 0.1
	 */
	public static function unsetAll() {
		if (!self::isActive()) {
			throw new SessionException("Előbb el kell indítania egy munkamenetet");
		}
		
		session_unset();
	}
	
	/**
	 * Visszaadja a munkamenet nevét
	 * 
	 * @return string A munkamenet neve
	 * @throws SessionException Ha a munkamenet még nem lett elindítva
	 * @since 0.1
	 */
	public static function getName() {
		if (!self::isActive()) {
			throw new SessionException("Előbb el kell indítania egy munkamenetet");
		}
		
		return session_name();
	}
	
	/**
	 * Törli a munkamenet során regisztrált összes értéket és lezárja a
	 * munkamenetet
	 * 
	 * @throws SessionException Ha a munkamenet még nem lett elindítva
	 * @since 0.1
	 */
	public static function close() {
		if (!self::isActive()) {
			throw new SessionException("Előbb el kell indítania egy munkamenetet");
		}
		
		session_unset();
		session_destroy();
	}
}
