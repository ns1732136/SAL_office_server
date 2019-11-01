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
$deleteBack = "RiyousyaSyousai.php";

// ログインボタンが押された場合
if(isset($_POST["signUp"])) {
    // 1. ユーザ名の入力チェック
    if (empty($_POST["username"])) {  // 値が空のとき
        $errorMessage = 'ユーザー名が未入力です。';
    }

    if (!empty($_POST["username"]) && !empty($_POST["house"])){
        // 入力したユーザ名とパスワードを格納
        $username = $_POST["username"];
	$house = $_POST["house"];
	$kouhoti = $_POST["kouhoti"];
	$userid = $_SESSION["edit"];
	
        // 2. ユーザIDとパスワードが入力されていたら認証する
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            
            $imgname='/var/www/html/photo/'.$userid.'.jpg';
                
            if(is_uploaded_file($_FILES['file']['tmp_name'])){
                move_uploaded_file($_FILES['file']['tmp_name'], $imgname);//$_FILES['file']['name']);//'./img/'.$_FILES['file']['name']);
            }
            $stmt = $pdo->prepare("UPDATE RiyousyaData SET name=?,house=?,kouhoti=? WHERE id=? ");
            $stmt->execute(array($username,$house,$kouhoti,$userid ));  // パスワードのハッシュ化を行う（今回は文字列のみなのでbindValue(変数の内容が変わらない)を使用せず、直接excuteに渡しても問題ない）
            $userid = $pdo->lastinsertid('id');  // 登録した(DB側でauto_incrementした)IDを$useridに入れる

            $signUpMessage = '登録が完了しました。';//. $username. 'さんの登録IDは '. $userid.  'です。' ; //パスワードは '. $password. ' です。必ず覚えておいてください。';  // ログイン時に使用するIDとパスワード
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
             echo $e->getMessage();
        }
    }
}else if(isset($_POST["delete"])){
	$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
    if($_SESSION["edit"]>10){   //デモで消去されないようにする処理
        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare("DELETE FROM RiyousyaData WHERE id = ?");

            $stmt->execute(array($_SESSION["edit"]));
            
            $signUpMessage ='削除完了';
            
            $deleteBack = "RiyousyaItiran.php";
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

            $stmt = $pdo->prepare("SELECT * FROM RiyousyaData WHERE id=?");

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
history.replaceState({location_replace: "<?php echo $deleteBack ?>"}, "")
history.pushState({}, "", "RiyousyaEdit.php")

window.addEventListener("popstate", eve => {
	if(eve.state && eve.state.location_replace){
		location.replace(eve.state.location_replace)
	}
})
</script>

        <h1>編集画面</h1>
        
        <form id="loginForm" name="loginForm" action="" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend>編集フォーム</legend>
                <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
                <div><font color="#0000ff"><?php echo htmlspecialchars($signUpMessage, ENT_QUOTES); ?></font></div>
                
                <?php
                    foreach($rows as $row){
                ?>
                <label for="username">利用者名</label><input type="text" id="username" name="username" placeholder="<?php echo htmlspecialchars($row['name'], ENT_QUOTES);?>" value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);}else{ echo htmlspecialchars($row['name'], ENT_QUOTES);} ?>">
                <br>
                <br>
                <label for="house">住所</label><input type="text" id="house" name="house" placeholder="<?php echo htmlspecialchars($row['house'], ENT_QUOTES);?>" value="<?php if (!empty($_POST["house"])) {echo htmlspecialchars($_POST["house"], ENT_QUOTES);}else{ echo htmlspecialchars($row['house'], ENT_QUOTES);} ?>">
                <br>                
                <br>
                <label for="kouhoti">候補地</label>
                <br>
                <textarea form="loginForm" rows="5" cols="30" id="kouhoti" name="kouhoti" placeholder="<?php echo htmlspecialchars($row['kouhoti'], ENT_QUOTES);?>"><?php if (!empty($_POST["kouhoti"])) {echo htmlspecialchars($_POST["kouhoti"], ENT_QUOTES);}else{echo htmlspecialchars($row['kouhoti'], ENT_QUOTES);} ?></textarea>
                <br>
                <br>
                写真<input type="file" name="file" accept="image/*">
                <br>
                <br>

                <input type="submit" id="signUp" name="signUp" value="保存">
                                        <?php
            }
        ?>
            </fieldset>


        </form>
        <br>
        <form action="<?php echo $deleteBack ?>">
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
