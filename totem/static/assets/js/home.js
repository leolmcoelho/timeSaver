let btn = document.getElementById('btn');

btn.onclick = async function() {
    let name = document.querySelector('#name').value;
    localStorage.clear();
    localStorage.setItem('name', name);
    /*console.log('vai fazer a request');
    fetch('api/start-bot?name=' + name).then(T => T.json()); 
    console.log('vai redirecionar');*/
    window.location.href = '/video';
    
}

