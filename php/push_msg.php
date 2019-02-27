<?php
use tools\illuminate\illuminate as illuminate;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
// This assumes that you have placed the Firebase credentials in the same directory
// as this PHP file.
$serviceAccount = ServiceAccount::fromJsonFile('google_service_account/live-chat-4ee5f-3b6b09c294fa.json');

$firebase = (new Factory)
    ->withServiceAccount($serviceAccount)
    ->withDatabaseUri('https://live-chat-4ee5f.firebaseio.com')
    ->create();

 $database = $firebase->getDatabase();

if (count($_GET) != 2 || !isset($_GET['q'])  || !isset($_GET['name'])) exit;
if (strpos(base64_decode($_GET['name']), "\n") !== false) exit;
if (strpos(base64_decode($_GET['q']), "%\\n") !== false) exit;
if (base64_decode($_GET['q']) == "") exit;
if (base64_decode($_GET['name']) == "") exit;

$now = strtotime("now");
$conversation_db = $database
    ->getReference('conversation')->orderByChild('timestamp')->limitToLast(1)->getValue();

$database->getReference('conversation')
   ->push([
       'id' => $_GET['name'],
       'msg' => $_GET['q'],
       'timestamp' => strtotime("now")
      ]);


