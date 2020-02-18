<?php
$basepath = '/home2/elosed78/public_html';
require_once "$basepath/config/functions.php";
require_once "$basepath/config/meekrodb.2.3.class.php";
require_once "$basepath/config/credentials.php";
require_once "$basepath/config/KLogger.php";
require_once "$basepath/config/DW.class.php";
//require_once "$basepath/config/PHPTerminalProgressBar.php";

$log = new KLogger("$basepath/pages/log.txt", KLogger::DEBUG);
$dw = new DW();
$canvas = DB::queryFirstRow("SELECT * FROM canvas");
$api = new CanvasLMS($canvas['token'], $canvas['url']);

date_default_timezone_set('America/Sao_Paulo');