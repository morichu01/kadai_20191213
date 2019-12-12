<?php
// 共通で使うものを別ファイルにしておきましょう。

// DB接続関数（PDO）
function connectToDb()
{
  $db = 'mysql:dbname=gsacfl02_05;charset=utf8;port=3306;host=localhost';
  $user = 'root';
  $pwd = '';
  try {
    return new PDO($db, $user, $pwd);
  } catch (PDOException $e) {
    exit('dbError:' . $e->getMessage());
  }
}

// influenza DB接続関数（PDO）
function connectToFluDb()
{
  $db = 'mysql:dbname=influenza;charset=utf8;port=3306;host=localhost';
  $user = 'root';
  $pwd = '';
  try {
    return new PDO($db, $user, $pwd);
  } catch (PDOException $e) {
    exit('dbError:' . $e->getMessage());
  }
}

// SQL処理エラー
function showSqlErrorMsg($stmt)
{
  $error = $stmt->errorInfo();
  exit('sqlError:' . $error[2]);
}

// SESSIONチェック＆リジェネレイト
function checkSessionId()
{
  // 失敗時はログイン画面に戻る（セッションidがないor一致しない）
  if (
    !isset($_SESSION['session_id']) || $_SESSION['session_id'] != session_id()
  ) { // ログイン失敗時の処理（ログイン画面に移動）
    header('Location: login.php');
  } else { // ログイン成功時の処理（一覧画面に移動）
    session_regenerate_id(true); // セッションidの再生成
    $_SESSION['session_id'] = session_id(); // セッション変数に格納
  }
}

//退会チェック
function checkLeave($name)
{
  $pdo = connectToDb();

  // ユーザIDでSELECTする
  $sql = 'SELECT * FROM user_table WHERE lid=:lid';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':lid', $name, PDO::PARAM_STR);
  $res = $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user['life_flg'] == 1) {
    echo 'あなたは退会しています<br>';
    // header('Location: login.php');
    echo '<a href="login.php">ログイン画面へ</a>';
    exit();
  }
}

// menuを決める
function menu()
{
  $menu = '<li class="nav-item"><a class="nav-link" href="index.php">todo登録</a></li><li class="nav-item"><a class="nav-link" href="select.php">todo一覧</a></li>';
  $menu .= '<li class="nav-item"><a class="nav-link" href="logout.php">ログアウト</a></li>';
  return $menu;
}

// admin用menu
function menuAdmin()
{
  $menu = '<li class="nav-item"><a class="nav-link" href="index.php">todo登録</a></li><li class="nav-item"><a class="nav-link" href="select.php">todo一覧</a></li>';
  $menu .= '<li class="nav-item"><a class="nav-link" href="flu.php">インフルエンザ情報</a></li>';
  $menu .= '<li class="nav-item"><a class="nav-link" href="secret.php">極秘プロジェクト</a></li>';
  $menu .= '<li class="nav-item"><a class="nav-link" href="./otoshidama/index.html">お年玉ルーレット</a></li>';
  $menu .= '<li class="nav-item"><a class="nav-link" href="logout.php">ログアウト</a></li>';
  return $menu;
}
