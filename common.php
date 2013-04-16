<?php

require_once 'config.php';

date_default_timezone_set('UTC');

// Along the lines of '/path/to/file.png'
$uri = $_SERVER['REQUEST_URI'];
if ( strpos($uri,'?') !== false )
{
	list ($uri, ) = explode("?",$uri);
}

define('URI', $uri);

// We support GET and POST methods
define('METHOD', $_SERVER['REQUEST_METHOD']);

// Current time in ISO-8601 format
define('NOW', date('Y-m-d\TH:i:s\Z'));

// Respond with some JSON
function respond($json)
{
	header("Content-Type: application/json");
	echo json_encode($json);
	exit;
}

// Displays an error JSON
function error($text = "500 Internal server error")
{
	header("HTTP/1.1 $text");
	respond(array("status" => "error"));
}

// Redirects to an URL
function redirect($url)
{
	header("HTTP/1.1 303 See Other");
	header("Location: $url");
	exit;
}

// Returns the contents of a file
function return_file($path,$mime,$filename)
{
	$file = fopen(file_path($path),"r");
	if ( $file ) 
	{
		header("Content-Type: $mime");
		
		if ( $filename !== null )
		{
			$filename = addcslashes($filename,"\"\\\n");
			header("Content-Disposition: attachment; filename=\"$filename\"");
		}
	
		fpassthru($file);
		fclose($file);
	}
	else
	{
		error("404 Not Found");
	}
}
