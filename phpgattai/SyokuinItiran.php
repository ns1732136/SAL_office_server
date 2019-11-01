<?php
session_start();

// ログイン状態チェック
if (!isset($_SESSION["NAME"]) || $_SESSION["BUNRUI"] != 1) {
    header("Location: Logout.php");
    exit;
}

if(isset($_POST["edit"])){
	$_SESSION["edit"]=$_POST["id"];
	header("Location:SyokuinEdit.php");
	exit();
}

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "hogehoge";  // ユーザー名
$db['pass'] = "pass";  // ユーザー名のパスワード
$db['dbname'] = "hogehoge";  // データベース名
// エラーメッセージの初期化
$errorMessage = "";

// ログインボタンが押された場合
    // 1. ユーザIDの入力チェック

    // 2. ユーザIDとパスワードが入力されていたら認証する
$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // 3. エラー処理
try {
	$pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

        $stmt = $pdo->prepare('SELECT * FROM SiyakusyoKaigoUserData WHERE bunrui=1');
        
        
        $stmt->execute();
        //$rows = $stmt->fetch(PDO::FETCH_ASSOC);
        
        while($row = $stmt->fetch()){
                $rows[]=$row;
        }
        
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            //$errorMessage = $sql;
            // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
            // echo $e->getMessage();
        }
    


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="一覧テンプレ.css">

<title>職員一覧</title>
</head>

<body>

<script>
history.replaceState({location_replace: "SiyakusyoTop.php"}, "")
history.pushState({}, "", "SyokuinItiran.php")

window.addEventListener("popstate", eve => {
	if(eve.state && eve.state.location_replace){
		location.replace(eve.state.location_replace)
	}
})
</script>

<h2>職員一覧</h2>

<table border ="2" >
<tr>
<th>ID</th>
<th>職員名</th>
<th>所属</th>
<th>メールアドレス</th>
</tr>

<?php
	foreach($rows as $row){
?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo htmlspecialchars($row['name'], ENT_QUOTES); ?></td>
<td><?php echo htmlspecialchars($row['syozoku'], ENT_QUOTES); ?></td>
<td><?php echo htmlspecialchars($row['mail'], ENT_QUOTES); ?></td>
<td><form id="editForm" name="editForm" action="" method="POST">
	<input type="hidden" name="id" value="<?php echo $row['id'];?>">
	<input type="submit" name="edit" value="編集">
</form></td>

</tr>
<?php
	} 
?>
</table>


<form action="SyokuinSignUp.php">
<div><input id="add" type="submit" value="職員の追加"></div>
</form>

<form action="SiyakusyoTop.php">
<div><input id="add" type="submit" value="戻る"></div>
</form>
</body>
</html>
