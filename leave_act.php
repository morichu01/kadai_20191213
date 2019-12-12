<?php
// var_dump($_POST);
// exit();

include('functions.php');

// 入力チェック
if (
  !isset($_POST['name']) || $_POST['name'] == ''
) {
  exit('ParamError');
}

$name = $_POST['name'];

//DB接続
$pdo = connectToDb();

// sql作成＆実行
$sql = 'SELECT * FROM user_table WHERE lid=:a1';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':a1', $name, PDO::PARAM_STR);
$res = $stmt->execute();

// var_dump($res);
// exit();

//3. SQL実行時にエラーがある場合
$id = '';
if ($res == false) {
  showSqlErrorMsg($stmt);
} else {
  while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // var_dump($result);
    // exit();

    if ($result['id'] == '') {
      echo 'ユーザ登録されていません';
      header('Location: login.php');
    } else {
      $id = $result['id'];
      // var_dump($id);

      // life_flgを1にセットするsql作成＆実行
      $sql = 'UPDATE user_table SET life_flg= 1 WHERE id=:b1';
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':b1', $id, PDO::PARAM_INT);
      $res = $stmt->execute();
      if ($res == false) {
        showSqlErrorMsg($stmt);
      } else {
        header('Location: login.php');
      }
    }
  }
}
