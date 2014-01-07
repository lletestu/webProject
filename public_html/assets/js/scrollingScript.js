var header = document.getElementById("header");

function onScroll(e) {
  window.scrollY > 0 ?	header.classList.add('shadow') :
                        header.classList.remove('shadow');
}

document.addEventListener('scroll', onScroll);
