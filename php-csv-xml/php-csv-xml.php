<?php

//function to convert csv to xml string
function convertCsvToXmlString($csv_string) {
	// split rows into lines
	$lines = explode(PHP_EOL, $csv_string);
	
	// retrieve headers
	$headers = explode(',', array_shift($lines));
	
	// Create a new dom document with pretty formatting
	$doc  = new DomDocument();
	$doc->formatOutput   = true;
	
	// Add a root node to the document
	$root = $doc->createElement('policies');
	$root = $doc->appendChild($root);
	
	// Loop through each row creating a <policy> node with the correct data
	foreach ($lines as $line)
	{
		$row = str_getcsv($line);
		$container = $doc->createElement('policy');
		
		foreach($headers as $i => $header)
		{
			$child = $doc->createElement($header);
			$child = $container->appendChild($child);
			$value = $doc->createTextNode($row[$i]);
			$value = $child->appendChild($value);
		}
		$root->appendChild($container);
	}
	
	$strxml = $doc->saveXML();
	
	echo $strxml;
}

//Test - csv to xml
$csv_string ='policyID,statecode,county,eq_site_limit,hu_site_limit,fl_site_limit,fr_site_limit,tiv_2011,tiv_2012,eq_site_deductible,hu_site_deductible,fl_site_deductible,fr_site_deductible,point_latitude,point_longitude,line,construction,point_granularity
119736,FL,CLAY COUNTY,498960,498960,498960,498960,498960,792148.9,0,9979.2,0,0,30.102261,-81.711777,Residential,Masonry,1
448094,FL,CLAY COUNTY,1322376.3,1322376.3,1322376.3,1322376.3,1322376.3,1438163.57,0,0,0,0,30.063936,-81.707664,Residential,Masonry,3
206893,FL,CLAY COUNTY,190724.4,190724.4,190724.4,190724.4,190724.4,192476.78,0,0,0,0,30.089579,-81.700455,Residential,Wood,1
333743,FL,CLAY COUNTY,0,79520.76,0,0,79520.76,86854.48,0,0,0,0,30.063236,-81.707703,Residential,Wood,3
172534,FL,CLAY COUNTY,0,254281.5,0,254281.5,254281.5,246144.49,0,0,0,0,30.060614,-81.702675,Residential,Wood,1
785275,FL,CLAY COUNTY,0,515035.62,0,0,515035.62,884419.17,0,0,0,0,30.063236,-81.707703,Residential,Masonry,3
995932,FL,CLAY COUNTY,0,19260000,0,0,19260000,20610000,0,0,0,0,30.102226,-81.713882,Commercial,Reinforced Concrete,1
223488,FL,CLAY COUNTY,328500,328500,328500,328500,328500,348374.25,0,16425,0,0,30.102217,-81.707146,Residential,Wood,1
433512,FL,CLAY COUNTY,315000,315000,315000,315000,315000,265821.57,0,15750,0,0,30.118774,-81.704613,Residential,Wood,1';

//header('Content-type: text/xml');
//convertCsvToXmlString($csv_string);

//File
function convertCsvToXmlFile($input_file, $output_file) {
	// Open csv file for reading
	$inputFile  = fopen($input_file, 'rt');
	
	// Get the headers of the file
	$headers = fgetcsv($inputFile);
	
	// Create a new dom document with pretty formatting
	$doc  = new DomDocument();
	$doc->formatOutput   = true;
	
	// Add a root node to the document
	$root = $doc->createElement('policies');
	$root = $doc->appendChild($root);
	
	// Loop through each row creating a <policy> node with the correct data
	while (($row = fgetcsv($inputFile)) !== FALSE)
	{
		$container = $doc->createElement('policy');
		
		foreach($headers as $i => $header)
		{
			$child = $doc->createElement($header);
			$child = $container->appendChild($child);
			$value = $doc->createTextNode($row[$i]);
			$value = $child->appendChild($value);
		}
		$root->appendChild($container);
	}
	
	$strxml = $doc->saveXML();
	
	$handle = fopen($output_file, "w");
	fwrite($handle, $strxml);
	fclose($handle);
}

convertCsvToXmlFile("FL_insurance.csv", "FL_insurance.xml");