<?php 

include "model.php"; 

if (isset($_GET["upload"]))
{
	$request = (object) array(
		"ends" => date('Y-m-d\TH:i:s\Z',time() + 3600),
		"path" => $_POST["path"],
		"size" => 1024 * 1024
	);
	
	$json = json_encode($request);
	$hmac = hash_hmac("sha1",$json,API_KEY);

	$request->hmac = $hmac;
	respond($request);
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>SelfStore Test Page</title>
	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
	<h2>Files</h2>
<div class="row">
<div class="span7">

	<table class="table">
		<tr><th>Path</th><th>Hash</th></tr>
		<?php foreach (all_files() as $file): ?>
		<tr>
			<td><a href="<?php 
			
				$request = (object) array(
					"ends" => date('Y-m-d\TH:i:s\Z', time() + 3600),
					"path" => $file->path,
					"what" => "GET"
				);
				
				$json = json_encode($request);
				$hmac = hash_hmac("sha1",$json,API_KEY);
				
				echo $file->path . "?ends=" . $request->ends . "&hmac=$hmac";
			
			?>"><?php echo $file->path; ?></a></td>
			<td><?php echo md5($file->path); ?></td>
		</tr>
		<?php endforeach; ?>
	</table>

</div>
<div class="span5">
	
	<h3>Upload File</h3>
	<form id="upload" class="form-horizontal" action="/upload" method="POST" enctype="multipart/form-data">
		<input id="token" type="hidden" name="token"/>
		<input type="hidden" name="next" value="http://<?php echo $_SERVER["SERVER_NAME"]; ?>/test"/>
		<div class="control-group">
			<label class="control-label" for="path">Upload path</label>
			<div class="controls">
				<input id="path" name="path" type="text" placeholder="/path/to/file" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="file">Local file</label>
			<div class="controls">
				<input id="file" name="file" type="file" />
			</div>
		</div>
		<div class="form-actions">
			<button class="btn btn-primary" type="submit">Upload</button>
		</div>
	</form>

</div>
</div>
</div>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
$(function(){

	var old_token = "";

	$("#upload").submit(function(){
	
		if ($("#token").val() != old_token) {
			old_token = $("#token").val();
			return true;
		}
		
		$.post("/test?upload",{path:$("#path").val()},function(data){
			$.post("/prepare",data,function(allowed){
				$("#token").val(allowed.token);
				$("#upload").submit();
			});
		});
		
		return false;
	
	});

});
</script>

</body>
</html>
