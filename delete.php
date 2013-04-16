<?php

$request = (object) array(		
	"ends" => date('Y-m-d\TH:i:s\Z',strtotime(isset($_POST["ends"]) ? $_POST["ends"] : "")),
	"path" => isset($_POST["path"]) ? $_POST["path"] : "",
	"what" => "DELETE"
);

$json = json_encode($request);

$hmac = isset($_GET["hmac"]) ? $_GET["hmac"] : "";
$hmac_is_correct = ( hash_hmac("sha1",$json,API_KEY) == $hmac );

if ( !$hmac_is_correct || $request->ends < NOW )
{
	error("401 Unauthorized");
}
else
{
	require_once 'model.php' ;
	delete_file($request->path);
	respond(array("status" => "ok"));	
}
