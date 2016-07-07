<?php

/**
 * Ez az osztály a felhasználó web ágenséről ad információkat.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class Client {
	
	/**
	 * Megadja a web-ágens leírását
	 * 
	 * @return string A felhasználó web ágensének leírása
	 * @since 0.1
	 */
	public static function getAgentDescriptor() {
		return $_SERVER["HTTP_USER_AGENT"];
	}
	
	/**
	 * Megvizsgálja, hogy a kérés valamilyen script-től érkezett-e. Jelenleg
	 * támogatottak: Windows PowerShell, cURL, wget.
	 * 
	 * @return boolean True ha igen, false ha nem
	 * @since 0.1
	 */
	public static function isScriptingClient() {
		$Find = array(
			"WindowsPowerShell",
			"curl",
			"Wget"
		);
		
		$CustomScriptingAgents;
		try {
			$CustomScriptingAgents = Application::Config()->CustomScriptAgents;
		} catch (EntryNotFoundException $ex) {
			$CustomScriptingAgents = array();
		}
		
		$UserAgent = strtolower(self::getAgentDescriptor());
		
		foreach (array_merge($CustomScriptingAgents, $Find) as $ScriptUserAgent) {
			if (strpos($UserAgent, strtolower($ScriptUserAgent)) !== false) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Megvizsgálja, hogy a kérés valamilyen mobileszközről érkezett-e.
	 * 
	 * @return boolean True ha mobileszközről érkezett a kérés, false ha nem
	 * @since 0.1
	 */
	public static function isMobile() {
		$Find = array(
			"android",
			"iphone",
			"ipad",
			"mobile",
			"windows phone"
		);
		
		$UserAgent = strtolower(self::getAgentDescriptor());
		
		foreach ($Find as $MobileUserAgent) {
			if (strpos($UserAgent, strtolower($MobileUserAgent)) !== false) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Visszaadja a kliens által elfogadott nyelveket. A függvény egy kétdimenziós
	 * tömböt ad vissza, ahol az első dimenzió tartalmazza az adott nyelv adatait
	 * (lang: a nyelv azonosítója; q: a nyelv minősítése, ha adott).
	 * 
	 * @return array A kliens által elfogadott nyelveket tartalmazó tömb.
	 * @since 0.1
	 */
	public static function acceptLanguages() {
		$Result = array();
		foreach (explode(',', $_SERVER["HTTP_ACCEPT_LANGUAGE"]) as $Current) {
			$Parts = explode(';', $Current);
			
			$CurrentLocale = array();
			$CurrentLocale["lang"] = trim($Parts[0]);
			
			if (count($Parts) == 2) {
				$CurrentLocale["q"] = substr($Parts[1], 2);
			}
			
			$Result[] = $CurrentLocale;
		}
		
		return $Result;
	}
	
	/**
	 * Visszaadja a kliens IP címét.
	 * 
	 * @return string A kliens IP címe
	 * @since 0.1
	 */
	public static function ipAddress() {
		return $_SERVER["REMOTE_ADDR"];
	}
}
