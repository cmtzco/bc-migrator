<?php 

require 'config.php';

$options = getopt('', array("csv:", "sku_column:"));

if(empty($options['csv']) && empty($options['sku_column'])) {
	echo 'Missing Options.';
	die();
}

// Get CSV Data
$csvPath = './data/' . $options['csv'] . '.csv';

if(isset($csvPath) && !empty($csvPath)) {
	// $csvHeaders = $migrator->parseCSVHeaders($csvPath);
	$products = $migrator->parseCSV($csvPath, str_replace(' ', '_', strtolower($options['sku_column'])));
	// $productSKUs = $migrator->getSkus($products);
	$productData = $migrator->getTestData($products);

	$availableColors = ['Acid','Black','Blue','Camo','Clear','Fatigue','Gold','Green','Orange','Pink','Poler','Red','Silver','Smoke','White','Yellow'];

	echo 'Product Count: ' . count($products);

	$groupedProducts = [];

	if(isset($products) && !empty($products)) {
		foreach($products as $sku => $product) {

			// All SKUs Associated With THis Product
			$allSkus = [];

			foreach($productData as $productSKU => $testData) {
				if(strstr($productSKU, $sku) !== FALSE) {
					if($testData['catname'] === $product['powersport_type_catname'] && $testData['manufacture'] === $product['manufacture']) {
						$allSkus[$sku] = array(
							'sku'  => $productSKU,
							'name' => $testData['product_name']
						);	
					}
				}
			}

			if(isset($allSkus) && !empty($allSkus)) {
				$groupedProducts[] = $allSkus;
			}
			
		}


		print_r($groupedProducts);
	}
	
}