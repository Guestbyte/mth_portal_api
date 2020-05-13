<?php
require '../vendor/autoload.php';
require './credentials.php';
require './lib/MailChimp.class.php';
require './lib/MailChimp.extends.class.php';
require './lib/route_mailchimp_subscribe.php';
require './lib/route_woocommerce_webhooks.php';
require './lib/route_woocommerce_webhooks_bulk.php';
require './lib/route_woocommerce_subscriptions.php';
require './lib/route_mailchimp_ciranda2020.php';
require './lib/route_moodle_relatorio.php';
require './lib/API.class.php';
require './lib/MTH.class.php';

require './lib/SimpleCache.php';
$cache = new Gilbitron\Util\SimpleCache();
$cache->cache_path = '/var/www/html/api/cache/';
$cache->cache_time = 86400;

// define('DOING_AJAX', true);
// define('SHORTINIT', true);
// require '/var/www/html/wp-load.php';

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

// ini_set('display_startup_errors', 1);
// ini_set('display_errors', 1);
// error_reporting(-1);

wh_log('==================[ Incoming Request ]==================');

$API = new API('/api/v3');
$API->route('/woocommerce/webhooks/', 'route_woocommerce_webhooks')
    ->route('/mailchimp/subscribe/', 'route_mailchimp_subscribe', $_POST)
    ->route('/woocommerce/webhooks/bulk/', 'route_woocommerce_webhooks_bulk')
    ->route('/woocommerce/subscriptions/', 'route_woocommerce_subscriptions')
    ->route('/mailchimp/ciranda2020/', 'route_mailchimp_ciranda2020')
     ->route('/moodle/progress/csv/', 'route_moodle_progress_csv')
    ->catchError();

wh_log('==================[ End Request ]==================');

/**
 * Write message in log file 
 *
 * @param string $msg
 * @return void
 */
function wh_log(string $msg){ global $log; $log->warning($msg); }