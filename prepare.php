<?php

$request = (object) array(
	
	// The time when this prepared upload expires, expressed as
	// an ISO-8601 date time with no timezone.
	"ends" => date('Y-m-d\TH:i:s\Z',strtotime($_POST["ends"])),
	
	// The path where the file can be uploaded, and from where it
	// will be downloadable. For instance, "/my/path.png"
	"path" => $_POST["path"],
	
	// The *maximum* size of the uploaded file, in bytes.
	"size" => (int)$_POST["size"]
	
);

$hmac = $_POST["hmac"];
$json = json_encode($request);

$hmac_is_correct = ( hash_hmac("sha1",$json,API_KEY) == $hmac );

if ( !$hmac_is_correct )
{
	error("403 Forbidden");
}
else
{
	require_once 'model.php';
	$token = prepare_upload($request);
	respond(array("status" => "ok", "token" => $token));
}
