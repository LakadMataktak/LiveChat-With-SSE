<?php
	use tools\routers\routers as routers;
	$router = new routers();


	$router -> set_route("home", "php/home.php"); 
	$router -> set_route("test", "php/test.php"); 
	$router -> set_route("push", "php/push_msg.php"); 
	$router -> set_route("for-rent", "for_rent.php"); 
	$router -> set_route("channel", "eventsource/channel.php"); 
	$router -> set_route("en/for-rent/{sdfd}", "for_rent.php"); 
	$router -> set_route("en/for-sale", "for_sale.php"); 


