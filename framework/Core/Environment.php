<?php

/**
 * Ez az osztály a futtatókörnyezetről ad információkat.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class Environment {
	/**
	 * Megadja a fájlrendszer elválasztókarakterét.
	 * 
	 * @return string Az elválasztó karakter
	 * @since 0.1
	 */
	public static function pathDelimiter() {
		return DIRECTORY_SEPARATOR;
	}
	
	/**
	 * Megadja a környezetben használt újsor karaktert.
	 * 
	 * @return string Az újsor karakter
	 * @since 0.1
	 */
	public static function newLine() {
		return PHP_EOL;
	}
	
	/**
	 * Megadja a PHP verzióját
	 * 
	 * @return string A PHP verziója
	 * @since 0.1
	 */
	public static function phpVersion() {
		return PHP_VERSION;
	}
	
	/**
	 * Megadja a webszerver szoftver leírását
	 * 
	 * @return string A webszerver leírása
	 * @throws EntryNotFoundException Ha a SERVER_SOFTWARE nem létezik a $_SERVER tömbben
	 * @since 0.1
	 */
	public static function webServer() {
		if (!isset($_SERVER["SERVER_SOFTWARE"])) {
			throw new EntryNotFoundException("SERVER_SOFTWARE");
		}
		
		return $_SERVER["SERVER_SOFTWARE"];
	}
	
	/**
	 * Megadja, hogy a szoftver Microsoft Windows környezetben fut-e.
	 * 
	 * @return boolean True, ha Windows rendszeren fut, false ha nem
	 * @since 0.1
	 */
	public static function isWindowsHost() {
		return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
	}
	
	/**
	 * Megadja a MySQL szerver verzióját. Ennek használatához az alkalmazásnak
	 * kapcsolódva kell lennie a MySQL szerverhez.
	 * 
	 * @return string A MySQL szerver verziója
	 * @since 0.1
	 */
	public static function mysqlVersion() {
		$Query = Application::DB()->query("SELECT version() AS 'mysql_version';");
		$Result = $Query->fetch_array();
		$Query->close();
		
		return $Result["mysql_version"];
	}
}
