<?php

/**
 * Ez az osztály az ősosztálya minden Controller osztálynak.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class Controller {
	/**
	 * Tárolja, hogy engedélyezve van-e a kimenet sablonra illesztése
	 * @var boolean
	 * @since 0.1
	 */
	private $useTemplate = true;
	
	/**
	 * Tárolja a controller/action útvonalat reprezentáló Route példányt.
	 * @var Route
	 * @since 0.1
	 */
	private $Route = NULL;
	
	/**
	 * Tárolja az oldal címét
	 * @var string
	 * @since 0.1
	 */
	protected $PageTitle = "";
	
	/**
	 * Létrehoz egy új Controller példányt a megadott Route felhasználásával.
	 * 
	 * @param Route $Route A controller/action útvonalat reprezentáló Route példány
	 * @since 0.1
	 */
	public function __construct($Route) {
		if ($Route == NULL) {
			throw new ArgumentNullException("Route");
		}
		
		$this->Route = $Route;
		
		$this->useTemplate = !Client::isScriptingClient();
	}

	/**
	 * Betölti és végrehajtja a vezérlőhöz tartozó megadott nézetet a
	 * megadott paraméterekkel
	 *
	 * @param string $View A nézet neve
	 * @param array $Args A nézetnek átadandó esetleges paraméterek
	 * @since 0.1
	 */
	protected function display($View, $Args = null) {
		if ($View == NULL) {
			throw new ArgumentNullException("View");
		}
		
		if (Client::isScriptingClient()) {
			print json_encode($Args);
		}
		else {
			$File = $this->Route->getViewDirectory();
			if (Application::Config()->UseMobileTemplate && Client::isMobile()) {
				$File .= "$View.mobile.php";
			}
			else {
				$File .= "$View.php";
			}
			
			if (file_exists($File)) {
				require $File;
			} else {
				throw new ApplicationException("Nincs ilyen nézet: $View ($File)");
			}
		}
	}
	
	/**
	 * Letiltja a nézet sablonra illesztését.
	 * 
	 * @since 0.1
	 */
	protected function disableTemplate() {
		$this->useTemplate = false;
	}
	
	/**
	 * Megadja a sablon útvonalát.
	 * 
	 * @return string A sablon útvonala
	 * @since 0.1
	 */
	public function getTemplate() {
		$Filename = "default.php";
		
		if (Application::Config()->UseMobileTemplate && Client::isMobile()) {
			$Filename = "mobile.php";
		}
		
		return appDir() . "template/" . Application::Config()->DefaultTemplate . "/" . $Filename;
	}
	
	/**
	 * Megadja, hogy a kimenetet sablonra lehet-e illeszteni.
	 * 
	 * @return boolean True, ha sablonra illeszthető, false ha nem
	 * @since 0.1
	 */
	public function templateEnabled() {
		return $this->useTemplate;
	}
	
	/**
	 * Az oldal címét adja meg.
	 * 
	 * @return string Az oldal címe
	 * @since 0.1
	 */
	public function getPageTitle() {
		return $this->PageTitle;
	}
	
	/**
	 * Átirányít a megadott útvonalra.
	 * 
	 * @param string $route Az útvonal (controller/action formában)
	 * @param array $args Az URI GET paramétereinek tömbje
	 * @throws ApplicationException Ha az útvonal formátuma érvénytelen
	 * @since 0.1
	 */
	public function redirect($route, $args = array()) {
		$Validator = new RouteValidator(array(
			'allowNull' => false,
			'allowEmpty' => false
		));
		
		if ($Validator->isValid($route)) {
			$Uri = array("Location: index.php?r=$route");
			
			if (count($args) > 0) {
				$Uri[] = "&";
				
				$Parameters = array();
				foreach ($args as $Key => $Value) {
					$Parameters[] = sprintf("%s=%s", urlencode($Key), urlencode($Value));
				}
				
				$Uri[] = implode('&', $Parameters);
			}
			
			header(implode('', $Uri));
		}
		else {
			throw new ApplicationException("Érvénytelen útvonal: $route");
		}
	}
	
	/**
	 * Lefuttat egy controller egy action metódusát egy Route példány alapján.
	 * 
	 * @param Route $Route A Route példány amely meghatározza a futtatandó controller/action-t
	 * @throws ApplicationException Ha a controller fájl nem létezik, vagy egy kötelező paraméter hiányzik
	 * @since 0.1
	 */
	public static function run($Route) {
		if (!file_exists($Route->getControllerFile())) {
			throw new ApplicationException("A " . $Route->getControllerFile() . " fájl nem létezik.");
		}
		
		//Példányosítjuk a Controller osztályt
		$ClassName = $Route->getControllerClass();
		$Instance = new $ClassName($Route);
		
		//Információt szerzünk az Action metódusról
		$ActionMethod = new ReflectionMethod($Instance, $Route->getActionMethod());
		$Arguments = array();
		
		foreach ($ActionMethod->getParameters() as $ParameterInfo) {
			$ParamName = $ParameterInfo->getName();
			$Value = @$_GET[$ParamName];
			if (!isset($Value) && !$ParameterInfo->isOptional()) {
				throw new ApplicationException("A '$ParamName' kötelező paraméter értéke nem lett megadva a GET kérésben");
			}
			else {
				$Arguments[] = $Value;
			}
		}
		
		ob_start();
		$ActionMethod->invokeArgs($Instance, $Arguments);
		$Output = ob_get_contents();
		ob_end_clean();
		
		if ($Instance->templateEnabled()) {
			$TemplateFile = $Instance->getTemplate();
			self::applyTemplate($TemplateFile, $Instance->getPageTitle(), $Output);
		}
		else {
			print $Output;
		}
	}
	
	/**
	 * Betölti a sablonfájlt és a kimenetet ráilleszti.
	 * 
	 * @param string $TemplateFile A sablonfájl teljes elérési útvonala
	 * @param string $PageTitle Az oldal címe
	 * @param string $Output Az oldal kimenete
	 */
	private static function applyTemplate($TemplateFile, $PageTitle, $Output) {
		require $TemplateFile;
	}
}
