<?php
session_start();

// ログイン状態チェック
if (!isset($_SESSION["NAME"]) || !($_SESSION["BUNRUI"] == 1 || $_SESSION["BUNRUI"] == 2)) {
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

?>

<!DOCTYPE html>


<html lang="ja">

	<head>
<meta charset="UTF-8">
		<title>利用者詳細</title>


<style type="text/css">
tr{text-align: center;font-size: 130%;}
td{border: solid 2px;}
</style>


	</head>

	
	<body>
		
<script>
history.replaceState({location_replace: "<?php if($_SESSION["BUNRUI"] == 1){echo "RiyousyaItiran.php";}else if($_SESSION["BUNRUI"] == 2){echo "KaigosiTop.php";}?>", "")
history.pushState({}, "", "RiyousyaSyousai.php")

window.addEventListener("popstate", eve => {
	if(eve.state && eve.state.location_replace){
		location.replace(eve.state.location_replace)
	}
})
</script>
		
<?php
	foreach($rows as $row){
?>
<?php
	} 
?>
		<div align="center">
				<p>
				<font size="8" align="left"><?php echo htmlspecialchars($row['name'], ENT_QUOTES); ?>様</font>
				</p>
					
				<p>
				<font size="6"></font>
				</p>

				<p>
				<img src="../photo/<?php echo htmlspecialchars($row['photo'], ENT_QUOTES); ?>" width="350" height="350" border="5">
				</p>
				
				
				
<table style="width:80%;margin: 0 auto;" border ="2" >
<tr><td>住所</td>
	<td><?php echo htmlspecialchars($row['house'], ENT_QUOTES); ?></td></tr>
<tr>
	<td>行きそうな場所</td>
	<td>
		<?php echo htmlspecialchars($row['kouhoti'], ENT_QUOTES); ?>
	</td>
</tr>
</table>


	<p><form action="RiyousyaEdit.php">
            <input type="submit" value="編集" style="width:40%;height:50px; font:30pt MSゴシック; font-weight:blod;">
	</form></p>

        <p><form action="<?php if($_SESSION["BUNRUI"] == 1){echo "RiyousyaItiran.php";}else if($_SESSION["BUNRUI"] == 2){echo "KaigosiTop.php";}?>">
            <input type="submit" value="戻る" style="width:40%;height:50px; font:30pt MSゴシック; font-weight:blod;">
	</form></p>

</div>


	</body>

</html>
