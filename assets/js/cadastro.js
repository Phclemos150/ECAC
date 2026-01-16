document.addEventListener("DOMContentLoaded", () => {
  const modalErro = document.getElementById("modalErro");
  const fecharModalErro = document.getElementById("fecharModalErro");
  const modalSucesso = document.getElementById("modalSucesso");
  const btnSucessoOk = document.getElementById("btnSucessoOk");

  // Função para redirecionar para o login
  const irParaLogin = () => {
    window.location.href = "../views/login.php";
  };

  const configurarModal = (modal, botao, usarFlagErro = false) => {
    if (!modal) return;

    const podeRedirecionar = () =>
      !usarFlagErro || window.redirecionarAoFechar === true;

    const timeout = setTimeout(() => {
      if (modal.classList.contains("ativo") && podeRedirecionar()) {
        irParaLogin();
      }
    }, 5000);

    if (botao) {
      botao.addEventListener("click", (e) => {
        e.preventDefault();
        clearTimeout(timeout);
        modal.classList.remove("ativo");

        if (podeRedirecionar()) {
          irParaLogin();
        }
      });
    }
  };

  configurarModal(modalSucesso, btnSucessoOk);
  configurarModal(modalErro, fecharModalErro, true);

  // Mostrar/Ocultar Senha
  const toggleSenha = document.getElementById("toggleSenha");
  const senhaInput = document.getElementById("senha");

  if (toggleSenha && senhaInput) {
    toggleSenha.addEventListener("click", () => {
      const tipo = senhaInput.getAttribute("type") === "password" ? "text" : "password";
      senhaInput.setAttribute("type", tipo);

      toggleSenha.classList.toggle("fa-eye");
      toggleSenha.classList.toggle("fa-eye-slash");
    });
  }
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