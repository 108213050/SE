var express = require('express');
var router = express.Router();

/* GET home page. */
router.get('/', function(req, res, next) {
  res.render('index', { title: 'SE' });
});
// res: response
// render: 渲染一個畫面
module.exports = router;
