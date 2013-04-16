<?php

$token    = $_POST["token"];
$path     = $_POST["path"];
$next     = $_POST["next"];
$size     = $_FILES["file"]["size"];
$filetmp  = $_FILES["file"]["tmp_name"];
$filename = $_FILES["file"]["name"];

if ( !$token || !$path || $size == 0 || !$filetmp || !$filename ) 
{
	error();
}
else
{
	require_once 'model.php';
	$success = perform_upload($path, $token, $size, $filetmp, $filename);
	if ( $success ) 
	{
		redirect($next);
	}
	else
	{
		error();
	}
}
