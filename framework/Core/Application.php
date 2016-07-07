<?php

/**
 * Az alkalmazás futtatásáért felelős osztály
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class Application {
	/**
	 * Az alkalmazás futó példánya
	 * @var Application
	 * @since 0.1
	 */
	private static $Instance = null;
	
	/**
	 * Az alkalmazás debug módban fut-e
	 * @var boolean
	 * @since 0.1
	 */
	private static $Debug = false;
	
	/**
	 * Beállítja, hogy az alkalmazás debug módban fusson. Éles környezetben ne
	 * használja! Ezt a createApplication előtt kell meghívnia.
	 * 
	 * @throws ApplicationAlreadyRunningException Ezt nem lehet meghívni, ha már fut az alkalmazás
	 * @since 0.1
	 */
	public static function enableDebug() {
		if (self::$Instance != NULL) {
			throw new ApplicationAlreadyRunningException("
				Az enableDebug metódust nem hívhatja meg, ha már fut az
				alkalmazás.
			");
		}
		
		error_reporting(E_ALL | E_STRICT);
		self::$Debug = true;
	}
	
	/**
	 * Megadja, hogy az alkalmazás debug módban fut-e.
	 * 
	 * @return boolean True, ha az alkalmazás debug módban fut, false, ha nem
	 * @since 0.1
	 */
	public static function isDebugging() {
		return self::$Debug;
	}
	
	/**
	 * Megadja a szoftver nevét.
	 * 
	 * @throws ApplicationNotRunningException Ha az alkalmazás példánya még nem fut
	 * @return string A szoftver neve
	 * @since 0.1
	 */
	public static function getName() {
		return Application::Config()->SoftwareName;
	}
	
	/**
	 * Megadja a szoftver verzióját.
	 * 
	 * @throws ApplicationNotRunningException Ha az alkalmazás példánya még nem fut
	 * @return string A szoftver verziója
	 * @since 0.1
	 */
	public static function getVersion() {
		return Application::Config()->Version;
	}
	
	/**
	 * Megadja a szoftver szerzőjének nevét.
	 * 
	 * @throws ApplicationNotRunningException Ha az alkalmazás példánya még nem fut
	 * @return string A szoftver szerzőjének neve
	 * @since 0.1
	 */
	public static function getAuthor() {
		return Application::Config()->Author;
	}
	
	/**
	 * Megadja a szoftver kiadási dátumát.
	 * 
	 * @throws ApplicationNotRunningException Ha az alkalmazás példánya még nem fut
	 * @return string A szoftver kiadási dátuma
	 * @since 0.1
	 */
	public static function getReleaseDate() {
		return Application::Config()->ReleaseDate;
	}
	
	/**
	 * Visszaadja az éppen futó alkalmazás konfigurációját kezelő Configuration
	 * példányt.
	 * 
	 * @return Configuration A konfigurációt kezelő osztálypéldány
	 * @throws ApplicationNotRunningException Akkor váltódik ki, ha az alkalmazás még nem fut
	 * @since 0.1
	 */
	public static function Config() {
		if (self::$Instance == NULL) {
			throw new ApplicationNotRunningException(
				"Az alkalmazás még nem fut."
			);
		}
		
		return self::$Instance->getConfig();
	}

	/**
	 * Betölti (és debug módban validálja) a konfigurációt, majd létrehozza
	 * ezek alapján az alkalmazás egy példányát
	 * 
	 * @param string $ApplicationDir Az alkalmazás fájljait tároló könyvtár
	 * @param array $Configuration A konfigurációt tároló tömb
	 * @throws ApplicationAlreadyRunningException Akkor váltódik ki, ha már fut az alkalmazás
	 * @throws InvalidArgumentException Ha a $ApplicationDir NULL vagy üres string
	 * @since 0.1
	 */
	public static function createApplication($ApplicationDir, $Configuration) {
		if (self::$Instance != null) {
			throw new ApplicationAlreadyRunningException(
				"Az alkalmazás egy példánya már fut."
			);
		}
		
		if (empty($ApplicationDir)) {
			throw new InvalidArgumentException(
				'A $ApplicationDir nem lehet NULL vagy üres string'	
			);
		}
		
		$Config = new Configuration($Configuration, self::isDebugging());
		
		self::$Instance = new Application($ApplicationDir, $Config);
	}
	
	/**
	 * Visszaadja az alkalmazás éppen futó példányát
	 * 
	 * @return Application Az éppen futó Application példány
	 * @throws ApplicationNotRunningException Ha az alkalmazás nem fut
	 * @since 0.1
	 */
	public static function getInstance() {
		if (self::$Instance == NULL) {
			throw new ApplicationNotRunningException(
				"Az alkalmazás még nem fut."
			);
		}
		
		return self::$Instance;
	}
	
	/**
	 * Visszaadja a MySQLi példányt, amely az adatbázishoz kapcsolódik.
	 * 
	 * @return MySQLi A MySQLi példány
	 * @throws ApplicationNotRunningException Ha az alkalmazás még nem fut
	 * @throws DatabaseException Ha az alkalmazás nem igényel MySQL kapcsolatot, vagy még nem kapcsolódott
	 * @since 0.1
	 */
	public static function DB() {
		return self::getInstance()->getDB();
	}
	
	/**
	 * A konfigurációt kezelő Configuration példány
	 * @var Configuration
	 * @since 0.1
	 */
	private $Config = NULL;
	
	/**
	 * A MySQLi példány, amely az adatbázishoz kapcsolódik.
	 * @var MySQLi
	 * @since 0.1
	 */
	private $MySQLi = NULL;
	
	/**
	 * Az alkalmazás fájljait tartalmazó könyvtár teljes elérési útvonala
	 * @var string
	 * @since 0.1
	 */
	private $ApplicationDir = NULL;
	
	/**
	 * Létrehozza az alkalmazás egy példányát
	 * 
	 * @param string $ApplicationDir Az alkalmazás fájljait tároló könyvtár
	 * @param Configuration $Configuration Az alkalmazás konfigurációja
	 * @throws ArgumentNullException Ha a $Configuration értéke NULL
	 * @since 0.1
	 */
	private function __construct($ApplicationDir, $Configuration) {
		if ($Configuration == NULL) {
			throw new ArgumentNullException("Configuration");
		}
		
		$this->Config = $Configuration;
		$this->ApplicationDir = $ApplicationDir;
	}
	
	/**
	 * Visszaadja egy konfigurációt kezelő Configuration osztálypéldányt.
	 * 
	 * @return Configuration A konfigurációt kezelő példány
	 * @since 0.1
	 */
	public function getConfig() {
		return $this->Config;
	}
	
	/**
	 * Lefuttatja az alkalmazást
	 * 
	 * @since 0.1
	 */
	public function run() {
		if (!$this->Config->AllowScriptClients && Client::isScriptingClient()) {
			print json_encode([
				"error" => "Ez a webhely nem teszi lehetővé az adatok elérését más alkalmazások számára."
			]);
			return;
		}
		
		Session::start();
		
		if ($this->Config->UseDB) {
			$this->connectDatabase(
				$this->Config->MySQL["Host"],
				$this->Config->MySQL["Username"],
				$this->Config->MySQL["Password"],
				$this->Config->MySQL["Schema"]
			);
		}
		
		$Route = Route::getRoute($this->Config->DefaultRoute);
		Controller::run($Route);
		
		if ($this->Config->UseDB) {
			$this->closeDatabase();
		}
	}
	
	/**
	 * Kapcsolatot épít ki a MySQL szerverrel.
	 * 
	 * @param string $Server A szerver IP címe vagy DNS neve
	 * @param string $Username A felhasználói fiók neve
	 * @param string $Password A felhasználói fiók jelszava
	 * @param string $Schema A séma neve
	 * 
	 * @throws DatabaseException Ha nem sikerül kapcsolódni az adatbázishoz vagy már van kapcsolat
	 * 
	 * @since 0.1
	 */
	private function connectDatabase($Server, $Username, $Password, $Schema) {
		if (isset($this->MySQLi)) {
			throw new DatabaseException("Már van egy adatbázis-kapcsolat nyitva.");
		}
		
		$this->MySQLi = @new mysqli($Server, $Username, $Password, $Schema);
		if ($this->MySQLi->connect_error) {
			throw new DatabaseException("Nem sikerült kapcsolódni az adatbázishoz: " . $this->MySQLi->connect_error);
		}
		
		if (!$this->MySQLi->set_charset("utf8")) {
			throw new DatabaseException("Nem sikerült az adatbázis-kapcsolat karakterkódolását beállítani.");
		}
	}
	
	/**
	 * Lezárja a kapcsolatot a MySQL szerverrel.
	 * 
	 * @throws DatabaseException Ha még nincs megnyitva a kapcsolat, vagy a meglévő kapcsolatot nem sikerült lezárni
	 * @since 0.1
	 */
	private function closeDatabase() {
		if (!isset($this->MySQLi)) {
			throw new DatabaseException("Nincs lezárandó adatbázis-kapcsolat.");
		}
		
		if (!$this->MySQLi->close()) {
			throw new DatabaseException("Nem sikerült lezárni az adatbázis-kapcsolatot.");
		}
	}
	
	/**
	 * Visszaadja a MySQLi példányt, amely az adatbázis-kiszolgálóhoz kapcsolódik.
	 * 
	 * @return MySQLi A MySQLi példány
	 * @throws DatabaseException Ha az alkalmazás nem igényel MySQL kapcsolatot, vagy még nem kapcsolódott
	 * @since 0.1
	 */
	public function getDB() {
		if (!$this->Config->UseDB) {
			throw new DatabaseException("Ez az alkalmazás nem igényel adatbázis-kapcsolatot.");
		}
		
		if ($this->MySQLi == NULL) {
			throw new DatabaseException("Az alkalmazás még nem kapcsolódott a MySQL szerverhez.");
		}
		
		return $this->MySQLi;
	}
	
	/**
	 * Megadja az alkalmazás fájljait tároló könyvtárat.
	 * 
	 * @return string Az alkalmazás fájljait tároló könyvtár útvonala
	 * @since 0.1
	 */
	public function getApplicationDir() {
		return $this->ApplicationDir;
	}
}