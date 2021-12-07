var express = require('express');
var router = express.Router();
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
router.get('/', function(req, res, next) {
  // 在app.js 把conneciton 塞到req.db => req.db = connection
  req.db.query('SELECT * FROM user WHERE id=1', function(err, rows, fields) {
    if (err) throw err;
    console.log('SELECT Result: ', rows[0].id);
    console.log('SELECT Result: ', rows[0].username);
    console.log('SELECT PWD: ', rows[0].password);
    let data = {
      title: '軟工課輔',
      username : rows[0].username
    }
    res.render('index', data);  
  });
  
});
// res: response
// render: 渲染一個畫面
module.exports = router;
