<?php
	//Session 使用範例
	session_start(); //啟用session 功能, 必須在php程式還沒輸出任何訊息之前啟用
	$_SESSION["username"] = ""; //宣告session 變數並指定值
?>
<hr>
<!doctype html>
<html lang="en">
	<head>
	  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<title>Document</title>
  </head>
  <body>
      <div class="container">
        <div class="row">
          <div class="col-12 text-center">
            <h1>登入</h1>
          </div>
        </div>
        <form action="0.login.php" method="post">
            <div class="row m-5">
                <div class="col-5 m-auto border border-success border-5">
                  <div class="row mt-2 p-3">
                    <div class="col-4 text-center" name="username">帳號</div>
                    <div class="col-8 "><input name="username" type="text" class="w-100" ></div>
                  </div> 
                  <div class="row">
                    <div class="col-4 text-center">密碼</div>
                    <div class="col-8"><input name="password" type="password" class="w-100" ></div>
                  </div>
                  <div class="row m-2 ">
                    <button class="btn btn-dark" type="submit">登入</button>
                  </div>
                  <div class="row m-2">
				    <a class="btn btn-dark" href="signupUI.php" >註冊</a>
				  <!-- <a href="/login" class="btn btn-dark"> -->
				</div>
                </div>
            </div>
        </form>
      </div>
  </body>
</html>
