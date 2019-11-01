<?php
// セッション開始

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "hogehoge";  // ユーザー名
$db['pass'] = "pass";  // ユーザー名のパスワード
$db['dbname'] = "hogehoge";  // データベース名

// エラーメッセージ、登録完了メッセージの初期化
$errorMessage = "";
$signUpMessage = "";

// ログインボタンが押された場合
if(isset($_POST["kakunin"]) || isset($_POST["tuuhou"])) {

        $username = $_POST["username"];

        // 2. ユーザIDとパスワードが入力されていたら認証する
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            
            $imgname='/var/www/html/photo/tuuhou.jpg';

            $stmt = $pdo->prepare("INSERT INTO HaikaiRireki(riyousyaid,tuuhoutime,kaigosyaid,satueitime,photomovie,status) VALUES (8,?,2,?,?,?)");
			$satueitime = "2019-07-13 10:00:00";
			$tuuhoutime = date("Y/m/d H:i:s");
			if(isset($_POST["kakunin"])){
				$status=1;
			}else{
				$status=2;
			}
            $stmt->execute(array($tuuhoutime,date($satueitime),$imgname,$status));  // パスワードのハッシュ化を行う（今回は文字列のみなのでbindValue(変数の内容が変わらない)を使用せず、直接excuteに渡しても問題ない）
            if(isset($_POST["kakunin"])){
                    		header("Location: kaigotuti_kakunin.html");  //市役所メイン画面へ遷移
			}else{
                    		header("Location: kaigotuti_tuho.html");  // 介護師画面へ遷移
			}
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
            //echo $e->getMessage();
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

		<div align="center">
			<h1><font color="#FFAA00">外出通知</font></h1>
		</div>

        <form id="loginForm" name="loginForm" action="" method="POST">
		<div align="right">
			
			<input align="light" type="submit" id="kakunin" name="kakunin" value="確認" style="width:100px;height:50px; font:20pt MSゴシック; font-weight:blod;">
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
				
				<p><input type="submit" id="tuuhou" name="tuuhou" value="通 報" style="width:200px;height:100px; font:50pt MSゴシック; font-weight:blod;"></p>

				

				
				
		</div>
				</form>
	</body>

</html>
