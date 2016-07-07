<?php

/**
 * Ez a kivétel akkor váltódik ki, ha az alkalmazás példányához szeretnénk
 * hozzáférni akkor, amikor még nem fut.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class ApplicationNotRunningException extends ApplicationException {
	
}
