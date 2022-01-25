var express = require('express');
var router = express.Router();
// 定義router
// 純粹連進來
router.get('/',function(req,res,next){
    // 清除session
    req.session.user =undefined;
    // 導回login
    res.redirect('/login');
});
// 匯成一個module
module.exports =router;