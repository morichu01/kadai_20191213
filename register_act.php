<?php
// var_dump($_POST);
// exit();

include('functions.php');

// 入力チェック
if (
  !isset($_POST['name']) || $_POST['name'] == '' ||
  !isset($_POST['lpw']) || $_POST['lpw'] == '' ||
  !isset($_POST['kanri_flg']) || $_POST['kanri_flg'] == ''
) {
  exit('ParamError');
}

// exit();

//POSTデータ取得
$name = $_POST['name'];
$pass = $_POST['lpw'];
$kanri_flg = (int) $_POST['kanri_flg'];

// var_dump(gettype($kanri_flg));
// exit();

//ハッシュを作る
$hash = password_hash($pass, PASSWORD_BCRYPT);

// var_dump($name . ' ' . $pass . ' ' . $hash . ' ' . $kanri_flg);
// exit();

//DB接続
$pdo = connectToDb();

// user_tableに保存する
$sql = 'INSERT INTO user_table (`id`, `name`, `lid`, `lpw`, `kanri_flg`, `life_flg`) VALUES (NULL, :a1, :a2, :a3, :a4, 0)';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':a1', $name, PDO::PARAM_STR);
$stmt->bindValue(':a2', $name, PDO::PARAM_STR);
$stmt->bindValue(':a3', $hash, PDO::PARAM_STR);
$stmt->bindValue(':a4', $kanri_flg, PDO::PARAM_INT);
$status = $stmt->execute();

//データ登録処理後
if ($status == false) {
  showSqlErrorMsg($stmt);
} else {
  //index.phpへリダイレクト
  header('Location: index.php');
}
