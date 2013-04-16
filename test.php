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

</body>
</html>
