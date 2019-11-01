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

// ログインボタンが押された場合
if (isset($_POST["signUp"])) {
    // 1. ユーザ名の入力チェック
    if (empty($_POST["username"])) {  // 値が空のとき
        $errorMessage = 'ユーザー名が未入力です。';
    }
    if (empty($_POST["mail"])) {  // 値が空のとき
        $errorMessage = 'メールアドレスが未入力です。';
    }
    if (!empty($_POST["username"]) && !empty($_POST["mail"])) {
        $username = $_POST["username"];
        $mail = $_POST["mail"];
	
        // 2. ユーザIDとパスワードが入力されていたら認証する
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare("INSERT INTO KyouryokusyaUserData(name, mail) VALUES (?, ?)");

            $stmt->execute(array($username, $mail));  // パスワードのハッシュ化を行う（今回は文字列のみなのでbindValue(変数の内容が変わらない)を使用せず、直接excuteに渡しても問題ない）
            $userid = $pdo->lastinsertid('id');  // 登録した(DB側でauto_incrementした)IDを$useridに入れる

            $signUpMessage = '登録が完了しました。'.$username.'の登録IDは '. $userid. ' です。';  // ログイン時に使用するIDとパスワード
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
            // echo $e->getMessage();
        }
   }
}
?>

<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
            <title>新規登録</title>
    </head>
    <body>
<script>
//bodyの一番上に入れる
history.replaceState({location_replace: "KyouryokusyaItiran.php"}, "")
history.pushState({}, "", "KyouryokusyaSignUp.php")

window.addEventListener("popstate", eve => {
	if(eve.state && eve.state.location_replace){
		location.replace(eve.state.location_replace)
	}
})
</script>

        <h1>新規登録画面</h1>
        <form id="loginForm" name="loginForm" action="" method="POST">
            <fieldset>
                <legend>協力者新規登録フォーム</legend>
                <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
                <div><font color="#0000ff"><?php echo htmlspecialchars($signUpMessage, ENT_QUOTES); ?></font></div>
                <label for="username">ユーザー名</label><input type="text" id="username" name="username" placeholder="協力者名を入力" value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>">
                <br>
		<label for="mail">メールアドレス</label><input type="text" id="mail" name="mail" placeholder="メールアドレスを入力" value="<?php if (!empty($_POST["mail"])) {echo htmlspecialchars($_POST["mail"], ENT_QUOTES);} ?>">
                <br>
                <input type="submit" id="signUp" name="signUp" value="新規登録">
            </fieldset>
        </form>
        <br>
        <form action="KyouryokusyaItiran.php">
            <input type="submit" value="戻る">
        </form>
    </body>
</html>
