var express = require('express');
var router = express.Router();
const loginCheck = require('./middleware/loginCheck')
// const mysql = require('mysql');

// let db_config = {
//   database : 'todo',
//   host     : 'localhost',
//   user     : 'root',
//   password : ''
// }
// var connection = mysql.createConnection(db_config);
// 
// var connection = mysql.createConnection({
//   database : 'todo',
//   host     : 'localhost',
//   user     : 'root',
//   password : ''
// });
// connection.connect();要用這個
// connection.end();直接報廢
// var connection = mysql.creatPool (db_config);
/* GET home page. */
router.get('/',loginCheck ,function(req, res, next) {
    console.log();
    
    res.render('index', {title: 'Express',username: req.session.user.username});  
  });
  


router.get('/signUp', function(req, res, next) {
    res.render('signUp');  
});
router.get('/login', function(req, res, next) {
    console.log(req.session);
    res.render('login');  
});
router.get('/add', function(req, res, next) {
    console.log(req.session);
    res.render('add');  
});

// res: response
// render: 渲染一個畫面
module.exports = router;
