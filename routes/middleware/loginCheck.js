let loginCheck = (req,res,next)=>{
    if(req.session.user == undefined){
        res.redirect('/login');
    }else{
        next();
    }
}

// function loginCheck(req,res,next){
//     if(req.session.user == undefined){
//         res.redirect('/login');
//     }else{
//         next();
//     }
// }

module.exports =loginCheck;
// 包成一個module