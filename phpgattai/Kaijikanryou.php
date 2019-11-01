<?php
session_start();

// ログイン状態チェック
if (!isset($_SESSION["NAME"]) || $_SESSION["BUNRUI"] != 1) {
    header("Location: Logout.php");
    exit;
}


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="一覧テンプレ.css">

<title>開示完了</title>
</head>

<body>

<script>
history.replaceState({location_replace: "SiyakusyoTop.php"}, "")
history.pushState({}, "", "Kaijikanryou.php")

window.addEventListener("popstate", eve => {
	if(eve.state && eve.state.location_replace){
		location.replace(eve.state.location_replace)
	}
})
</script>

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
