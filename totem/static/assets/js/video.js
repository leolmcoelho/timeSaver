function start() {
    let name = localStorage.getItem('name');
    console.log(name);
    console.log('s');
    fetch('api/start-bot?name=' + name).then(T => T.json());
}




setInterval(async function() {
    let url = await fetch('api/status-bot').then(T => T.json()); 
    if (url.code == 400) {
        window.location.href = '/amil_token';
    }
    else if (url.code != 100){
        localStorage.setItem('status-bot', url.code)
        window.location.href = '/result';

    }

    localStorage.setItem('status-bot', url.code)

    //window.location.href = '/video';
    //console.log('teste');
}, 1000*2);

start();