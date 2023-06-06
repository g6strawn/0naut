<?php # MySQL login secrets (i.e. passwords) - This file should be outside HTML directory
if(count(get_included_files())==1)  exit;  //direct access not permitted
DEFINE('DB_DATABASE', 'thunkonaut');
if($_SERVER['SERVER_ADDR'] === '127.0.0.1'  ||  $_SERVER['SERVER_ADDR'] === '::1') {
	//connect to local test server
	DEFINE('DB_SERVER',   'localhost');
	DEFINE('DB_USERNAME', 'root');
	DEFINE('DB_PASSWORD', '');
} else {
	//connect to live server
	DEFINE('DB_SERVER',   '127.0.0.1');
	DEFINE('DB_USERNAME', 'thunk0dbB0t');
	DEFINE('DB_PASSWORD', 'Ztvf_@!eY_8g&w9G');
}
?>
