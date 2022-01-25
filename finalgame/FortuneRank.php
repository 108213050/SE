<?php
require("dbconfig.php");
if ( $_SESSION["username"] == '') {
	header("Location: 0.loginUI.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <title>財富排行榜</title>
  <script>
    function RankList(){
      fetch("ControlAjax.php?act=RankList")
      .then(function(resp){
        // console.log(' =>>>>>>', resp.json());
        return resp.json();
      })
      .then(function(json) {
        if (json){
          console.log(json);
          console.log(typeof(json));
         
          let tableStr = "<div class=\"col border border-2 bg-warning fw-bolder\">Username</div><div class=\"col border border-2 bg-warning fw-bolder\">Money</div>";
          for ( let key in json) {
            tableStr += "<div class=\"col border border-1\">" + json[key]['username']+"</div>";
            // tableStr += "</td><td>" + json[key];
            tableStr += "<div class=\"col border border-1\">" + json[key]['money']+"</div>";
          }
          
            

            // for (const [key, value] of Object.entries(json)) {
          //   console.log(key, value);
          //   tableStr += "<td>" + value+"</td>";
          // }
          
          // tableStr += "</div>";
          document.getElementById("Ranklist").innerHTML=tableStr;
        }
      })
    }
  </script>
</head>
<body onload="RankList()">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <?php
          if($_SESSION["role"] == 0){
              echo "<a class=\"navbar-brand\" href=\"/finalgame/host.php\">猜獎遊戲</a>
              ";
          }elseif($_SESSION["role"] == 1){
              echo "<a class=\"navbar-brand\" href=\"/finalgame/player.php\">猜獎遊戲</a>";
          }
        ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <?php
              // 連結
              // 莊家
              if($_SESSION["role"] == 0){
                echo "<a class=\"nav-link active\" aria-current=\"page\" href=\"/finalgame/host.php\">Home</a>";
              }elseif($_SESSION["role"] == 1){ 
                // 玩家
                echo "<a class=\"nav-link active\" aria-current=\"page\" href=\"/finalgame/player.php\">Home</a>";
              }
              ?>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/finalgame/FortuneRank.php">財富排行榜</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  <div class="container text-center">
    <div class="row mt-2 p-3 text-center">
        <div class="col fs-2 fw-bolder">玩家財富排行榜</div>
    </div>
    <!-- <a id="gotoGame" href="/game" class="btn btn-success disabled">前往下注</a> -->
    <form>
      <div id="Ranklist" class="row row-cols-2 text-center "></div>
    </form>
    
    <!-- <script src="https://cdn.socket.io/4.4.1/socket.io.min.js" integrity="sha384-fKnu0iswBIqkjxrhQCTZ7qlLHOFEgNkRmK2vaO/LbTZSXdJfAu6ewRBdwHPhBo/H" crossorigin="anonymous"></script> -->
</div>
</body>
</html>