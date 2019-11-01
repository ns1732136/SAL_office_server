<?php
session_start();

// ログイン状態チェック
if (!isset($_SESSION["NAME"]) || $_SESSION["BUNRUI"] != 1) {
    header("Location: Logout.php");
    exit;
}

if(isset($_POST["syousai"])){
	$_SESSION["edit"]=$_POST["id"];
	header("Location: SousakuSyousai.php");
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

        $stmt = $pdo->query('SELECT * FROM HaikaiRireki AS h, RiyousyaData AS d, StatusMei AS s WHERE h.riyousyaid = d.id AND h.status = s.status');
        
        
        //$stmt->execute();
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

<title>捜索履歴</title>
</head>

<body>
<h2>捜索履歴</h2>

<table border ="2" >
<tr>
<th>通報番号</th>
<th>利用者名</th>
<th>現在の状況</th>

<?php
	foreach($rows as $row){
?>
<tr>
<td><?php echo $row['number']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['statusmei']; ?></td>
<td>
	<form id="editForm" name="editForm" action="" method="POST">
	<input type="hidden" name="id" value="<?php echo $row['number'];?>">
	<input type="submit" name="syousai" value="詳細">
	</form>
</td>
</tr>
<?php
	} 
?>
</table>
	<form action="SiyakusyoTop.php">
	<div><input id="add" type="submit" value="戻る"></div>
	</form>
	</body>


</body>
</html>
