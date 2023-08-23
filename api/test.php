<?php
require_once __DIR__ . '/vendor/autoload.php';

error_reporting(E_ALL);

use \RouterOS\Config;
use \RouterOS\Client;
use \RouterOS\Query;

// Create config object with parameters
$config =
    (new Config())
        ->set('timeout', 1)
        ->set('host', '103.102.216.1')
        ->set('user', 'api')
        ->set('pass', 'log_api');

// Initiate client with config object
$client = new Client($config);

// Get list of all available profiles with name Block
$query = new Query('/ppp/active/print');
$query->where('service', 'pppoe');
$secrets = $client->query($query)->read();


print_r($secrets);

?>
