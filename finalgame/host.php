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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script>
        var s; //socket object for connection
        function OpenBtn(){
            // 開獎
            
            fetch("ControlAjax.php?act=OpenLottery")
            .then(function(resp){
                return resp.text();
            })
            .then(function(rr) {
                if (rr){
                    console.log(rr);
                }
                // 發送開獎通知
                s.send("Open Lottery");
                UpPacket();
                let StartBtn = document.getElementById("StartBtn");
                StartBtn.disabled = false;
                let EndBtn = document.getElementById("EndBtn");
                // document.getElementById("EndBtn").classList.remove('disabled');
                EndBtn.disabled = true;
            })
        }
        function log_msg(msg) {
				var p = document.getElementById("show");
				p.innerHTML = msg;
				// show.appendChild(p);
			}
        // 按下開始遊戲
        function clickBtn() {
            // socket.emit('I-Want-Start', "START!!");
            let numcheck = ["1","2","3","4","5"];
            let number = document.getElementById("num").value;
            console.log(number);
            document.getElementById("show").innerHTML="";
            if(numcheck.includes(number)) {
                // 存入num至資料庫
                store(number);
                console.log("store");
            }else{
                console.log("no");
                document.getElementById("show").innerHTML = "<p>請重新輸入1~5的數字</p>";
            }
        }
        // 更新錢包
        function UpPacket() {
            fetch("ControlAjax.php?act=UpdatePacket&role=0")
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
        // 儲存中獎數字
        function store(num){
            let mydat = new FormData();
            mydat.append("num",num);
            fetch("ControlAjax.php?act=addgamenum",{
                method: 'POST', // or 'GET', 'PUT'
                body: mydat
                //body: JSON.stringify(data)
            })
            //.then(function(resp){return resp.text();})		
            .then(function(resp){
                //console.log(resp);
                // 內容轉為json物件
                return resp.text();
		    })
            .then(function(rr) {
                if (rr){
                    // 將json物件轉為表格
                    console.log("ok");
                    // 發送 開始遊戲
                    s.send("start game");
                    let StartBtn = document.getElementById("StartBtn");
                    StartBtn.disabled = true;
                    let EndBtn = document.getElementById("EndBtn");
                    // document.getElementById("EndBtn").classList.remove('disabled');
                    EndBtn.disabled = false;
                    // console.log(EndBtn.disabled);
                    UpPacket();
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
                    let EndBtn = document.getElementById("EndBtn");
                    EndBtn.disabled = true;
                    UpPacket();
					s = new WebSocket(host); //建立socket元件
					//設定幾個主要事件
					s.onopen = function (e) { log_msg("connected..."); };
					s.onclose = function (e) { log_msg("connection closed."); };
					s.onerror = function (e) { log_msg("connection error."); };
					
					//當server送訊息來時
					s.onmessage = function (e) { 
                        if(e.data == "new store"){
                            log_msg("message: " + e.data);
                        }else if(e.data == "PacketUpdate"){
                            log_msg("message: " + e.data);
                            UpPacket();
                        }
                        log_msg("message: " + e.data);
					};
				} catch (ex) {
					log_msg("connection exception:" + ex);
				}
			}
    </script>
    <title>Host Page</title>
    <!-- <link rel='stylesheet' href='/stylesheets/style.css' /> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  </head>
  <body onload="doInit()">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="/finalgame/host.php">猜獎遊戲</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="/finalgame/host.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/finalgame/FortuneRank.php">財富排行榜</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container text-center">
    <?php echo "<h1>莊家: ".$_SESSION['username']."</h1>";?>
        <div class="row mt-2 p-3">
            <div class="col-4 text-center" name="detail"></div>
            <div id ="detail" class="col-8 " ></div>
        </div>
        <!-- <a id="gotoGame" href="/game" class="btn btn-success disabled">前往下注</a> -->
        <form>
            <div class="row m-5">
                <div class="col-5 m-auto border border-success border-5">
                    <div class="row mt-2 p-3">
                        <div class="col-4 text-center" name="num">遊戲數字</div>
                        <div class="col-8 "><input id="num" type="text" class="w-100" ></div>
                    </div> 
                    <div class="row m-4 ">
                        <!-- <button class="btn btn-dark" type="submit"></button> -->
                        <button id="StartBtn" type="button" onclick="clickBtn()" class="btn btn-lg btn-dark">Start game</button>
                        <button id="EndBtn" type="button" onclick="OpenBtn()" class="btn btn-lg btn-warning">開獎</button>
                    </div>
                </div>
            </div>
        </form>
        <div id="show"></div>
        
        <!-- <script src="https://cdn.socket.io/4.4.1/socket.io.min.js" integrity="sha384-fKnu0iswBIqkjxrhQCTZ7qlLHOFEgNkRmK2vaO/LbTZSXdJfAu6ewRBdwHPhBo/H" crossorigin="anonymous"></script> -->
    </div>
  </body>
</html>
