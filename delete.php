<?php

$request = array(		
	"ends" => date('Y-m-d\TH:i:s\Z',strtotime(isset($_POST["ends"]) ? $_POST["ends"] : "")),
	"path" => isset($_POST["path"]) ? $_POST["path"] : "",
	"what" => "DELETE"
);

$hmac = isset($_GET["hmac"]) ? $_GET["hmac"] : "";
$hmac_is_correct = ( hmac($request) == $hmac );

if ( !$hmac_is_correct || $request['ends'] < NOW )
{
	error("401 Unauthorized");
}
else
{
	require_once 'model.php' ;
	delete_file($request['path']);
	respond(array("status" => "ok"));	
}
