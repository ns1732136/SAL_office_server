<?php
session_start();

// ログイン状態チェック
if (!isset($_SESSION["NAME"]) || !($_SESSION["BUNRUI"] == 1)) {
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

            $stmt = $pdo->prepare("SELECT * FROM HaikaiRireki AS h, RiyousyaData AS d, StatusMei AS s WHERE h.riyousyaid = d.id AND h.status = s.status AND h.number = ?");

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
		<title>捜索詳細</title>


<style type="text/css">
tr{text-align: center;font-size: 130%;}
td{border: solid 2px;}
</style>


	</head>

	
	<body>
		
<script>
history.replaceState({location_replace: "SousakuLog.php", "")
history.pushState({}, "", "SousakuSyousai.php")

window.addEventListener("popstate", eve => {
	if(eve.state && eve.state.location_replace){
		location.replace(eve.state.location_replace)
	}
})
</script>
		
<?php
	foreach($rows as $row){}
?>

		<div align="center">
				<p>
				<font size="8" align="left"><?php echo htmlspecialchars($row['name'], ENT_QUOTES); ?>様</font>
				</p>
					
				<p>
				<font size="6">通報された際の画像</font>
				</p>

				<p>
				<img src="../photo/<?php echo htmlspecialchars($row['photo'], ENT_QUOTES); ?>" width="350" height="350" border="5">
				</p>
				
				
				
<table style="width:80%;margin: 0 auto;" border ="2" >
<tr><td>通報日時</td>
	<td><?php echo htmlspecialchars($row['tuuhoutime'], ENT_QUOTES); ?></td></tr>
<tr>
	<td>情報公開日時</td>
	<td>
		<?php echo htmlspecialchars($row['opentime'], ENT_QUOTES); ?>
	</td>
</tr>
<tr>
	<td>発見日時</td>
	<td>
		<?php echo htmlspecialchars($row['hakkentime'], ENT_QUOTES); ?>
	</td>
</tr>
<tr>
	<td>現在の状態</td>
	<td>
		<?php echo htmlspecialchars($row['statusmei'], ENT_QUOTES); ?>
	</td>
</tr>
<tr>
	<td>発見時の状態</td>
	<td>
		<?php echo htmlspecialchars($row['riyousyastatus'], ENT_QUOTES); ?>
	</td>
</tr>
</table>


	<p><form action="LogEdit.php">
            <input type="submit" value="捜索状況の変更" style="width:40%;height:50px; font:30pt MSゴシック; font-weight:blod;">
	</form></p>

        <p><form action="SousakuLog.php">
            <input type="submit" value="戻る" style="width:40%;height:50px; font:30pt MSゴシック; font-weight:blod;">
	</form></p>

</div>


	</body>

</html>
