<?php

function send()
{
	require_once 'model.php' ;
	$meta = get_meta(URI);
	
	if ( !isset($meta) ) 
	{
		error("404 Not Found");
	}
	else
	{
		return_file(URI, $meta->mime, $meta->name);
	}
}

if ( strpos(URI,'/public/') === 0 )
{
	send();
}
else
{	
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
		send();
	}
}
