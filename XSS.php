<?php
	header('Content-Type:application/json; charset-UTF-8;');

	if(!in_array('application/json',explode(';',$_SERVER['CONTENT_TYPE']))){
		echo json_encode(array('result_code' => '400'));
		exit;
	}

	$rowdata = file_get_contents("php://input");
	$data = json_decode($rowdata, true);

	$inputURL = $data['url'];
	$domain = $data['domain'];

	try
	{
		require_once('/var/www/api/XSS.php');
	}
	catch(Exception $e)
	{
		echo json_encode(array('result_code' => '400', 'url' => $inputURL, 'error' => $e->getMessage()));
		exit;
	}
	if(strcmp('http://localhost/', $currentURL) == 0)
	{
		echo json_encode(array('result_code' => '200', 'domain' => $domain, 'url' => $currentURL, 'result' => 'true'));
	}
	else
	{
		echo json_encode(array('result_code' => '200', 'domain' => $domain, 'url' => $currentURL, 'result' => 'false'));
	}
?>