<?php

//setup the BC-API 
require 'migrator.php';
$storeUrl = 'https://store-f50y1r.mybigcommerce.com/';
$username = 'scriptilabs';
$apiKey = 'c4709e020662fe501d6a707c7e507dc4c7225d1a';

$migrator = new migrator();

$migrator->basicAuth($storeUrl, $username, $apiKey);
$migrator->checkSSL(false);
$connection = $migrator->testConnection();
if ($connection){
	$csv = 'endresult_api.csv';
	$migrator->parseCSVHeaders($csv);
	$migrator->tempParseCSV($csv);
	
	//$migrator->createCustomFields($productID, $customFieldName, $customFieldText);
}
