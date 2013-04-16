<?php

require_once 'config.php';

date_default_timezone_set('UTC');

// Along the lines of '/path/to/file.png'
define('URI', $_SERVER['REQUEST_URI']);

// We support GET and POST methods
define('METHOD', $_SERVER['REQUEST_METHOD']);

// Respond with some JSON
function respond($json)
{
	header("Content-Type: application/json");
	echo json_encode($json);
	exit;
}

// Displays an error JSON
function error()
{
	header("HTTP/1.1 500 Internal server error");
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
function file($path,$mime,$filename)
{
	$file = fopen($path);
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
		error();
	}
}
