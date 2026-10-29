// Carrossel

let atual = 0;
const slides = document.querySelector(".slides");
const imagens = document.querySelectorAll(".slides img");
const dots = document.querySelectorAll(".bullets span");

function mostrarSlide() {
    slides.style.transform = `translateX(-${atual * 100 / imagens.length}%)`;

    dots.forEach(dot => dot.style.backgroundColor = "white");
    dots[atual].style.backgroundColor = "#298dcc";
}

document.querySelector(".next").onclick = () => {
    atual = (atual + 1) % imagens.length;
    mostrarSlide();
};

document.querySelector(".last").onclick = () => {
    atual = (atual - 1 + imagens.length) % imagens.length;
    mostrarSlide();
};

setInterval(() => {
    atual = (atual + 1) % imagens.length;
    mostrarSlide();
}, 5000);