<?php
// セッション開始

session_start();

if (!isset($_SESSION["NAME"]) ||  !($_SESSION["BUNRUI"] == 1  || $_SESSION["BUNRUI"] == 2)) {
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

    if (!empty($_POST["username"]) && !empty($_POST["house"])){
        // 入力したユーザ名とパスワードを格納
        $username = $_POST["username"];
	$house = $_POST["house"];
	$kouhoti = $_POST["kouhoti"];
	

	
        // 2. ユーザIDとパスワードが入力されていたら認証する
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            
            
                $stmt = $pdo->prepare('SELECT max(id) as id FROM RiyousyaData');
                $stmt->execute();
                
                while($row = $stmt->fetch()){
                    $rows[]=$row;
                }
                foreach($rows as $row){
                    if(empty($row['id'])){
                        $userid = 1;
                    }else{
                        $userid = $row['id']+1;  // 登録した(DB側でauto_incrementした)IDを$useridに入れる
                    }
                }
                    
                $imgname='/var/www/html/photo/'.$userid.'.jpg';
                
	if(is_uploaded_file($_FILES['file']['tmp_name'])){
        	move_uploaded_file($_FILES['file']['tmp_name'], $imgname);//$_FILES['file']['name']);//'./img/'.$_FILES['file']['name']);
    }
            $stmt = $pdo->prepare("INSERT INTO RiyousyaData(name,photo,house,kouhoti) VALUES (?, ?, ?, ?)");
            $userid=$userid.'.jpg';
            $stmt->execute(array($username, $userid,$house,$kouhoti));  // パスワードのハッシュ化を行う（今回は文字列のみなのでbindValue(変数の内容が変わらない)を使用せず、直接excuteに渡しても問題ない）
            $userid = $pdo->lastinsertid('id');  // 登録した(DB側でauto_incrementした)IDを$useridに入れる

            $signUpMessage = '登録が完了しました。'. $username. 'さんの登録IDは '. $userid.  'です。' ; //パスワードは '. $password. ' です。必ず覚えておいてください。';  // ログイン時に使用するIDとパスワード
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
             echo $e->getMessage();
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
history.replaceState({location_replace: "RiyousyaItiran.php"}, "")
history.pushState({}, "", "RiyousyaSignUp.php")

window.addEventListener("popstate", eve => {
	if(eve.state && eve.state.location_replace){
		location.replace(eve.state.location_replace)
	}
})
</script>

        <h1>新規登録画面</h1>
        <form id="loginForm" name="loginForm" action="" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend>新規登録フォーム</legend>
                <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
                <div><font color="#0000ff"><?php echo htmlspecialchars($signUpMessage, ENT_QUOTES); ?></font></div>
                <label for="username">利用者名</label><input type="text" id="username" name="username" placeholder="利用者名を入力" value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>">
                <br>
                <br>
                <label for="house">住所</label><input type="text" id="house" name="house" placeholder="住所を入力" value="<?php if (!empty($_POST["house"])) {echo htmlspecialchars($_POST["house"], ENT_QUOTES);} ?>">
                <br>                
                <br>
                <label for="kouhoti">候補地</label>
                <br>
                <textarea form="loginForm" rows="5" cols="30" id="kouhoti" name="kouhoti" placeholder="移動先候補地" ><?php if (!empty($_POST["kouhoti"])) {echo htmlspecialchars($_POST["kouhoti"], ENT_QUOTES);} ?></textarea>
                <br>
                <br>
                写真<input type="file" name="file" accept="image/*">
                <br>
                <br>
                <input type="submit" id="signUp" name="signUp" value="新規登録">
            </fieldset>
        </form>
        <br>
        <form action="RiyousyaItiran.php">
            <input type="submit" value="戻る">
        </form>
    </body>
</html>
