<?php

//元のURL https://qiita.com/KosukeQiita/items/b56b3004413c999b9858
//セッションというサーバに置かれるつなげてきたユーザのデータを入れる入れ物
session_start();

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "hogehoge";  // ユーザー名
$db['pass'] = "pass";  // ユーザー名のパスワード
$db['dbname'] = "hogehoge";  // データベース名
// エラーメッセージの初期化
$errorMessage = "";

// ログインボタンが押された場合
if (isset($_POST["login"])) {
    // 1. ユーザIDの入力チェック
    if (empty($_POST["userid"])) {  // emptyは値が空のとき
        $errorMessage = 'ユーザーIDが未入力です。';
    } else if (empty($_POST["password"])) {
        $errorMessage = 'パスワードが未入力です。';
    }

    if (!empty($_POST["userid"]) && !empty($_POST["password"])) {
        // 入力したユーザIDを格納
        $userid = $_POST["userid"];

        // 2. ユーザIDとパスワードが入力されていたら認証する
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
	
            $stmt = $pdo->prepare('SELECT * FROM SiyakusyoKaigoUserData WHERE id = ?');
            $stmt->execute(array($userid));

            $password = $_POST["password"];
		//rowの中にstmtのfetchを使ってをデータを入れる
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		//入力されたパスワードのハッシュと正解のパスワードを比較
                if (password_verify($password, $row['password'])) {
		//新しいセッションで古いのを置き換える
                    session_regenerate_id(true);

                   /* $id = $row['id'];
                    $sql = "SELECT * FROM userData WHERE id = $id";  //入力されたIDからデータを引っ張る
                    $stmt = $pdo->query($sql);
                    */
		    $_SESSION["NAME"] = $row['name'];	//今後のセッション管理で使う
		    $_SESSION["BUNRUI"] = $row['bunrui'];	//今後のセッション管理で使う

			if($row['bunrui'] == 1){
                    		header("Location: SiyakusyoTop.php");  //市役所メイン画面へ遷移
			}else if($row['bunrui'] == 2){
                    		header("Location: KaigosiTop.php");  // 介護師画面へ遷移
			}else{
				$errorMessage = '登録が不十分なユーザーです。';

			}
                    exit();  // 処理終了
                } else {
                    // 認証失敗
                    $errorMessage = 'ユーザーIDあるいはパスワードに誤りがあります。';
                }
            } else {
                // 4. 認証成功なら、セッションIDを新規に発行する
                // 該当データなし
                $errorMessage = 'ユーザーIDあるいはパスワードに誤りがあります。';
            }
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            //$errorMessage = $sql;
            // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
            // echo $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ログイン画面</title>

<style type="text/css">

input.example, select {
width: 250px;
}
textarea {
width: 250px;
height: 7em;
}

input, select, textarea {
font-size: 170%;
}

</style>

</head>
<body>
<CENTER>
<br><br><br>
	<form id="loginForm" name="loginForm" action="" method="POST">
	<font size="5">
            <fieldset>
                <legend>ログインフォーム</legend>
                <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
                <label for="userid">ユーザーID</label><input style="width:200px; height:50px;" type="text" id="userid" name="userid" placeholder="ユーザーIDを入力" value="<?php if (!empty($_POST["userid"])) {echo htmlspecialchars($_POST["userid"], ENT_QUOTES);} ?>">
                <br>
                <label for="password">パスワード</label><input style="width:200px; height:50px;" type="password" id="password" name="password" value="" placeholder="パスワードを入力">
                <br>
                <input type="submit" id="login" name="login" value="ログイン" style="width:100px; height:40px;">
            </fieldset>
		</font>
	</form>
	</CENTER>
	
</body>
</html>
