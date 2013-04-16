<?php

$request = (object) array(		
	"ends" => date('Y-m-d\TH:i:s\Z',strtotime($_GET["ends"])),
	"path" => URI
);

$json = json_encode($request);

$hmac = $_GET["hmac"];
$hmac_is_correct = ( hash_hmac("sha1",$json,API_KEY) == $hmac );

if ( !$hmac_is_correct || $request->ends < NOW )
{
	error("401 Unauthorized");
}
else
{
	require_once 'model.php' ;
	$meta = get_meta($request->path);
	
	if ( !isset($meta) ) 
	{
		error("404 Not Found");
	}
	else
	{
		return_file($request->path, $meta->mime, $meta->name);
	}
}
