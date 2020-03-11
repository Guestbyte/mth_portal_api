<?php
require '../vendor/autoload.php';
require './MailChimp.php';
require './credentials.php';
require './functions.php';
require './lib/mthRoute.class.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use \DrewM\MailChimp\MailChimp;

$log = new Logger('name');
$log->pushHandler(new StreamHandler('audit.log', Logger::WARNING));

$MailChimp = new MailChimp(MAILCHIMP_KEY);

header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET POST");
header("Access-Control-Allow-Headers: Authorization");

wh_log('==================[ Incoming Request ]==================');

$API = new API('/api/homolog');
$API->route('/', 'baseRoute', $data)
    ->route('/woocommerce/webhooks/', 'route_woocommerce_webhooks')
    ->route('/mailchimp/subscribe/', 'route_mailchimp_subscribe', $_POST)
    ->route('/woocommerce/webhooks/bulk/', 'route_woocommerce_webhooks_bulk');