var express = require('express');
// const { redirect } = require('express/lib/response');
var router = express.Router();

// 跟資料庫作比對的函數，return Promise
let checkLogin = (db,formData)=>{
    return new Promise((rs,rj)=>{
        // sql語法
        let sql = "SELECT * FROM user WHERE username=? AND password=?";
        // 綁定參數
        let params = [formData['username'],formData['password']];
        // 實際下指令 db.query(sql語法,綁定參數,回傳的資料)
        db.query(sql,params,function(err,rows){
            // 跟資料庫連接的時候可能會發生錯誤
            if(err){
                console.log("[SELECT ERROR -]",err);
                rj('DB ERROR');
            }else{ //撈資料
                // username、password其中一個是錯的，沒撈到資料
                if(rows.length == 0){
                    rj('login ERROR');
                }else{//有撈到資料
                    // rs() :resolve，解決了，回傳這個資料
                    rs(rows[0]);
                }
            }

        })
    })
}
/* post users listing. */
router.post('/',async function(req, res, next) {
    let formData = req.body;
    console.log(formData);
    try{
        // 檢查傳過來的資料與資料庫做比對，比對完成惠回傳一份資料
        let userData = await checkLogin(req.db,formData);
        // 把資料存到這個session裡面
        req.session.user = {
            id:userData['id'],
            username: userData['username']
        }
        // 登入成功導向其他頁面: index
        res.redirect('/');
    }catch(err){
        console.log(err);
        // res.send('respond with a resource');
    }
  });
  
  module.exports = router;
  