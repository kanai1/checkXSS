<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>XSS검사</title>
	<?php
		if(!isset($_GET['domain'])) $domain = $_GET['domain'];
		else die();
	?>
</head>
<body>
	<?php
		if(isset($_GET['url']))
		{
			// 받은 url에서 XSS체크하기
		}
		else
		{
			// XSS 뚫은 url 입력받기
		}
	?>
</body>
</html>