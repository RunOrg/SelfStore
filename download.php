<?php

$request = array(		
	"ends" => date('Y-m-d\TH:i:s\Z',strtotime(isset($_GET["ends"]) ? $_GET["ends"] : "")),
	"path" => URI,
	"what" => "GET"
);

$hmac_is_correct = ( hmac($request) == $hmac );

if ( !$hmac_is_correct || $request['ends'] < NOW )
{
	error("401 Unauthorized");
}
else
{
	require_once 'model.php' ;
	$meta = get_meta($request['path']);
	
	if ( !isset($meta) ) 
	{
		error("404 Not Found");
	}
	else
	{
		return_file($request['path'], $meta->mime, $meta->name);
	}
}
