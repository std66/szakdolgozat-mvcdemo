<?php

/**
 * Regisztrálja az automatikus osztálybetöltőt.
 * 
 * @param string$ApplicationDir Az alkalmazás fájljait tartalmazó könyvtár
 * @throws ApplicationException Ha az autoloader-t nem sikerült regisztrálni
 * @since 0.1
 */
function RegisterAutoloader($ApplicationDir) {
	define("APP_DIR", $ApplicationDir);
	
	$RegisterStatus = spl_autoload_register(function($Classname) {
		$SearchPaths = array(
			"./framework/Core/",
			"./framework/Database/",
			"./framework/IO/",
			"./framework/Exceptions/",
			"./framework/Validators/",
			"./framework/Libraries/PHPMailer/",

			APP_DIR . "controller/",
			APP_DIR . "model/",
			APP_DIR . "library/"
		);

		foreach ($SearchPaths as $IncludeDir) {
			$Filename = $IncludeDir . $Classname . '.php';
			if (file_exists($Filename)) {
				require_once $Filename;
				break;
			}
		}
	});

	if (!$RegisterStatus) {
		throw new ApplicationException("Az automatikus osztálybetöltőt nem sikerült regisztrálni");
	}
}