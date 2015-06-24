<?php

require 'vendor/autoload.php';

use Bigcommerce\Api\Client as Bigcommerce;

class migrator extends Bigcommerce{

	function basicAuth($storeUrl, $username, $apiKey) {
		Bigcommerce::configure(array(
		    'store_url' => $storeUrl,
		    'username'  => $username,
		    'api_key'   => $apiKey
		));
		Bigcommerce::setCipher('RC4-SHA');
		
	}
	function checkSSL($bool){
		Bigcommerce::verifyPeer($bool);
	}
	function testConnection() {
		$ping = Bigcommerce::getTime();

		if ($ping && !empty($ping)) {
			echo $ping->format('H:i:s') . "\n";
			echo "Connected!";
			return true;
		} 
		else { 
			return "Could Not Connect! Check Credentials. "; 
		}
	}
	function parseCSVHeaders($csvFile){

		$csvFileHandle = fopen($csvFile,'r');
		$csvFileHeaders = fgetcsv($csvFileHandle);
		$csvFileHeaderMap = array();

		foreach($csvFileHeaders as $key => $currentHeader) {
		    $csvFileHeaderMap[trim($currentHeader)] = $key;
		}
		echo "\r\nHeaders\r\n";
		print_r($csvFileHeaderMap);
		return $csvFileHeaderMap;
	}

	// CREATE 
	function createCustomFields($productID, $customFieldName, $customFieldText){
		if(isset($productID) && !empty($productID)){
			try {
				$setCustomField = Bigcommerce::createProductCustomField($productID, array(
					'name' => $customFieldName,
					'text' => $customFieldText,
				));
			}
			catch(Bigcommerce\Api\Error $error) {
			    echo $error->getCode();
			    echo $error->getMessage();
			}

		} else {
			echo "No Product ID to Update";
		}
	}

	//this is a test parser.  Do not use without some modification. 
	function tempParseCSV($csvFile){

		$csvFileHandle = fopen($csvFile,'r');
		$csvFileHeaders = fgetcsv($csvFileHandle);
		$csvFileHeaderMap = array();

		foreach($csvFileHeaders as $key => $currentHeader) {
		    $csvFileHeaderMap[trim($currentHeader)] = $key;
		}
	
		$lastProduct = NULL;
		while(($data = fgetcsv($csvFileHandle, 0, ',')) !== false) {
			$productID = $data[$csvFileHeaderMap['ProductID']];
			$universalFit = $data[$csvFileHeaderMap['UniversalFit']];
			$fieldCreator = self::createCustomFields($productID, 'Universal Fit', $universalFit);
			echo '<pre>';
			var_dump($fieldCreator);
			echo '</pre>';
		}
		echo "All Custom Fields Added";
			
	}

	// READ
	function getProductBrand($productID){
		$product = Bigcommerce::getProduct($productID);
		$brandID = $product->brand_id;
		if($brandID != 0 && !empty($brandID)){
			$brand = Bigcommerce::getBrand($brandID)->name;
		}
		else {
			$brand = "No Brand Assigned";
		}
		return $brand;
		
	}
 
	function getProductIDs(){

		$count = Bigcommerce::getProductsCount();
		$pages = ceil($count / 250);

		for ($i = 1; $i <= $pages; $i++) {
			$products = Bigcommerce::getProducts(array(
			  "page" => $i, "limit" => 250)
			);
			foreach($products as $product){
				$productIDs[] = $product->id;
			}
		}
		return $productIDs;
	}


}


