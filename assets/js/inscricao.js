const userPerfil = document.querySelector('.user-profile');
const arrowPerfil = document.querySelector('.arrow-icon');

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

/* proteger caso user-profile não exista (usuário não logado) */
if (userPerfil) {
  userPerfil.addEventListener('mouseover', () =>{
    userPerfil.classList.add("user-profile-hover");
    if (arrowPerfil) arrowPerfil.classList.add("arrow-icon-hover");
  });

  userPerfil.addEventListener('mouseleave', () =>{
    userPerfil.classList.remove("user-profile-hover");
    if (arrowPerfil) arrowPerfil.classList.remove("arrow-icon-hover");
  });
}

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

/* Toggle para as normas (dropdown por seta) */
(function() {
  const normasSection = document.querySelector('.normas');
  const normasToggle = document.querySelector('.normas-toggle');
  const normasContent = document.getElementById('normasContent');

  if (!normasSection || !normasToggle || !normasContent) return;

  // garantir estado inicial fechado
  normasSection.classList.remove('open');
  normasContent.style.maxHeight = 0;
  normasContent.setAttribute('aria-hidden', 'true');
  normasToggle.setAttribute('aria-expanded', 'false');

  normasToggle.addEventListener('click', () => {
    const abrir = !normasSection.classList.contains('open');
    normasSection.classList.toggle('open', abrir);
    normasToggle.setAttribute('aria-expanded', abrir ? 'true' : 'false');
    normasContent.setAttribute('aria-hidden', abrir ? 'false' : 'true');

    if (abrir) {
      // animação suave: ajustar maxHeight conforme conteúdo
      normasContent.style.maxHeight = normasContent.scrollHeight + 'px';
    } else {
      normasContent.style.maxHeight = 0;
    }
  });

  // opcional: ajustar maxHeight caso o conteúdo mude depois (ex.: imagens carreguem)
  window.addEventListener('resize', () => {
    if (normasSection.classList.contains('open')) {
      normasContent.style.maxHeight = normasContent.scrollHeight + 'px';
    }
  });
})();