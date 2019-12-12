<?PHP
session_start();

include('functions.php');

//ログイン状態のチェック
checkSessionId();

//life_flgの状態チェック
$name = $_SESSION['name'];
checkLeave($name);

//influenza DB接続
$pdo = connectToFluDb();

//データ登録SQL作成
$sql = 'SELECT * FROM influenza';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

//変数定義
$region = [];
$year = [];
$mounth = [];
$patients = [];

//データ登録処理後
if ($status == false) {
  showSqlErrorMsg($stmt);
} else {
  while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $region[] .= $result['region'];
    $year[] .= $result['year'];
    $mounth[] .= $result['mounth'];
    $patients[] .= $result['patients'];
  }
  // var_dump($patients);
  // exit();

  //jsへ変数受け渡し
  $regionJs = json_encode($region);
  $yearJs = json_encode($year);
  $mounthJs = json_encode($mounth);
  $patientsJs = json_encode($patients);
}

//SQL文
// SELECT `year`,`mounth`, ROUND(sum(`patients`), 2) FROM `influenza` WHERE `year`='2015' GROUP BY `mounth`

// SELECT `region`,`year`, ROUND(SUM(`patients`),2) AS 'patients' FROM `influenza` WHERE `year`='2017' GROUP BY `region` ORDER BY `region` ASC
// "中央区", "南区", "博多区", "城南区", "早良区", "東区", "西区

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>インフルエンザ情報</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <script src="js/Chart.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <style>
    h1 {
      font-size: 30px;
    }

    h2 {
      font-size: 20px;
      font-weight: normal;
    }

    .wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    div {
      padding: 10px;
      font-size: 16px;
    }

    /* canvas {
      -moz-user-select: none;
      -webkit-user-select: none;
      -ms-user-select: none;
    } */
  </style>
</head>

<body>

  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="#">インフルエンザ情報</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="logout.php">ログアウト</a></li>
        </ul>
      </div>
    </nav>
  </header>

  <div class="wrapper">
    <h1>福岡県のインフルエンザ発生状況</h1>
    <h2>1.年別</h2>
    <!-- <div class="container">
      <div class="row"> -->
    <!-- chart area -->
    <!-- <div class="col-sm-6"> -->
    <div id="chart-area1" style="width:70%;"></div>
    <!-- </div>
      </div> -->
    <!-- </div> -->

    <h2>2.地域別</h2>
    <!-- <div class=" container">
      <div class="row"> -->
    <!-- chart area -->
    <!-- <div class="col-sm-6"> -->
    <div id="chart-area2" style="width:70%;"></div>
    <!-- </div>
      </div>
    </div> -->
  </div>

  <!-- <script src="js/mycharts.js"></script>
 -->
  <script>
    //PHPから変数取得
    const regionPhp = JSON.parse('<?php echo $regionJs; ?>');
    const yearPhp = JSON.parse('<?php echo $yearJs; ?>');
    const mounthPhp = JSON.parse('<?php echo $mounthJs; ?>');
    const patientsPhp = JSON.parse('<?php echo $patientsJs; ?>');
    // console.log(yearPhp);
    // console.log(patientsPhp);

    //-------------------------
    // 年別折線グラフ
    //-------------------------
    const year = {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      year2015: [0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1.66, 1.03, 0.51, 3.64, 4.84, 16.77],
      year2016: [464.20, 1625.36, 1274.50, 144.12, 16.15, 0.74, 0.33, 0.09, 9.65, 15.64, 24.02, 245.00],
      year2017: [1069.64, 1320.00, 509.97, 244.04, 29.44, 9.24, 5.43, 1.79, 5.19, 32.23, 65.28, 1144.82],
      year2018: [4313.10, 3091.88, 453.24, 70.12, 32.56, 28.46, 3.10, 1.02, 10.54, 9.90, 22.42, 309.90],
      year2019: [, , , , , , , , , , , ],
      // timestamp: ["2018/04/16 22:18", "2018/04/16 23:18", "2018/04/17 00:18", "2018/04/17 01:18", "2018/04/17 02:18", "2018/04/17 09:18", "2018/04/17 10:18", "2018/04/17 11:18", "2018/04/17 12:18", "2018/04/17 13:18", "2018/04/17 14:18", "2018/04/17 15:18"]
    };

    // グラフ定義
    const loadChartsLine = function() {
      const chartDataSet = {
        type: 'line',
        data: {
          labels: year.labels,
          datasets: [{
            label: 'year2015',
            data: year.year2015,
            backgroundColor: 'rgba(60, 160, 220, 0.3)',
            borderColor: 'rgba(60, 160, 220, 0.8)'
          }, {
            label: 'year2016',
            data: year.year2016,
            backgroundColor: 'rgba(255,100,150, 0.3)',
            borderColor: 'rgba(255,0,100, 0.8)'
          }, {
            label: 'year2017',
            data: year.year2017,
            backgroundColor: 'rgba(60, 190, 20, 0.3)',
            borderColor: 'rgba(60, 190, 20, 0.8)'
          }, {
            label: 'year2018',
            data: year.year2018,
            backgroundColor: 'rgba(153,102,255, 0.3)',
            borderColor: 'rgba(153,0,255, 0.8)'
          }]
        },
        options: {}
      };

      const ctx = document.createElement('canvas');
      document.getElementById('chart-area1').appendChild(ctx);
      new Chart(ctx, chartDataSet);
    };

    // グラフ表示
    loadChartsLine();


    //-------------------------
    // 地域別棒グラフ
    //-------------------------
    const region = {
      labels: ['東区', '南区', '西区', '中央区', '博多区', '早良区', '城南区'],
      year2015: [0.64, 0.0, 0.0, 0.0, 0.5, 4.63, 0.6],
      year2016: [6.18, 6.22, 8, 2, 4.83, 41.25, 15],
      year2017: [16.18, 23.67, 29.14, 8.6, 19.17, 89.25, 66.6],
      year2018: [13.22, 4.78, 10.43, 9.5, 8, 67, 17.2],
      year2019: [, , , , , , ],
      // timestamp: ["2018/04/16 22:18", "2018/04/16 23:18", "2018/04/17 00:18", "2018/04/17 01:18", "2018/04/17 02:18", "2018/04/17 09:18", "2018/04/17 10:18", "2018/04/17 11:18", "2018/04/17 12:18", "2018/04/17 13:18", "2018/04/17 14:18", "2018/04/17 15:18"]
    };

    // グラフ定義
    const loadChartsbar = function() {
      const chartDataSet = {
        type: 'bar',
        data: {
          labels: region.labels,
          datasets: [{
            label: 'year2015',
            data: year.year2015,
            backgroundColor: 'rgba(60, 160, 220, 0.3)',
            borderColor: 'rgba(60, 160, 220, 0.8)'
          }, {
            label: 'year2016',
            data: region.year2016,
            backgroundColor: 'rgba(255,100,150, 0.3)',
            borderColor: 'rgba(255,0,100, 0.8)'
          }, {
            label: 'year2017',
            data: region.year2017,
            backgroundColor: 'rgba(60, 190, 20, 0.3)',
            borderColor: 'rgba(60, 190, 20, 0.8)'
          }, {
            label: 'year2018',
            data: region.year2018,
            backgroundColor: 'rgba(153,102,255, 0.3)',
            borderColor: 'rgba(153,0,255, 0.8)'
          }]
        },
        options: {}
      };

      const ctx = document.createElement('canvas');
      document.getElementById('chart-area2').appendChild(ctx);
      new Chart(ctx, chartDataSet);
    };

    // グラフ表示
    loadChartsbar();
  </script>
</body>

</html>