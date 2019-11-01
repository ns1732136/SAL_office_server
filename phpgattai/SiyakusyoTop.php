
<?php
session_start();

// ログイン状態チェック
if (!isset($_SESSION["NAME"]) || $_SESSION["BUNRUI"] != 1) {
    header("Location: Logout.php");
    exit;
}

if(isset($_POST["syousai"])){
	$_SESSION["edit"]=$_POST["syousai"];
	header("Location:KyouryokuOpen.php");
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

        $stmt = $pdo->query('SELECT * FROM HaikaiRireki AS h, RiyousyaData AS d WHERE h.riyousyaid = d.id AND status = 2');
        
        
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

<title>top</title>
</head>

<body>


 <h1>メイン画面</h1>
	<ul>
            <li><a href="Logout.php">ログアウト</a></li>
        </ul>


<h2>介護者からの新着通知</h2>
<DIV STYLE="FLOAT: RIGHT">
<font size="5">
<p align="right"><a href="RiyousyaItiran.php">利用者一覧</a></p>
<p align="right"><a href="SousakuLog.php">捜索履歴</a></p>
<p align="right"><a href="KaigosiItiran.php">介護師一覧</a></p>
<p align="right"><a href="SyokuinItiran.php">職員一覧</a></p>
<p align="right"><a href="KyouryokusyaItiran.php">協力者一覧</a></p>
</font>
</DIV>
<DIV STYLE="FLOAT: LEFT">
<table border ="2">
    
    
<?php
	foreach($rows as $row){
?>
<tr>
<td width="200" height="50">
    <form id="editForm" name="editForm<?php echo $row['number'];?>" action="" method="POST">
	<input type="hidden" name="syousai" value="<?php echo $row['number'];?>">
    <a href="javascript:editForm<?php echo $row['number'];?>.submit()"><?php echo $row['name']; ?></a>
    </form>
    </td>
</tr>
<?php
	} 
?>
<!--
<script>
var array1 = new Array('山田 ○○', '鈴木 ××', '佐藤 △△','高橋 〇×','田中 〇×','伊藤 △×','渡辺 ×△','山田 △○','鈴木 〇×','佐藤 △◇','小林 ××')
for(let i=0;i<11;i++){
	document.write('<tr><td width="200" height="50"><a href="');
	document.write('KyouryokuOpen.php">');
	document.write(array1[i]+'さん');
	document.write('</a></td></tr>');
}
</script>

-->
</table>
</DIV>

</body>
</html>
