<?php

require 'vendor/autoload.php';

use Bigcommerce\Api\Client as Bigcommerce;

class migrator {

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

	/**
	 * Parse CSV File
	 * @param  string $csvFile    Path to CSV File
	 * @param  array $csvHeaders CSV Headers
	 * @return array/bool
	 */
	function parseCSV($csvFile) {
		$products = [];

		$csvFileHandle = fopen($csvFile,'r');
		$csvFileHeaders = fgetcsv($csvFileHandle);
		$csvFileHeaderMap = array();

		foreach($csvFileHeaders as $key => $currentHeader) {
		    $header = str_replace(' ' , '_' , trim(strtolower($currentHeader)));
		    
			if(!in_array($header, $csvFileHeaderMap)) {
				$csvFileHeaderMap[$key] = $header;
			} else {
				$csvFileHeaderMap[$key] = $header . '_attr';
			}

		}

		$rowCount = 0;

		while(($data = fgetcsv($csvFileHandle, 0, ',')) !== FALSE) {
			$rowCount++;

			if($rowCount === 1)
				continue;

			$csvRow = [];

			foreach($data as $key => $value) {
				$csvRow[$csvFileHeaderMap[$key]] = trim($value);
			}

			if(isset($csvRow) && !empty($csvRow)) {
				$products[$csvRow['stock_number']] = $csvRow;
			}
		}

		if(!isset($products) && empty($products)) {
			return FALSE;
		} else {
			return $products;
		}
		
	}

	/**
	 * Get Product SKUs
	 * @param  array $products Array of products received from the CSV file -- The parseCSV function will set the product SKU to the array KEY
	 * @return array/bool
	 */
	function getSkus($products) {
		$SKUs = [];

		foreach($products as $SKU => $product) {
			$SKUs[] = $SKU;	
		}

		if(!isset($SKUs) && empty($SKUs)) {
			return FALSE;
		} else {
			return $SKUs;
		}
		
	}

	// READ
	function getProductBrand($productID){
		$product = Bigcommerce::getProduct($productID);
		$brandID = $product->brand_id;
		$brand = Bigcommerce::getBrand($brandID)->name;
		echo '<pre>';
		var_dump($brandID);
		var_dump($brand);
		echo '</pre>';

	}

}


