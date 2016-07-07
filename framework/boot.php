<?php

$Requires = array(
	'framework/Exceptions/ApplicationException.php',
	'framework/ExceptionHandler.php',
	'framework/Autoloader.php'
);

foreach ($Requires as $File) {
	require $File;
}

/**
 * Rövidebb név az Environment::newLine-hoz.
 * 
 * @return string Az operációs rendszeren használt újsor karakter
 * @since 0.1
 */
function nl() {
	return Environment::newLine();
}

/**
 * Rövidebb név az Application::getInstance()->getApplicationDir()-hoz. Ez csak
 * az Application::createInstance() után hívható meg.
 * 
 * @return string Az alkalmazás fájljait tartalmazó könyvtár
 * @since 0.1
 */
function appDir() {
	return Application::getInstance()->getApplicationDir();
}