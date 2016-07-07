<?php

/**
 * Ez az osztály lehetővé teszi az e-mailek küldését.
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class Mail {
	/**
	 * A PHPMailer példánya, amellyel az e-mailt fogjuk küldeni
	 * @var PHPMailer
	 * @since 0.1
	 */
	private $Mailer;
	
	/**
	 * Létrehozza a Mail osztály egy új példányát
	 * 
	 * @throws EntryNotFoundException Ha a konfigurációs állományban nem szerepel a "Mail" bejegyzés
	 * @since 0.1
	 */
	public function __construct() {
		$this->Mailer = new PHPMailer();
		$this->Mailer->isHTML();
		$this->Mailer->CharSet = "UTF-8";
		
		$this->loadConfiguration();
	}
	
	/**
	 * Betölti a konfigurációs állományból a PHPMailer beállításait
	 * 
	 * @throws EntryNotFoundException Ha a konfigurációs állományban nem szerepel a "Mail" bejegyzés
	 * @since 0.1
	 */
	private function loadConfiguration() {
		foreach (Application::Config()->Mail as $Name => $Value) {
			$this->Mailer->$Name = $Value;
		}
	}
	
	/**
	 * Felvesz egy új címzettet.
	 * 
	 * @param string $Address A címzett e-mail címe
	 * @since 0.1
	 */
	public function addTo($Address) {
		$this->Mailer->addAddress($Address);
	}
	
	/**
	 * Felvesz egy e-mail címet, aki másolatot kap az e-mailről.
	 * 
	 * @param string $Address A címzett e-mail címe
	 * @since 0.1
	 */
	public function addCC($Address) {
		$this->Mailer->addCC($Address);
	}
	
	/**
	 * Felvesz egy e-mail címet, aki vak másolatot (blind copy) kap az e-mailről.
	 * 
	 * @param string $Address A címzett e-mail címe
	 * @since 0.1
	 */
	public function addBCC($Address) {
		$this->Mailer->addBCC($Address);
	}
	
	/**
	 * Megadja a küldés során észlelt hibák leírását.
	 * 
	 * @return string Az e-mail küldése során jelentkező hiba leírása
	 * @since 0.1
	 */
	public function getError() {
		return $this->Mailer->ErrorInfo;
	}
	
	/**
	 * Visszaadja a PHPMailer példányt, amellyel az e-mailt küldjük.
	 * 
	 * @return PHPMailer A PHPMailer példány
	 * @since 0.1
	 */
	public function getMailer() {
		return $this->Mailer;
	}
	
	/**
	 * Elküld egy e-mailt a megadott tárggyal és tartalommal.
	 * 
	 * @param string $Subject Az üzenet tárgya
	 * @param string $Message Az üzenet tartalma
	 * @return boolean True, ha sikerült elküldeni az e-mailt, false ha nem
	 * @since 0.1
	 */
	public function send($Subject, $Message) {
		$this->Mailer->Subject = $Subject;
		$this->Mailer->Body = $Message;
		
		return $this->Mailer->send();
	}
	
	/**
	 * Elküld egy e-mailt a megadott tárggyal és nézettel.
	 * 
	 * @param string $Subject Az üzenet tárgya
	 * @param string $View A nézet neve
	 * @param array $Args Egy tömb, amely a nézet paramétereit és értékeit tartalmazza
	 * @return boolean True, ha sikerült elküldeni az e-mailt, false ha nem
	 * @since 0.1
	 */
	public function sendView($Subject, $View, $Args = array()) {
		$TemplateFile = appDir() . "/template/mail.php";
		$ViewFile = appDir() . "/view/mail/$View.php";
		
		$MailTitle = "";
		
		ob_start();
		require $ViewFile;
		$MailContents = ob_get_contents();
		ob_clean();
		
		require $TemplateFile;
		$Result = ob_get_contents();
		ob_end_clean();
		
		$this->Mailer->Subject = $Subject;
		$this->Mailer->Body = $Result;
		
		return $this->Mailer->send();
	}
}
