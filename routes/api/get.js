var express = require('express');
var router = express.Router();

let getData = (db,uid)=>{
    return new Promise((rs,rj)=>{
        // 大於或等於現在的時間
        let sql ='SELECT * FROM `item` WHERE `date` >= CURDATE() AND `uid` = ?';
        let params = [uid];
        db.query(sql,params,function(err,result){
            if(err){
                console.log("[SELECT ERROR]",err);
                rj(404);
            }else{
                if(result.length == 0){
                    rj(404);
                }else{

                    rs(result);
                }
            }
        })
    });
}


router.get('/future',async function(req,res,next){
    let formData = req.query;
    console.log(formData);
    try{
        let result = await getData(req.db,req.session.user.id)
        res.send(result);
    }catch(error){
        console.log(error);
        res.send(400);
    }

});
module.exports =router;