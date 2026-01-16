document.addEventListener("DOMContentLoaded", () => {
  const modalErro = document.getElementById("modalErro");
  const fecharModalErro = document.getElementById("fecharModalErro");
  const abrirModalSenha = document.getElementById("abrirModalSenha");
  const modalSenha = document.getElementById("modalSenha");
  const fecharModalSenha = document.getElementById("fecharModalSenha");

  const irParaCadastro = () => {
    window.location.href = "../views/cadastro.php";
  };

  const configurarModal = (modal, botao, permitirRedirecionamento = false) => {
    if (!modal) return;

    let timeout;
    if (permitirRedirecionamento) {
      timeout = setTimeout(() => {
        if (modal.classList.contains("ativo")) {
          irParaCadastro();
        }
      }, 5000);
    }

    if (botao) {
      botao.addEventListener("click", (e) => {
        e.preventDefault();
        
        if (timeout) clearTimeout(timeout);

        modal.classList.remove("ativo");

        if (permitirRedirecionamento) {
          irParaCadastro();
        }
      });
    }
  };

  configurarModal(modalErro, fecharModalErro, window.redirecionarAoFechar);
  
  // Mostrar/ocultar senha
  function toggleSenha(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (!input || !icon) return;

    icon.addEventListener("click", () => {
      if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
      } else {
        input.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
      }
    });
  }

  toggleSenha("senha", "toggleSenha");
  toggleSenha("novaSenha", "toggleNovaSenha");
  toggleSenha("confirmarNovaSenha", "toggleConfirmarSenha");
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