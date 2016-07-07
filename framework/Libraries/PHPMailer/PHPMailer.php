<?php

$Requires = array(
	"class.phpmailer.php",
	"class.phpmaileroauth.php",
	"class.phpmaileroauthgoogle.php",
	"class.pop3.php",
	"class.smtp.php"
);

foreach ($Requires as $File) {
	$Filename = "./framework/Libraries/PHPMailer/$File";
	require_once $Filename;
}