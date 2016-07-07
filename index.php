<?php

$ApplicationDir = './protected/';

require 'framework/boot.php';
require $ApplicationDir . 'config.php';

RegisterAutoloader($ApplicationDir);

Application::enableDebug();
Application::createApplication($ApplicationDir, $config);
Application::getInstance()->run();