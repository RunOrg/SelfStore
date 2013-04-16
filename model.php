<?php

// The server-side path where the document is stored.
// Uses an MD5 hash to avoid wasting time on path sanitization
// and directory structure upkeep.
function file_path($path)
{
	return "uploads/" . md5($path);
}

// The meta file sits next to the main file, and contains
// general information about that file :
//   - actual name (the name it was uploaded as)
//   - MIME type
function meta_path($path)
{
	return file_path($path).".meta";
}

// The upload file is created before the actual file, and
// determines : 
//   - how long until the upload permission expires ?
//   - how large can the uploaded file be ?
//   - what is the secret key token for uploading ? 
function upload_path($path)
{
	return file_path($path).".upload";
} 

// Prepare uploading a file by saving a '*.upload' file
// to the disk. Returns the policy token for uploading.
function prepare_upload($policy)
{
	$upload_path = upload_path($policy->path);
	$policy->token = sha1(uniqid(mt_rand(), true));
	$json = json_encode($policy);
	file_put_contents($upload_path,$json);
	return $policy->token;
}

// Returns true if a given upload was permitted,
// saves the data to the disk.
function perform_upload($path,$token,$size,$filetmp,$filename)
{
	$json = @file_get_contents(upload_path($path));
	if ( !$json ) return false;
	
	$policy = @json_decode($json);
	if ( !$policy ) return false;
	
	if ( $policy->token != $token ) return false;
	
	if ( $policy->size < $size ) return false;
	
	if ( $policy->ends < NOW ) return false;
	
	$meta = array("name" => $filename);
	
	$success = @copy($filetmp, file_path($path));
	if ( !$success ) return false;
	
	@file_put_contents(meta_path($path), json_encode($meta));
	@unlink(upload_path($path));
	
	return true;
}

// Set the mime-type of a file. Returns false if
// it failed.
function set_mime($path,$mime)
{
	$metapath = meta_path($path);
	
	$json = @file_get_contents($metapath);
	if ( !$json ) return false;
	
	$meta = @json_decode($json);
	if ( !$meta ) return false;
	
	$meta->mime = $mime;
	
	@file_put_contents($metapath, json_encode($meta));
	
	return true;
}

// Get the file name and mime-type
function get_meta($path)
{	
	$json = @file_get_contents(meta_path($path));
	if ( !$json ) return null;
	
	$meta = @json_decode($json);
	if ( !$meta ) return null;
	
	if ( !isset($meta->mime) )
	{
		$meta->mime = "application/octet-stream";
	}
	
	return $meta;
}

// Deletes a file (does nothing if no such file exists)
function delete($path)
{
	@unlink(meta_path($path));
	@unlink(upload_path($path));
	@unlink(file_path($path));
}