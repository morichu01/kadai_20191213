<?php
// セッションのスタート
session_start();

//0.外部ファイル読み込み
include('functions.php');

// ログイン状態のチェック
checkSessionId();

$menu = menu();

// getで送信されたidを取得
if (!isset($_GET['id'])) {
  exit("Error");
}
$id = $_GET['id'];

//DB接続します
$pdo = connectToDb();

//データ登録SQL作成，指定したidのみ表示する
$sql = 'SELECT * FROM php02_table WHERE id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

//データ表示
if ($status == false) {
  showSqlErrorMsg($stmt);
} else {
  $rs = $stmt->fetch();
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>todo更新ページ</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <style>
    div {
      padding: 10px;
      font-size: 16px;
    }
  </style>
</head>

<body>

  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="#">todo更新</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <?= $menu ?>
        </ul>
      </div>
    </nav>
  </header>

  <form method="post" action="update.php">
    <div class="form-group">
      <label for="task">Task</label>
      <input type="text" class="form-control" id="task" name="task" placeholder="Enter task" value="<?= $rs['task'] ?>">
    </div>
    <div class="form-group">
      <label for="deadline">Deadline</label>
      <input type="date" class="form-control" id="deadline" name="deadline" value="<?= $rs['deadline'] ?>">
    </div>
    <div class="form-group">
      <label for="comment">Comment</label>
      <textarea class="form-control" id="comment" name="comment" rows="3"><?= $rs['comment'] ?></textarea>
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    <input type="hidden" name="id" value="<?= $rs['id'] ?>">
  </form>

</body>

</html>