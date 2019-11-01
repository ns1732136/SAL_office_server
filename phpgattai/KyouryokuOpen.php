<?php
session_start();

// ログイン状態チェック
if (!isset($_SESSION["NAME"]) || !($_SESSION["BUNRUI"] == 1)) {
    header("Location: Logout.php");
    exit;
}

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "hogehoge";  // ユーザー名
$db['pass'] = "pass";  // ユーザー名のパスワード
$db['dbname'] = "hogehoge";  // データベース名


if (isset($_POST["signUp"])) {
	
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare("UPDATE HaikaiRireki SET status=3 , opentime=? WHERE number=? ");
			$tuuhoutime = date("Y/m/d H:i:s");
            $stmt->execute(array($tuuhoutime,$_SESSION["edit"]));  // パスワードのハッシュ化を行う（今回は文字列のみなのでbindValue(変数の内容が変わらない)を使用せず、直接excuteに渡しても問題ない）
				header("Location:Kaijikanryou.php");
				exit();
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
            // echo $e->getMessage();
        }
   }


?>

<!DOCTYPE html>

<html lang="ja">

	<head>
		<meta charset="utf-8">

		<title>外出通知</title>
		
	</head>

	
	<body>
<script>
history.replaceState({location_replace: "SiyakusyoTop.php"}, "")
history.pushState({}, "", "KyouryokuOpen.php")

window.addEventListener("popstate", eve => {
	if(eve.state && eve.state.location_replace){
		location.replace(eve.state.location_replace)
	}
})
</script>
		<div align="center">
			<h1><font color="#FFAA00">外出通知</font></h1>
		</div>
	
		<div align="center">
				<p>
				<font size="6" align="left">〇〇様</font>
				</p>
					
				<p>
				<font size="6">2019年7月13日10時00分00秒に外出されました。</font>
				</p>
				
				<p>
				<img src="1.png" width="500" height="350" border="5">
				</p>
				
        <form id="loginForm" name="loginForm" action="" method="POST">
				<p><input type="submit" id="signUp" name="signUp"  value="協力者に開示" style="width:500px;height:100px; font:50pt MSゴシック; font-weight:blod;"></p>
				</form>
		<form action="SiyakusyoTop.php">
			<p><input id="add" type="submit" value="戻る" style="width:500px;height:100px; font:50pt MSゴシック; font-weight:blod;"></p>
		</form>		
				
		</div>

	</body>

</html>
