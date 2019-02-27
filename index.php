<?php
	error_reporting(0);
 	require_once("vendor/autoload.php");
 	use tools\routers\routers as routers;
 	use tools\illuminate\illuminate as illuminate;
 	define(BASE_URL, "/htoo_lab/live_chat/");
 	$router = new routers();
 	$request_uri = strtolower($_SERVER["REQUEST_URI"]);
 	$request_page = str_replace(strtolower(BASE_URL), "", $request_uri);
	if(!$request_page){$request_page = "home";}

	require_once("routes.php");
	$page = $router -> route_page($request_page);
	if($page = $router -> route_page($request_page)) {
		require_once($page);
	}
	