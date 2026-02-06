const userPerfil = document.querySelector('.user-profile');
const arrowPerfil = document.querySelector('.arrow-icon');

/* Ativar DropDown */
function ativarDropdown() {
  document.getElementById("dropdownMenu").classList.toggle("ativo");
}

/* Função para redirecionar para a página de inscrição */
function irParaInscricao() {
    window.location.href = "./inscricao.php";
}

window.addEventListener = function(event) {
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

/* MODAL */
function abrirModalEvento(id) {
  const modal = document.getElementById(id);
  if (modal) {
    modal.style.display = "flex";
    document.body.style.overflow = "hidden";
  }
}

function fecharModalEvento(id) {
  const modal = document.getElementById(id);
  if (modal) {
    modal.style.display = "none";
    document.body.style.overflow = "auto";
  }
}