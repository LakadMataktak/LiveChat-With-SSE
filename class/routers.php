<?php
namespace tools\routers;
class routers
{
		private $public_routes = array();
		protected $requested_page;		
		function get_path_page($path)
		{
			$pos =  strpos($path, '{');
			if($pos)
			{
				return substr($path, 0, $pos);
			}
			return $path;
		}

		function set_route($direction, $destination)
		{
			global $routes_arr;
			$page_arr = explode("@", $destination);
			$path_arr = explode("/", $direction);
			$variables = array_slice($path_arr, 1);
			 $path_page =  $this->get_path_page($direction);
			$routes_arr["properties"] = array("path" => $direction, "path_page" => $path_page, "variables" => $variables, "page" => $page_arr[0]);
			$this->set_public_routes($routes_arr);
		}

		public function set_public_routes($arr)
		{
			$this->public_routes[] = $arr; 
		}
		private function check_page_language($index)
		{
			if(stristr($index, "en"))
			{
				$GLOBALS["global_lang"] = "en";
			} else {
				$GLOBALS["global_lang"] = "mm";
			}
		}
		
		function route_page($requested_uri)
		{
	
			 $requested_page_arr = explode("/", $requested_uri);
			 $requested_page =  $this->get_path_page($requested_uri);
			 

			$filter_page_arr = explode("?", $requested_page);
		 	$page = 			$filter_page_arr[0];
		 	
		 	$this -> check_page_language($page);

			 $page_result = $this -> isset_page($page);
			
			 $page_not_found = false;

			if($page_result)
			{
					$page_properties = $this->get_page_properties($page);
					$path_page = $page_properties["path"];
	
					if(count(explode("/", $path_page)) != count($requested_page_arr))
					{
						$extra_path = true;
						goto exit_after_extra_variables;
					}

					$request_func = $page_properties["page"];
					$raw_variables = $page_properties["variables"];

					$variables = array();
					foreach ($raw_variables as $key => $value) {
							$value = trim($value, "{}");
							$variables[$value] =  $requested_page_arr[$key + 1];
					}
					if($_REQUEST)
					{
						$variables = array_merge($raw_variables, $_REQUEST);
					}
						return $request_func;
				

			}else{
				$page_not_found = true;
				return "php/Error_404.php";
				goto exit_after_404;
			}

			exit_after_404:{
					if($page_not_found == true)
						header("location:" . BASE_URL . "404");
					exit();
			}
			exit_after_extra_variables:{
				if($extra_path == true)
					header("location:" . BASE_URL . "extra-path");
					exit();
			}
		}
		function get_public_routes()
		{
			return $this->public_routes;
		}
		function get_controller($para) 
		{
			$para = trim($para);
			switch ($para) {
				case '':
					$controller = $this;
					break;
				default:
					$cname = "Route\\$para\\".$para;
					$controller = new $cname();
					break;
			}
			return $controller;
		}
		function get_page_properties($page)
		{

			$routes_arr = $this ->get_public_routes();
			
			$properties = $this-> find_appropriate_page($routes_arr, $page);
			return $properties;
		}

		function find_appropriate_page($arr, $haystack)
		{
			foreach($arr as $value)
			{
					$needle = $value["properties"]["path_page"] ;
					if(stristr($haystack, $needle))
					{
					$each_results["mark"]= strlen($needle);
					$each_results["properties"]= $value["properties"];
					$all_arr[] = $each_results; 
					}
			}
			$max_value =  max(array_column($all_arr, 'mark'));
			foreach ($all_arr as $key => $each_arr) {
				if($each_arr["mark"] >= $max_value)
				{
					return $each_arr["properties"];
				}
			}
		}
		function isset_page($page)
		{

			$routes_arr = $this ->get_public_routes();
			foreach ($routes_arr as $key => $properties_arr) {
				$result = false;
				 $prop_page = $properties_arr["properties"]["path_page"];

				if(stristr($page,  $prop_page))
				{
					$result = $prop_page;
					break;
				}
				
			}
			return $result;
		}
		

}