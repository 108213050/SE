var express = require('express');
var router = express.Router();
const mysql = require('mysql');

let db_config = {
  database : 'todo',
  host     : 'localhost',
  user     : 'root',
  password : ''
}
var connection = mysql.createConnection(db_config);
// var connection = mysql.createConnection({
//   database : 'todo',
//   host     : 'localhost',
//   user     : 'root',
//   password : ''
// });
connection.connect();
// connection.end();

/* GET home page. */
router.get('/', function(req, res, next) {
  connection.query('SELECT * FROM user WHERE id=1', function(err, rows, fields) {
    if (err) throw err;
    console.log('SELECT Result: ', rows[0].id);
    console.log('SELECT Result: ', rows[0].username);
    console.log('SELECT PWD: ', rows[0].password);
    let data = {
      title: '',
      username : rows[0].username
    }
    res.render('index', data);  
  });
  
});
// res: response
// render: 渲染一個畫面
module.exports = router;
