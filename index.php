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
	else if ( URI == '/meta' )
	{
		require_once 'meta.php';
	}
	else
	{
		error();	
	}
}
else
{
	require_once 'download.php';
}