<?php
// var_dump($_POST);
// exit();
//最初にSESSIONを開始！！
session_start();


//0.外部ファイル読み込み
include('functions.php');

// 入力チェック
if (
  !isset($_POST['lid']) || $_POST['lid'] == '' ||
  !isset($_POST['lpw']) || $_POST['lpw'] == ''
) {
  exit('ParamError');
}

$lid = $_POST['lid'];
$lpw = $_POST['lpw'];

//1.  DB接続&送信データの受け取り
$pdo = connectToDb();

// ユーザIDでSELECTする
$sql = 'SELECT * FROM user_table WHERE lid=:lid';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$res = $stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);


//3. SQL実行時にエラーがある場合
if ($res == false) {
  showSqlErrorMsg($stmt);
}

// var_dump($user);
// exit();


// ユーザがいない
if (!$user['lid']) {
  echo 'ユーザ名が正しくありません。';
  // header('Location: login.php');
  exit();
}

// パスワードチェック
if (!password_verify($lpw, $user['lpw'])) {
  echo 'パスワードが正しくありません。';
  // header('Location: login.php');
  exit();
}

// exit("logein success!");


// 5. 該当レコードがあればSESSIONに値を代入
if ($user['id'] != '') {
  // ログイン成功の場合はセッション変数に値を代入
  $_SESSION = array(); // session変数を空にする
  $_SESSION['session_id'] = session_id(); // idを格納
  $_SESSION['kanri_flg'] = $user['kanri_flg']; // 管理者かどうかの判定
  $_SESSION['life_flg'] = $user['life_flg']; // 退会したかどうかの判定
  $_SESSION['name'] = $user['name']; // ユーザ名の取得
  header('Location: select.php'); // 一覧画面に移動

} else {
  header('Location: login.php'); // 一覧画面に移動
}

exit();
