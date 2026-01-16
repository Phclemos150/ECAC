const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.dot');
const nextBtn = document.querySelector('.next');
const prevBtn = document.querySelector('.prev');
const userPerfil = document.querySelector('.user-profile');
const arrowPerfil = document.querySelector('.arrow-icon');

let index = 0;
let interval;

/* FUNÇÃO PRINCIPAL */
function showSlide(i) {
  slides.forEach(slide => slide.classList.remove('active'));
  dots.forEach(dot => dot.classList.remove('active'));

  slides[i].classList.add('active');
  dots[i].classList.add('active');

  index = i;
}

/* PRÓXIMO */
function nextSlide() {
  let i = index + 1;
  if (i >= slides.length) i = 0;
  showSlide(i);
}

/* ANTERIOR */
function prevSlide() {
  let i = index - 1;
  if (i < 0) i = slides.length - 1;
  showSlide(i);
}

/* AUTO PLAY */
function startAutoPlay() {
  interval = setInterval(nextSlide, 5000);
}

function stopAutoPlay() {
  clearInterval(interval);
}

/* EVENTOS */
nextBtn.addEventListener('click', () => {
  nextSlide();
  stopAutoPlay();
  startAutoPlay();
});

prevBtn.addEventListener('click', () => {
  prevSlide();
  stopAutoPlay();
  startAutoPlay();
});

dots.forEach((dot, i) => {
  dot.addEventListener('click', () => {
    showSlide(i);
    stopAutoPlay();
    startAutoPlay();
  });
});

/* INICIAR */
showSlide(0);
startAutoPlay();

/* Ativar DropDown */
function ativarDropdown() {
  document.getElementById("dropdownMenu").classList.toggle("ativo");
}

window.onclick = function(event) {
  if (!event.target.closest('.user-profile')) {
    const dropdowns = document.getElementsByClassName("dropdown-content");
    for (let i = 0; i < dropdowns.length; i++) {
      let openDropdown = dropdowns[i];
      if (openDropdown.classList.contains("ativo")) {
        openDropdown.classList.remove("ativo");
      }
    }
  }
}

userPerfil.addEventListener('mouseover', () =>{
  userPerfil.classList.add("user-profile-hover");
  arrowPerfil.classList.add("arrow-icon-hover");
});

userPerfil.addEventListener('mouseleave', () =>{
  userPerfil.classList.remove("user-profile-hover");
  arrowPerfil.classList.remove("arrow-icon-hover");
});

/* CONTATO */

function abrirEmail(e) {
  e.preventDefault();

 
  const gmailUrl = "https://mail.google.com/mail/?view=cm&fs=1&to=missaodesenvolver@gmail.com";

  const mailtoUrl = "mailto:missaodesenvolver@gmail.com";

  const novaJanela = window.open(gmailUrl, "_blank");


  setTimeout(() => {
    if (!novaJanela || novaJanela.closed) {
      window.location.href = mailtoUrl;
    }
  }, 300);
}

function abrirWhatsApp(e) {
  e.preventDefault();

  const numero = "5521992141882"; // DDI + DDD + número
  const mensagem = ""; 

  const texto = encodeURIComponent(mensagem);

  // Página intermediária oficial do WhatsApp
  const url = `https://api.whatsapp.com/send?phone=${numero}&text=${texto}`;

  window.open(url, "_blank");
}