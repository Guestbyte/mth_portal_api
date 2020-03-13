<?php
require '../vendor/autoload.php';
require './credentials.php';
require './lib/MailChimp.class.php';
require './lib/MailChimp.extends.class.php';
require './lib/route_mailchimp_subscribe.php';
require './lib/route_woocommerce_webhooks.php';
require './lib/route_woocommerce_webhooks_bulk.php';
require './lib/API.class.php';
require './lib/MTH.class.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use \DrewM\MailChimp\MailChimp;

$log = new Logger('name');
$log->pushHandler(new StreamHandler('audit.log', Logger::WARNING));

$MailChimp = new MTH_Mailchimp(MAILCHIMP_KEY);

$MTH = new MTH();

header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET POST");
header("Access-Control-Allow-Headers: Authorization");

wh_log('==================[ Incoming Request ]==================');

$API = new API('/api/homolog');
$API->route('/woocommerce/webhooks/', 'route_woocommerce_webhooks')
    ->route('/mailchimp/subscribe/', 'route_mailchimp_subscribe', $_POST)
    ->route('/woocommerce/webhooks/bulk/', 'route_woocommerce_webhooks_bulk')
    ->catchError();

/**
 * Write message in log file 
 *
 * @param string $msg
 * @return void
 */
function wh_log(string $msg){ global $log; $log->warning($msg); }