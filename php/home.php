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

$reference = $database
    ->getReference('conversation')->orderByChild('id')->limitToLast(20)->getValue();
$seasonal_animes = $reference;

	
?>

<!doctype html>
<html lang="en">
		<head>
				<meta charset="UTF-8"/>
				<title>Chatter</title>
				<link rel="stylesheet" href="https://unpkg.com/picnic">
				<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

				<style>
				 #logo {
						 font-size: 120px;
						 line-height: 80px;
				 }

				 section {
						 padding-top: 3em;
						 margin: auto;
						 width: 80%;
				 }

				 section a {
						 color: black;
						 text-decoration: underline;
				 }
				 section a:hover {
						 color: gray;
				 }
				</style>
		</head>
		<body>
				<nav>
						<a href="https://cretgp.com/lab/chatter" class="brand">
								<i class="fab fa-rocketchat"></i>
								<span>Chatter</span>
						</a>

						<div class="menu">
								<a href="https://cretgp.com/lab/chatter" class="pseudo button">Home</a>
								<a href="" class="pseudo button">Play</a>
						</div>
				</nav>

				<section>
						<!-- <textarea cols="30" id="" name="" rows="10"></textarea> -->
						<h1>#general</h1>
						<fleidset class="flex two">
								<div>
										<input id="name" name="" type="text" value="" placeholder="Name"/>
										<textarea cols="30" id="user" name="" rows="10" style="resize: none" placeholder="Contents"></textarea>
										<button type="submit" onclick="send()">Send</button>
								</div>
								<div id="view" style="overflow: scroll; height: calc(100vh - 10em)">
										<article class="card">
												<header>
														[Server]
												</header>
												<footer>
														Hi, welcome to Chatter.
														The message you sent will be only seen the people browsing this page AT THAT TIME.
												</footer>
										</article>
								</div>
						</fleidset>
				</section>

				<script>
				 const es = new EventSource('channel');
				 const view = document.getElementById('view');

				 var Base64 = {
						 encode: function(str) {
								 return btoa(unescape(encodeURIComponent(str)));
						 },
						 decode: function(str) {
								 return decodeURIComponent(escape(atob(str)));
						 }
				 };

				 function send() {
				 	document.body.style.cursor='wait';
						 fetch('push?name='+document.getElementById('name').value+'&q='+document.getElementById('user').value).then(
    function(response) {
      if (response.status == 200) {
      		document.body.style.cursor='default';
      }
  });
				 }

				 es.addEventListener('message', function (event) {
						 let message = event.data;
						 var message_arr = JSON.parse(message);
						 var name = message_arr[0];
						 var msg = message_arr[1];
						 let html = `
								 <article class="card">
										 <header>
												 ${name}
										 </header>
										 <footer>
												 ${msg}
										 </footer>
								 </article>
						 `;
						 view.innerHTML = html + view.innerHTML;
				 });

				 es.onerror = function (event) {
						 switch (es.readyState) {
								 case EventSource.CONNECTING:
										 break;
								 case EventSource.CLOSED:
								 alert('Connection Lost! Sorry');
										 break;
						 }
				 };
				</script>

				<!-- <script data-no-instant src="https://cdnjs.cloudflare.com/ajax/libs/instantclick/3.0.1/instantclick.min.js"></script>
						 <script data-no-instant>InstantClick.init();</script> -->
		</body>
</html>
