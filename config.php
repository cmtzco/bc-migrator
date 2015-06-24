<?php 

/** Set Timezone */
date_default_timezone_set('America/Los_Angeles');
ini_set('auto_detect_line_endings', TRUE);

/** Require Migrator */
require 'migrator.php';

$arguments = $argv;
$script_name = str_replace('.php', '', $arguments[0]);

/** Start Time */
$start_time = time();

/** Create Log File */
$log_file = 'logs/' . $script_name . '_' . $start_time . '.txt';
$current = 'Starting RLM Migration at ' . date('l jS \of F Y h:i:s A') . PHP_EOL . '----------------------------------------------------------------' . PHP_EOL;
file_put_contents($log_file, $current);

$apiUsername = 'scripti_api';
$apiPath = 'https://store-hrebw.mybigcommerce.com/';
$apiToken = '70aa140d6f0548178fe676e16802f2e011f48d29';

$migrator = new migrator();

// $migrator->basicAuth($apiPath, $apiUsername, $apiToken);
// $migrator->checkSSL(false);
// $connection = $migrator->testConnection();

// if(!$connection) {
// 	$migrator->writeToLog($log_file, 'Unable to successfully connect to the Bigcommerce store. Aborting.');
// 	die();
// }
