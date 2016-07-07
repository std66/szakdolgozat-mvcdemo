<?php

/**
 * Ez a kivétel akkor váltódik ki, ha már fut az alkalmazás egy példánya, és
 * új példányt szeretnénk indítani.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class ApplicationAlreadyRunningException extends ApplicationException {
	
}
