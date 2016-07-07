<?php

/**
 * Egy controller/action útvonalat reprezentál.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class Route {
	/**
	 * A controller neve
	 * @var string
	 * @since 0.1
	 */
	private $Controller;
	
	/**
	 * Az action neve
	 * @var string
	 * @since 0.1
	 */
	private $Action;
	
	/**
	 * Létrehozza a Route osztály egy új példányát a megadott útvonal alapján
	 * 
	 * @param string $Route A feldolgozandó útvonal
	 * @throws ValidationException Ha az útvonal nem érvényes
	 */
	public function __construct($Route) {
		$Validator = new RouteValidator([
			"allowNull" => false,
			"allowEmpty" => false
		]);
		
		if (!$Validator->isValid($Route)) {
			throw new ValidationException("A(z) $Route nem szabályos útvonal");
		}
		
		$this->parse($Route);
	}
	
	/**
	 * Felbontja az útvonalat.
	 * 
	 * @param string $Route Az útvonal string-ként
	 * @since 0.1
	 */
	private function parse($Route) {
		$parts = explode("/", $Route);
		
		$this->Controller = $parts[0];
		$this->Action = $parts[1];
	}
	
	/**
	 * Visszaadja a controller nevét
	 * 
	 * @return string A controller neve
	 * @since 0.1
	 */
	public function getController() {
		return $this->Controller;
	}
	
	/**
	 * Visszaadja a controller osztály nevét
	 * 
	 * @return string A controller osztály neve
	 * @since 0.1
	 */
	public function getControllerClass() {
		return ucfirst($this->Controller) . "Controller";
	}
	
	/**
	 * Megadja a controller fájl teljes útvonalát.
	 * 
	 * @return string A controller fájl útvonala
	 * @since 0.1
	 */
	public function getControllerFile() {
		return appDir() . "controller/" . $this->getControllerClass() . ".php";
	}
	
	/**
	 * Megadja a controllerhez tartozó nézeteket tartalmazó könyvtárat.
	 * 
	 * @return string A nézeteket tartalmazó könyvtár
	 * @since 0.1
	 */
	public function getViewDirectory() {
		return appDir() . "view/" . $this->getController() . "/";
	}
	
	/**
	 * Visszaadja az action nevét
	 * 
	 * @return string Az action neve
	 * @since 0.1
	 */
	public function getAction() {
		return $this->Action;
	}
	
	/**
	 * Visszaadja az action metódus nevét
	 * 
	 * @return string Az action metódus neve
	 * @since 0.1
	 */
	public function getActionMethod() {
		return "action" . ucfirst($this->Action);
	}
	
	/**
	 * Visszaadja az aktuális útvonalat reprezentáló Route példányt. Az útvonalat
	 * a $_GET["r"] értékből határozza meg, vagy ha az nincs megadva, akkor
	 * a paraméterként kapott alapértelmezett útvonalat használja.
	 * 
	 * @param string $DefaultRoute Az alapértelmezett útvonal
	 * @return Route A Route osztály egy példánya
	 * @throws ValidationException Ha az útvonal nem érvényes
	 * @since 0.1
	 */
	public static function getRoute($DefaultRoute) {
		$Route = isset($_GET["r"]) ? $_GET["r"] : $DefaultRoute;
		return new Route($Route);
	}
	
	/**
	 * Visszaadja az útvonal string-reprezentációját.
	 * 
	 * @return string Az útvonal string-reprezentációja
	 * @since 0.1
	 */
	public function __toString() {
		return $this->Controller . '/' .$this->Action;
	}
}
