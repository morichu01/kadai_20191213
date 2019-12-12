<?php
session_start();

include('functions.php');

//ログイン状態のチェック
#checkSessionId();

//life_flgの状態チェック
// $name = $_SESSION['name'];
// checkLeave($name);

//PHPで外部CGIを実行
echo 'ここではPHPからCGIを実行するテストを行っています<br>';
echo file_get_contents('http://localhost/cgi-bin/hello.sh');

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>極秘</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <style>
    /* .wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
    } */

    div {
      padding: 10px;
      font-size: 16px;
    }

    .thumb {
      width: 80%;
    }
  </style>
</head>

<body>

  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="#">極秘プロジェクト</a>
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
    <p>ここでは、Apache CGIの仕組みとOCR技術の実装テストを行っています</p>

    <input type="file" id="files" name="files[]" multiple />
    <output id="list"></output>

    <button id="script">CGI Run</button>
    <div id="pyresult"></div>

    <hr width="90%" color="gray">
    <p>ここではOCRで読み取ったファイルの中身を表示させています</p>

    <button id="result">Result Show</button>
    <output id="list1"></output>


  </div>

  <script>
    //ブラウザがFileAPIの対応状況を確認する
    if (window.File && window.FileReader && window.FileList && window.Blob) {
      // Great success! All the File APIs are supported.
    } else {
      alert('The File APIs are not fully supported in this browser.');
    }

    //選択したファイル名を取得
    function handleFileSelect(evt) {
      var files = evt.target.files; // FileList object
      // console.log(files);

      for (var i = 0, f; f = files[i]; i++) {

        // Only process image files.
        if (!f.type.match('image.*')) {
          continue;
        }

        var reader = new FileReader();

        // Closure to capture the file information.
        reader.onload = (function(theFile) {
          return function(e) {
            console.log(e);

            // Render thumbnail.
            var span = document.createElement('span');
            span.innerHTML = ['<img class="thumb" src="', e.target.result,
              '" title="', escape(theFile.name), '"/>'
            ].join('');
            document.getElementById('list').insertBefore(span, null);
          };
        })(f);

        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
      }
    }

    //別のファイル読み込み処理
    function displayResult() {
      // alert('ok');
      const xhr = new XMLHttpRequest();
      var data;

      xhr.open('GET', './result.txt', false); // GETでローカルファイルを開く
      xhr.onload = () => {
        data = xhr.responseText;
      };
      xhr.onerror = () => {
        console.log("error!");
      };

      xhr.send();

      cts1 = document.getElementById("list1");
      cts1.innerText = data;
    }

    //CGIを実行する処理
    function cgiRun() {
      function test() {
        return $.ajax({
          type: 'GET',
          url: '/cgi-bin/hello.sh'
          // url: '/cgi-bin/test-cgi'
        })
      }

      test().done(function(result) {
        console.log(result);
        $('#pyresult').text(result);

      }).fail(function(result) {
        console.log(result);
      });
    }


    document.getElementById('files').addEventListener('change', handleFileSelect, false);

    document.getElementById('result').addEventListener('click', displayResult, false);

    document.getElementById('script').addEventListener('click', cgiRun, false);
  </script>
</body>

</html>