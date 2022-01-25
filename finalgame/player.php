<?php
require("dbconfig.php");
if ( $_SESSION["username"] == '') {
	header("Location: 0.loginUI.php");
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
    integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
    crossorigin="anonymous"></script>
  <script>
    let enoughmoney;
    async function test() {
      // socket.emit('I-Want-Start', "START!!");
      // 猜的數字
      let number = document.getElementById("guessnum").value;
      // 下注金額
      let money = document.getElementById("money").value;
      console.log(number, money);
      
      // 錢有夠
      // let id = getPlayerId();
      try {
        // 檢查此玩家輸入的數字是否在1~5之間
        let numcheck = ["1","2","3","4","5"];
        
        // 檢查此玩家的存的錢
        let res = await checkmoney(money);
        console.log("res "+res);
        console.log("res type "+typeof(res));
        if(res.trim() == "OK" && numcheck.includes(number)) {
          store(number,money);
          console.log("store");
        }else if(res.trim() == "NO" && numcheck.includes(number) == true){
          console.log("not enough");
          document.getElementById("show").innerHTML = "<p>請重新輸入金額，你不夠錢喔!!!";
        }else if(numcheck.includes(number) == false){
          console.log("數字錯誤");
          document.getElementById("show").innerHTML = "<p>請重新輸入1~5的數字";
        }
      } catch(err) {
        console.log("error:"+err);
      }
    }
    var s; //socket object for connection
    function log_msg(msg) {
      var p = document.getElementById("show");
      p.innerHTML = msg;
    }
    // 檢查金額夠不夠
    function checkmoney(money) {
      return new Promise((rs, rj) => {
        fetch("ControlAjax.php?act=checkmoney&money="+money)
        .then(function(resp){
          //console.log(resp);
          rs(resp.text());
        })
        .then(function(rr) {
          if (rr){
            // enoughmoney = rr;
            rj(rr)
            console.log("rr:"+rr);
          }
        })

      });
    }
    // 更新玩家擁有的錢錢
    function UpPacket() {
      fetch("ControlAjax.php?act=UpdatePacket&role=1")
      .then(function(resp){
        return resp.text();
      })
      .then(function(rr) {
        if (rr){
          console.log(rr);
        }
        let tableStr = "<h1>金額"+rr+"</h1>";
        document.getElementById("detail").innerHTML=tableStr;
      })
    }
    // load this 
    function loadList() {
      fetch("ControlAjax.php?act=loadList")
      .then(function(resp){
        // console.log(' =>>>>>>', resp.json());
        return resp.json();
      })
      .then(function(json) {
        if (json){
          console.log(json);
          console.log(typeof(json));
          // let tableStr = "<table  border='1'><tr ><th>GameID</th><th>userID</th><th>num</th><th>stake</th></tr><tr>";
          // for (const [key, value] of Object.entries(json)) {
          //   console.log(key, value);
          //   tableStr += "<td>" + value+"</td>";
          // }
          
          // tableStr += "</tr></table>";
          let tableStr = "<div class=\"col border border-2 bg-warning fw-bolder\">GameID</div>";
          tableStr += "<div class=\"col border border-2 bg-warning fw-bolder\">userID</div>";
          tableStr += "<div class=\"col border border-2 bg-warning fw-bolder\">num</div>";
          tableStr += "<div class=\"col border border-2 bg-warning fw-bolder\">stake</div>";
          
          for (const [key, value] of Object.entries(json)) {
            console.log(key, value);
            tableStr += "<div class=\"col border border-1\">" + value+"</div>";
          }
          document.getElementById("staketable").innerHTML=tableStr;
        }
      })
    }
    // store num and stake money
    function store(number,money) {
      let mydat = new FormData();
      mydat.append("number",number);
      mydat.append("money",money);
      fetch("ControlAjax.php?act=addstake", {
        method: 'POST', // or 'GET', 'PUT'
        body: mydat
        //body: JSON.stringify(data)
      })
        //.then(function(resp){return resp.text();})		
        .then(function (resp) {
          //console.log(resp);
          // 內容轉為json物件
          return resp.text();
        })
        .then(function (rr) {
          if (rr) {
            // 將json物件轉為表格
            console.log(rr);
            // 更新表單
            // loadList();
            s.send("new store")
            let StakeBtn = document.getElementById("StakeBtn");
            StakeBtn.disabled = true;
            loadList();
          }
        })
    }
    // 開獎結果
    function result(){
      console.log("this is result");
      // 取得玩家結果
      document.getElementById("staketable").innerHTML="";
      fetch("ControlAjax.php?act=Playresult")
      .then(function(resp){
        return resp.text();
      })
      .then(function(rr) {
        console.log("rr===>>>",rr);
       if (rr){
          if(rr == 1){
            // 大於0
            let tableStr = "<h1>開獎結果: 恭喜你贏了</h1>";
            document.getElementById("staketable").innerHTML=tableStr;
          }else if(rr == 0){
            // 小於0
            // 輸了
            let tableStr = "<h1>開獎結果: 很不幸的你輸了!</h1>";
            document.getElementById("staketable").innerHTML=tableStr;
          }
       }
       
      })

    }

    // websocket
    function doInit() {
      try {
        var host = "ws://localhost:4545/"; //設定socker server的ip:port
        /*if(window.location.hostname) {
          host = "ws://" + window.location.hostname + ":4545/";
        }*/
        let StakeBtn = document.getElementById("StakeBtn");
        StakeBtn.disabled = true;
        UpPacket();
        s = new WebSocket(host); //建立socket元件
        //設定幾個主要事件
        s.onopen = function (e) { log_msg("connected..."); };
        s.onclose = function (e) { log_msg("connection closed."); };
        s.onerror = function (e) { log_msg("connection error."); };

        //當server送訊息來時
        s.onmessage = function (e) {
          console.log(e.data);
          if (e.data == "start game") {
            console.log("Start");
            document.getElementById("staketable").innerHTML="";
            let StakeBtn = document.getElementById("StakeBtn");
            StakeBtn.disabled = false;
            log_msg("message: " + e.data);
          }else if(e.data == "Open Lottery"){
            // 開獎
              log_msg("message: " + e.data);
            // 更新玩家錢包
            // 通知玩家結果
              UpPacket();
            // 開獎結果:
              result();
          }
          log_msg("message: " + e.data);
          // 當收到開始遊戲的訊息
        };
      } catch (ex) {
        log_msg("connection exception:" + ex);
      }
    }
  </script>
  <title>Host Page</title>
  <!-- <link rel='stylesheet' href='/stylesheets/style.css' /> -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body onload="doInit()">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="/finalgame/player.php">猜獎遊戲</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="/finalgame/player.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/finalgame/FortuneRank.php">財富排行榜</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

  <div class="container text-center">
    <?php echo "<h1>玩家: ".$_SESSION['username']."</h1>";?>
    <p>Game </p>
    <!-- <a id="gotoGame" href="/game" class="btn btn-success disabled">前往下注</a> -->
    <div class="row mt-2 p-3">
            <div class="col-4 text-center" name="detail"></div>
            <div id ="detail" class="col-8 " ></div>
    </div>
    <form>
      <div class="row m-5">
        <div class="col-5 m-auto border border-success border-5">
          <div class="row mt-2 p-3">
            <div class="col-4 text-center" name="guessnum">猜數字</div>
            <div class="col-8 "><input id="guessnum" type="text" class="w-100"></div>
          </div>
          <div class="row mt-2 p-3">
            <div class="col-4 text-center" name="stake">下注金額</div>
            <div class="col-8 "><input id="money" type="text" class="w-100"></div>
          </div>
          <div class="row m-2 ">
            <!-- <button class="btn btn-dark" type="submit"></button> -->

            <button id="StakeBtn" type="button" onclick="test()" class="btn btn-lg btn-warning ">下注</button>
          </div>
        </div>
      </div>
    </form>
    <form >
      <div class="row m-5">
      <div id="show" class="row mt-2 p-3"></div>
      <div id="staketable" class="row row-cols-4 text-center "></div>
      
    </div>
    </form>
    </div>
    <!-- <script src="https://cdn.socket.io/4.4.1/socket.io.min.js" integrity="sha384-fKnu0iswBIqkjxrhQCTZ7qlLHOFEgNkRmK2vaO/LbTZSXdJfAu6ewRBdwHPhBo/H" crossorigin="anonymous"></script> -->
  </div>
</body>

</html>