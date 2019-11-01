<?php
session_start();

// ログイン状態チェック
if (!isset($_SESSION["NAME"]) || $_SESSION["BUNRUI"] != 1) {
    header("Location: Logout.php");
    exit;
}
?>

<!DOCTYPE html>

<html lang="ja">

	<head>
		<meta charset="utf-8">

		<title>通報</title>
		
	</head>

	
	<body>

	
		<div align="center">
				<p>
				<font size="6" align="left">〇〇様</font>
				</p>
					
				<p>
				<font size="6">協力者へ情報を送信しました。</font>
				</p>
				
		</div>
	<form action="SiyakusyoTop.php">
	<div><input id="add" type="submit" value="戻る"></div>
	</form>
	</body>

</html>