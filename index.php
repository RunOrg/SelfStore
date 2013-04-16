<?php

// Along the lines of '/path/to/file.png'
define('URI', $_SERVER['REQUEST_URI']);

// We support GET and POST methods
define('METHOD', $_SERVER['REQUEST_METHOD']);

if ( METHOD == 'POST' )
{
	if ( URI == '/upload' ) 
	{
		require_once 'upload.php';
	}
	else if ( URI == '/delete' )
	{
		require_once 'delete.php';
	}
	else if ( URI == '/prepare' )
	{
		require_once 'prepare.php';
	}
	else if ( URI == '/meta' )
	{
		require_once 'meta.php';
	}
	else
	{
		require_once 'error.php';
	}
}
else
{
	require_once 'download.php';
}