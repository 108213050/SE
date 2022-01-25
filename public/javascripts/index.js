var myHeaders = new Headers();

function showData(data){
    let html = '';
    for(let i = 0;i<data.length;i++){
        let keys = Object.keys(data[i]);
        for(let j = 0 ;j<keys.length;j++){
            html+= keys[j] + ":" + data[i][keys[j]]+"</br>";
        }
    }
    document.getElementById('showData').innerHTML = html;
}
myHeaders.append("Cookie", "connect.sid=s%3ACnL5PZmlVZJJa4p38Lcs5_8ozf8eLPAd.0vw1LBPUuJP%2FVzHD%2Bj0kyKrGFPG0PXENbNa2GahHlag");

var requestOptions = {
  method: 'GET',
  headers: myHeaders,
  redirect: 'follow'
};

fetch("/api/get/future", requestOptions)
  .then(response => response.text())
  .then(result =>{
      console.log(result)
      showData(JSON.parse(result));

  }) 
  .catch(error => console.log('error', error));