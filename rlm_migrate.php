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
	$product_skus = $migrator->getSkus($products);

	var_dump($products);
	die();

	$availableColors = ['Acid','Black','Blue','Camo','Clear','Fatigue','Gold','Green','Orange','Pink','Poler','Red','Silver','Smoke','White','Yellow'];

	if(isset($products) && !empty($products)) {
		foreach($products as $sku => $product) {
			// if($product['stock_number'] !== 'OR3176')
				// continue;
				
			// $skus[] = $skus;

			/** Check For Size In Title **/
			/* $size = '';
			$sizeRegEx = '/([0-9]*)[x][0-9]*([a-z]{2})\b/i';

			preg_match($sizeRegEx, $product['product_name'], $sizeMatches);
			if(isset($matches) && !empty($matches)) {
				$size = $matches[0];
			} */

			/** Check For Colors In Title**/
			/* $productColor = '';
			
			foreach($availableColors as $color) {
				if(strpos(strtolower($product['product_name']), strtolower($color)) !== FALSE) {
					$productColor = $color;
					$productName = trim(str_replace($color, '', $product['product_name']));

					var_dump($productName);
				}
			}
		}
	}
	
}