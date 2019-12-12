<?php
include('functions.php');

$pdo = connectToDb();




?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>todoリスト表示</title>
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
      <a class="navbar-brand" href="#">新規登録</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="login.php">ログインページ</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <form method="post" action="register_act.php">
    <div class="form-group">
      <label for="name">name</label>
      <input type="text" class="form-control" id="name" name="name">
    </div>
    <div class="form-group">
      <label for="lpw">Pass</label>
      <input type="password" class="form-control" id="lpw" name="lpw">
    </div>
    <div class="form-group">
      <label for="kanri_flg">権限</label>
      <p>
        管理者: <input type="radio" class="form-control" id="kanri_flg" name="kanri_flg" value="1">
        一般: <input type="radio" class="form-control" id="kanri_flg" name="kanri_flg" value="0" checked="checked">
      </p>
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </form>

</body>

</html>