var b = document.getElementById("background");
b.style.minHeight = window.innerHeight-75 + 'px';

function onResize(e) {
    b.style.minHeight = window.innerHeight-75 + 'px';
}


window.addEventListener('resize',onResize);