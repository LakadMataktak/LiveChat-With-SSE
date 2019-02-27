<?php
use tools\illuminate\illuminate as illuminate;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
// Turn off output buffering
set_time_limit(0);
ini_set('output_buffering', 'off');
@ini_set('zlib.output_compression',0);
@ini_set('implicit_flush',1);
@ob_end_clean();

// Turn off PHP output compression




// This assumes that you have placed the Firebase credentials in the same directory
// as this PHP file.
$serviceAccount = ServiceAccount::fromJsonFile('google_service_account/live-chat-4ee5f-3b6b09c294fa.json');

$firebase = (new Factory)
    ->withServiceAccount($serviceAccount)
    ->withDatabaseUri('https://live-chat-4ee5f.firebaseio.com')
    ->create();

$database = $firebase->getDatabase();

$conversation_db = $database
    ->getReference('conversation')->orderByChild('timestamp')->limitToLast(1)->getValue();
$latest_msg = $conversation_db[key($conversation_db)]["timestamp"];


header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
ob_implicit_flush(1);
// Do not show the first message
// ini_set('output_buffering',4092);
$prev = $latest_msg;
$prev;
$counter = 0;
while (1) {
		$counter++;		
		 $conversation_db = $database
    ->getReference('conversation')->orderByChild('timestamp')->limitToLast(1)->getValue();
		$now = $conversation_db[key($conversation_db)]["timestamp"];
		if ($prev != $now) {
				$newdatas = substr($now, strlen($prev));
				$newdatas = explode("\n", $newdatas);
				$conversation_arr = $conversation_db[key($conversation_db)];
			
				// foreach ($newdatas as $com) {
						$com = explode(' ', $com);
						$name = htmlspecialchars($conversation_arr["id"]);
						$contents = htmlspecialchars($conversation_arr["msg"]);
						if (strpos($contents, "\n") !== false) {
								// Escape newline for SSE
								$contents = str_replace("\n", "%\\n", $contents);
						}
						if ($name != "" && $contents != "") 
							echo "data: $name $contents \n\n";
							echo str_repeat(' ',1024*64) . "\n";
							//this is for the buffer achieve the minimum size in order to flush data
				// }
			
				// $counter = 0;
		} else {
				// Send a little candy 15 seconds every in order not to disconnect
				if ($counter % 30 == 0) {
						//echo "retry: 30000 \n\n";
						//echo "data: After 3 seconds \n\n";
						echo "event: ping \n";
						echo "data: { heartbeat : 1 } \n\n";
						echo str_repeat(' ',1024*64) . "\n";
				}

				if ($counter % 150 == 0) {
					exit();
				}

		}
		$prev = $now;

    sleep(1);
}