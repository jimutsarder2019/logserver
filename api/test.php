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
        ->set('host', '103.150.7.26')
        ->set('user', 'test')
        ->set('pass', 'test');

// Initiate client with config object
$client = new Client($config);

// Get list of all available profiles with name Block
$query = new Query('/ppp/secret/print');
$query->where('service', 'pppoe');
$secrets = $client->query($query)->read();


print_r($secrets);

?>
