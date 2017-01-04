<?php

	if($_SERVER['HTTP_HOST']=='server')
	{
		define('BASE_DIR', 'F:/xampp/htdocs/mvcsmarty/');
		define('BASE_URL', 'http://server/mvcsmarty/');

		define('SMARTY_DIR', 'F:/xampp/htdocs/Smarty/libs/');
		# Database details
		
		define('DB_HOST_NAME', 'server');
		define('DB_USER_NAME', 'server');
		define('DB_PASSWORD' , '');
		define('DB_NAME', 'mvcsmarty');
		define('DEBUG', '0');

	}
	else
	{
		 
		define('BASE_DIR', 'F:/xampp/htdocs/mvcsmarty/');
		define('BASE_URL', 'http://server/mvcsmarty/');

		define('SMARTY_DIR', 'F:/xampp/htdocs/Smarty/libs/');
		# Database details
		
		define('DB_HOST_NAME', 'server');
		define('DB_USER_NAME', 'server');
		define('DB_PASSWORD' , '');
		define('DB_NAME', 'mvcsmarty');
		define('DEBUG', '0');
		
	}
	# define all global variables, constants
	define('WEBSITE_NAME', 'Freedom Pop');
	define('LIBRARY_DIR', BASE_DIR.'library/');
	define('CONTENT_DIR', LIBRARY_DIR.'content/');
	define('PUBLIC_DIR', BASE_DIR.'public/');
	define('PUBLIC_URL', BASE_URL.'public/');
	define('CLASS_DIR', LIBRARY_DIR.'class/');
	define('CONFIG_DIR', LIBRARY_DIR.'config/');
	define('FUNCTION_DIR', LIBRARY_DIR.'function/');
	define('JS_DIR', PUBLIC_DIR.'js/');
	define('CSS_URL', PUBLIC_URL.'css/');
	define('TEMPLATE', BASE_DIR.'templates/');
	define('TEMPLATE_C', BASE_DIR.'templates_c/');
	define('CATCHE', BASE_DIR.'cache/');
	define('IMAGE_PATH', PUBLIC_URL.'images/');
	
	define('IMPROVESYS_DIR', LIBRARY_DIR.'contacts/');
	
	define('MODEL_DIR', BASE_DIR.'models/');
	define('CONTROLLER_DIR', BASE_DIR.'controllers/');