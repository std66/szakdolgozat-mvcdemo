<?php

/**
 * Egy fájlrendszerbeli útvonalat reprezentál.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class Path {
	/**
	 * Egy útvonalban az elválasztó karaktert kijavítja az operációs rendszeren
	 * használt karakterre.
	 * 
	 * @param string $Path A javítandó útvonal
	 * @return string A javított útvonal
	 */
	public static function fixSeparator($Path) {
		return str_replace(
			["\\", "/"], 
			[Environment::pathDelimiter(), Environment::pathDelimiter()], 
			$Path
		);
	}
}
