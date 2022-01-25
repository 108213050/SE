var express = require('express');
var router = express.Router();

let insertItem = (db,data)=>{
    return new Promise((rs,rj)=>{
        let sql = 'INSERT INTO `item`(`title`, `date`, `time`, `ps`,`uid`) VALUES (?,?,?,?,?)';
        let params = [data['title'],data['date'],data['time'],data['ps'],data['uid']];
        db.query(sql,params,function(err,result){
            if(err){
                console.log("[INSERT ERROR",err);
                rj('DB ERROR');
            }else{
                rs('SUCCESS');
            }
        });
    });
}
// 純粹連進來
router.post('/',async function(req,res,next){
    let formData = req.body;
    console.log(formData);
    try{
        await insertItem(req.db,req.session.user.id,formData);
        res.send('OK')
        
    }catch(error){
        console.log(error);
        res.send('Bad')
    }
});
// 匯成一個module
module.exports =router;