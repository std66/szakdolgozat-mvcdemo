<?php

/**
 * Naplózásért felelős osztály
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class Log {
	/**
	 * Megjegyzés szintű esemény
	 * @since 0.1
	 */
	const LEVEL_NOTICE = 0;
	
	/**
	 * Figyelmeztetés szintű esemény
	 * @since 0.1
	 */
	const LEVEL_WARNING = 1;
	
	/**
	 * Súlyos, de nem végzetes hiba szintű esemény
	 * @since 0.1
	 */
	const LEVEL_ERROR = 2;
	
	/**
	 * Végzetes hiba szintű esemény
	 * @since 0.1
	 */
	const LEVEL_FATAL_ERROR = 3;
	
	/**
	 * A hibakódok ember számára olvasható alakját tárolja
	 * @var array
	 * @since 0.1
	 */
	private static $VerboseLevels = array(
		self::LEVEL_NOTICE => "Megjegyzés",
		self::LEVEL_WARNING => "Figyelmeztetés",
		self::LEVEL_ERROR => "Hiba",
		self::LEVEL_FATAL_ERROR => "Végzetes hiba"
	);
	
	/**
	 * A mai naplófájlhoz hozzáfűz egy eseményt a mostani időponttal.
	 * 
	 * @param string $Scope Az esemény hatóköre (pl. osztálynév)
	 * @param integer $Level Az esemény szintje (ld. a LEVEL_* konstansok az osztályban)
	 * @param string $Message Az esemény szöveges leírása
	 * @param array $AssociatedData Az eseményhez társított adatok
	 * @throws IOException Ha a naplófájl nem létezik és nem sikerült létrehozni
	 * @throws InvalidArgumentException Ha a Scope, Level vagy a Message értéke nem megfelelő
	 * @throws ArgumentNullException Ha a Level értéke NULL
	 * @since 0.1
	 */
	public static function add($Scope, $Level, $Message, $AssociatedData = array()) {
		if (empty($Scope)) {
			throw new InvalidArgumentException('A $Scope megadása kötelező és nem lehet üres string.');
		}
		
		if (!isset($Level)) {
			throw new ArgumentNullException("Level");
		}
		else if (!array_key_exists($Level, self::$VerboseLevels)) {
			throw new InvalidArgumentException('A $Level értéke LEVEL_NOTICE, LEVEL_WARNING, LEVEL_ERROR vagy LEVEL_FATAL_ERROR lehet.');
		}
		
		if (empty($Message)) {
			throw new InvalidArgumentException('A $Message megadása kötelező és nem lehet üres string.');
		}
		
		$Filename = self::getLogDir() . date("Y-m-d") . '.log';
		
		if (!File::exists($Filename)) {
			if (!File::create($Filename)) {
				throw new IOException("Nem sikerült a naplófájl létrehozása: $Filename");
			}
		}
		
		$Time = time();
		
		File::appendString($Filename,
			"<entry>". nl() . json_encode(array(
				"level" => $Level,
				"levelVerbose" => self::$VerboseLevels[$Level],
				"scope" => $Scope,
				"message" => $Message,
				"associatedData" => $AssociatedData,
				"unixTime" => $Time,
				"verboseTime" => date("Y. m. d. H:i:s", $Time)
			)) . nl() . "</entry>" . nl() . nl()
		);
	}
	
	/**
	 * Megadja a naplófájlokat tároló könyvtárat.
	 * 
	 * @return string A naplófájlokat tároló könyvtár
	 * @since 0.1
	 */
	public static function getLogDir() {
		return appDir() . "log/";
	}
}
