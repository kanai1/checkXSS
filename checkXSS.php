<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>XSS검사</title>
	<?php
		$domain = '';
		if(isset($_GET['domain'])) $domain = $_GET['domain'];
		else die();

		$conn = mysqli_connect('localhost', 'TeamA', 'TeamA1234567@', 'kknock');
		$stmt = mysqli_stmt_init($conn);
	?>
</head>
<body>
	<?php
		if(isset($_GET['url']))
		{
			// 받은 url에서 XSS체크하기

			$url = 'http://'.$domain.'/'.$_GET['url'];
			$data = json_encode(array('url' => $url, 'domain' => $domain));

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, "20.200.213.238:3737/XSS.php");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

			$json = curl_exec($curl);
			$result = json_decode($json, true);

			if(strcmp($result['result_code'], "400") == 0)
			{
				$heredoc = <<< HERE
				오류가 발생했습니다.<br>
				잠시후 다시 시도해주세요.<br>
				오류가 지속적으로 발생한다면 13기 권시훈에게 문의주세요.<br>
				HERE;

				echo $heredoc;
				die();
			}

			if(strcmp($result['result'], "true") == 0)
			{
				$stmt = $conn->prepare("SELECT name, flag from flag WHERE domain = ?");
				$stmt->bind_param('s', $domain);

				if($stmt->execute())
				{
					$result = $stmt->get_result()->fetch_assoc();
					$heredoc = <<< HERE
					name: {$result['name']}<br>
					flag: {$result['flag']}<br>
					HERE;
					echo $$heredoc;
				}
				else
				{
					$heredoc = <<< HERE
					오류가 발생했습니다.<br>
					잠시후 다시 시도해주세요.<br>
					오류가 지속적으로 발생한다면 13기 권시훈에게 문의주세요.<br>
					HERE;

					echo $heredoc;
					die();
				}
			}
			else
			{
				$heredoc = <<< HERE
				<script>location.href='http://localhost'<script>
				가 포함된 XSS공격 후 다시 시도해주세요
				HERE;

				echo "XSS가 감지되지 않았습니다<br>";
				echo htmlentities($heredoc);
			}
		}
		else
		{
			// XSS 뚫은 url 입력받기
		}
	?>
</body>
</html>