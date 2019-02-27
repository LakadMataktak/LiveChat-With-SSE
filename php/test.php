<?php
error_reporting(E_ALL);
set_time_limit(0);
// apiKey: "AIzaSyDAwBCuid1MamXl2pKMVhBQuBbvJRM8uI4",
//     authDomain: "jikanfag.firebaseapp.com",
//     databaseURL: "https://jikanfag.firebaseio.com",
//     projectId: "jikanfag",
//     storageBucket: "jikanfag.appspot.com",
//     messagingSenderId: "1006816936139"
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
 use tools\illuminate\illuminate as illuminate;
 use Jikan\Jikan;
use \Jikan\Helper\Constants;
	$jikan = new Jikan;
// Top anime
	// Anime this season
//$topAnime = $jikan->TopAnime(2);
$seasonal = $jikan->Seasonal()->anime;



// foreach($seasonal_animes as $key => $each)
// {
// 	$seasonal_animes[$key]->synopsis = substr($each->synopsis, 0, 150);
// }

$serviceAccount = ServiceAccount::fromJsonFile('google_service_account/jikanfag-4dcd81b1f17a.json');

$firebase = (new Factory)
    ->withServiceAccount($serviceAccount)
    ->withDatabaseUri('https://jikanfag.firebaseio.com')
    ->create();

$database = $firebase->getDatabase();

foreach ($seasonal as $key => $each) {
	$ref = $database->getReference('seasonals/' . $each->malId);
	$ref->set($each);
}


// $reference = $database
//     ->getReference('seasonals')->orderbyKey()->limitToLast(20)->getValue();
// $seasonal_animes = $reference;

$ref->set($seasonal, 'seasonals');


exit();

$array = ["[topanimes]" => json_encode($topAnimes),"[seasonalanimes]" => json_encode($seasonal_animes)];
	
	 illuminate::render("index.html", $array);