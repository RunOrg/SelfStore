<!DOCTYPE html>
<html>
<head>
	<title>SelfStore API Documentation</title>
	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
<div class="row">
<div class="span8">
	<h1>SelfStore API Documentation</h1>
	
	<h2>Installing</h2>
	<p>
		You have installed SelfStore on your server. Edit file <code>config.php</code> so
		that it looks something like this:
	</p>
	<pre>
define('API_KEY', '0123456789ABCDEFGHIJKLMNOPQRSTU');
define('TEST_MODE', false);</pre>
	<p>
		This will secure SelfStore. <b>The API key is a secret, keep it well hidden.</b> 
	</p>
	
	<h2>Authentication</h2>
	<p>
		All requests sent to SelfStore must be authenticated using the API key 
		(except for the upload request, which is instead authenticated with a 
		one-use token).
	</p>
	<p>
		To authenticate a request, you must include an additional parameter called
		HMAC, which is the hexadecimal representation of a SHA1-HMAC using your
		API key and the request payload in its canonical representation. 
	</p>
	<p>
		In terms of code, this means :
	</p>
	<pre>
$hmac = hash_hmac('sha1', $payload, API_KEY);</pre>
	<p>
		The server will compute the HMAC on its side, and compare it with yours.
		If they match, you're in. Make sure you have the same payload and the
		same API key on both server and client.
	</p>
	<p>
		The payload should be put in canonical representation. Output key=value
		pairs after sorting by key, urlencoding both key and value, and
		separated by &amp;. For example:		
	</p>
	<pre>
$request = array(
	"ends" => date('Y-m-d\TH:i:s\Z',time() + 600),
	"path" => $path,
	"what" => "GET"
);

ksort($request);
$payload = array();

foreach ($request as $key => $value) 
	$payload[] = urlencode($key)."=".urlencode($value);

$payload = implode("&amp;",$payload);</pre>
	
	<h2>Preparing an upload</h2>
	<p>
		Uploads must be prepared before they are performed. To prepare an upload:
	</p>
	<pre>POST /prepare
path=/the/path/requested&ends=2013-04-18T15:23:03Z&size=10000</pre>
	<dl>
		<dt>path</dt>
		<dd>The path where the file will be downloadable later, including the initial <code>/</code>.</dd>
		<dt>ends</dt>
		<dd>An ISO-8601 datetime with no timezone information, before which the upload must be performed.</dd>
		<dt>size</dt>
		<dd>The maximum size of the uploaded file, in bytes.</dd>
		<dt>hmac</dt>
		<dd>The authentication proof</dd>
	</dl>
	<p>
		All parameters except <code>hmac</code> are part of the payload.
	</p>
	<p>
		This API call returns some JSON that includes a unique, one-use upload token.
	</p>
	<pre>{"status":"ok","token":"40dad4ba18ba0b697006bdb8562dfdc0f3e41ba9"}</pre>
	
	<h2>Uploading a file</h2>
	<p>
		This should be done from a standard HTML form, that must include the following named fields:
	</p>
	<dl>
		<dt>path</dt>
		<dd>The path where the file will be downloadable later. This field is usually hidden from the user.</dd>
		<dt>token</dt>
		<dd>The token returned by the prepare API, must match the path parameter.</dd>
		<dt>file</dt>
		<dd>A file upload form input.</dd>
		<dt>next</dt>
		<dd>A complete URL where the user will be redirected when the upload finishes.</dd>
	</dl>
	<p>
		Submit the form and the server will redirect you to the specified URL.
	</p>
	
	<h2>Downloading a file</h2>
	<p>
		If the file is public (its URI begins with <code>/public/</code>), simply perform an HTTP
		GET on its path, for instance:
	</p>
	<pre>GET /public/file.txt</pre>
	<p>
		If the file is private, you need to provide the HMAC and expiration date, such as: 		
	</p>
	<pre>GET /path?ends=2013-04-17T07:39:27Z&hmac=de544cbf77b80a349a3cd9f91419e4dbfd573f2e</pre>
	<p>
		The HMAC is computed from field <code>ends</code> (the expiration time for the URL),
		the path itself, and a third parameter named <code>what</code> with a value of <code>GET</code>.
		In the above example, the request payload would be:
	</p>
	<pre>ends=2013-04-17T07:39:27Z&path=/path&what=GET</pre>	
	
</div>
</div>
</div>
</body>
</html>