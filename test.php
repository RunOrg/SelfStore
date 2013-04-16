<?php include "model.php"; ?>
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
	<form class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="token"/>
		<div class="control-group">
			<label class="control-label" for="path">Upload path</label>
			<div class="controls">
				<input id="path" name="path" type="text" value="path" placeholder="/path/to/file" />
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

</body>
</html>
