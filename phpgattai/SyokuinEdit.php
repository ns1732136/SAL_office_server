<?php
// セッション開始

session_start();

if (!isset($_SESSION["NAME"]) || $_SESSION["BUNRUI"] != 1) {
    header("Location: Logout.php");
    exit;
}

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "hogehoge";  // ユーザー名
$db['pass'] = "pass";  // ユーザー名のパスワード
$db['dbname'] = "hogehoge";  // データベース名

// エラーメッセージ、登録完了メッセージの初期化
$errorMessage = "";
$signUpMessage = "";

// 保存ボタンが押された場合
if (isset($_POST["signUp"])) {
    // 1. ユーザ名の入力チェック
    if (empty($_POST["username"])) {  // 値が空のとき
        $errorMessage = 'ユーザー名が未入力です。';
    }
    if (!empty($_POST["username"])) {
        $username = $_POST["username"];
        $mail = $_POST["mail"];
	$syozoku = $_POST["syozoku"];

        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare("UPDATE SiyakusyoKaigoUserData SET name=? , syozoku=? ,mail=? WHERE id=? ");

            $stmt->execute(array($username, $syozoku,$mail,$_SESSION["edit"]));  // パスワードのハッシュ化を行う（今回は文字列のみなのでbindValue(変数の内容が変わらない)を使用せず、直接excuteに渡しても問題ない）

            $signUpMessage = '編集が完了しました。'.$username.'の登録IDは '. $_SESSION["edit"]. ' でメールアドレスは'.$mail.'です。';  // ログイン時に使用するIDとパスワード
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
            // echo $e->getMessage();
        }
   }
}else if(isset($_POST["delete"])){
	$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
    if($_SESSION["edit"]!=1){
        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare("DELETE FROM SiyakusyoKaigoUserData WHERE id = ?");

            $stmt->execute(array($_SESSION["edit"]));
            
            $signUpMessage ='削除完了';

        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
            // echo $e->getMessage();
        }
        
    }else{
            $errorMessage = 'デモ用に削除できません。';
    }
}else{


        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare("SELECT * FROM SiyakusyoKaigoUserData WHERE id=?");

            $stmt->execute(array($_SESSION["edit"]));
		while($row = $stmt->fetch()){
                	$rows[]=$row;
        	}

        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
            // echo $e->getMessage();
        }



}
?>

<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
            <title>編集</title>
    </head>
    <body>
<script>
//bodyの一番上に入れる
history.replaceState({location_replace: "SyokuinItiran.php"}, "")
history.pushState({}, "", "SyokuinEdit.php")

window.addEventListener("popstate", eve => {
	if(eve.state && eve.state.location_replace){
		location.replace(eve.state.location_replace)
	}
})
</script>

        <h1>編集画面</h1>
        <form id="loginForm" name="loginForm" action="" method="POST">
            <fieldset>
                <legend>職員情報編集フォーム</legend>
                <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
                <div><font color="#0000ff"><?php echo htmlspecialchars($signUpMessage, ENT_QUOTES); ?></font></div>
                
                
<?php
	foreach($rows as $row){
?>
                <label for="username">ユーザー名</label><input type="text" id="username" name="username" placeholder="<?php echo htmlspecialchars($row['name'], ENT_QUOTES);?>" value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);}else{ echo htmlspecialchars($row['name'], ENT_QUOTES);} ?>">
                <br>
		<label for="syozoku">所属</label><input type="text" id="syozoku" name="syozoku" placeholder="<?php echo htmlspecialchars($row['syozoku'], ENT_QUOTES);?>" value="<?php if (!empty($_POST["syozoku"])) {echo htmlspecialchars($_POST["syozoku"], ENT_QUOTES);}else{echo htmlspecialchars($row['syozoku'], ENT_QUOTES);} ?>">
                <br>
                <label for="mail">メールアドレス</label><input type="text" id="mail" name="mail" placeholder="<?php echo htmlspecialchars($row['mail'], ENT_QUOTES);?>" value="<?php if (!empty($_POST["mail"])) {echo htmlspecialchars($_POST["mail"], ENT_QUOTES);}else{ echo htmlspecialchars($row['mail'], ENT_QUOTES);} ?>">
                <br>
                

                <input type="submit" id="signUp" name="signUp" value="保存">
                <?php
}
?>
            </fieldset>
        </form>
        <br>
        <form action="SyokuinItiran.php">
            <input type="submit" value="戻る">
        </form>
        <?php for($i=1 ;$i<=30;$i++){?>
        <br>       
        <?php } ?>
	<form action="" method="POST">
            <input type="submit" id="delete" name="delete" value="削除">
        </form>
    </body>
</html>
