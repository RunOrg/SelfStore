<?php

$request = (object) array(		
	"ends" => date('Y-m-d\TH:i:s\Z',strtotime(isset($_POST["ends"]) ? $_POST["ends"] : "")),
	"mime" => isset($_POST["mime"]) ? $_POST["mime"] : "",
	"path" => isset($_POST["path"]) ? $_POST["path"] : ""
);

$hmac = isset($_POST["hmac"]) ? $_POST["hmac"] : "";
$hmac_is_correct = ( hmac($request) == $hmac );

if ( !$hmac_is_correct || $request['ends'] < NOW )
{
	error("401 Unauthorized");
}
else
{
	require_once 'model.php' ;
	set_mime($request['path'],$request['mime']);
}
