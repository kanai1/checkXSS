<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>XSS check</title>
	<?php
		$conn = mysqli_connect('localhost', 'TeamA', 'TeamA1234567@', 'kknock');
		$stmt = mysqli_stmt_init($conn);
	?>
</head>
<body>
	<h1>이름이나 IP를 통해 검색해주세요.</h1>
		<form action="index.php" method="GET">
			<input type="text" name="search" placeholder="검색">
		</form>
		<table border="1px">
			<tr>
				<td>이름</td>
				<td>아이피</td>
			</tr>
	<?php
		if(isset($_GET['search']))
		{
			$keyword = "%{$_GET['search']}%";
			$stmt->prepare("SELECT name, domain from flag WHERE name like ? or domain like ?");
			$stmt->bind_param('ss', $keyword, $keyword);

			if($stmt->execute())
			{
				$result = $stmt->get_result();
				while($row = $result->fetch_assoc())
				{
					$heredoc = <<< HERE
					<tr>
						<td><a href="checkXSS?domain={$row['domain']}">{$row['name']}</a></td>
						<td><a href="checkXSS?domain={$row['domain']}">{$row['domain']}</a></td>
					</tr>
					HERE;

					echo $heredoc;
				}
			}
		}
	?>
		</table>
</body>
</html>