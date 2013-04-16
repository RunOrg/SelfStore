<?php

require_once 'common.php';

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
	else if ( URI == '/mime' )
	{
		require_once 'mime.php';
	}
	else
	{
		error("405 Method Not Allowed");	
	}
}
else
{
	if ( TEST_MODE && URI == '/test' )
	{
		require_once 'test.php';
	}
	else
	{
		require_once 'download.php';
	}
}