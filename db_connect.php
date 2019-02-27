<?php
use tools\illuminate\illuminate as illuminate;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
error_reporting(E_ALL);
// This assumes that you have placed the Firebase credentials in the same directory
// as this PHP file.
$serviceAccount = ServiceAccount::fromJsonFile('google_service_account/jikanfag-4dcd81b1f17a.json');

$firebase = (new Factory)
    ->withServiceAccount($serviceAccount)
    ->withDatabaseUri('https://jikanfag.firebaseio.com')
    ->create();

$database = $firebase->getDatabase();

$reference = $database
    ->getReference('seasonals')->orderByChild("malId")->limitToLast(20)->getValue();
$seasonal_animes = $reference;

$topanimes = $database
    ->getReference('top')->orderByChild('startDate')->limitToLast(20)->getValue();
  


	$array = ["[topanimes]" => json_encode(array_reverse($topanimes)),"[seasonalanimes]" => json_encode(array_reverse($seasonal_animes))];
	
	 illuminate::render("index.html", $array);