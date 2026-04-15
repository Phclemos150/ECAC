document.addEventListener("DOMContentLoaded", () => {
  const modalErro = document.getElementById("modalErro");
  const fecharModalErro = document.getElementById("fecharModalErro");
  const abrirModalSenha = document.getElementById("abrirModalSenha");
  const modalSenha = document.getElementById("modalSenha");
  const fecharModalSenha = document.getElementById("fecharModalSenha");
  const formLogin = document.querySelector("form");
  const novaSenhaInput = document.getElementById("novaSenha");
  const emailRecuperacao = document.getElementById("emailRecuperacao");
  const cpfRecuperacao = document.getElementById("cpfRecuperacao");
  const novaSenhaRecuperar = document.getElementById("novaSenhaRecuperar");
  const confirmarNovaSenhaRecuperar = document.getElementById("confirmarNovaSenhaRecuperar");

  /* Redirecionar para o Cadastro */
  const irParaCadastro = () => {
    window.location.href = "../views/cadastro.php";
  };

  const configurarModal = (modal, botao, permitirRedirecionamento = false) => {
    if (!modal) return;
    let timeout;
    if (permitirRedirecionamento) {
      timeout = setTimeout(() => {
        if (modal.classList.contains("ativo")) irParaCadastro();
      }, 5000);
    }
    if (botao) {
      botao.addEventListener("click", (e) => {
        e.preventDefault();
        if (timeout) clearTimeout(timeout);
        modal.classList.remove("ativo");
        if (permitirRedirecionamento) irParaCadastro();
      });
    }
  };

  configurarModal(modalErro, fecharModalErro, window.redirecionarAoFechar);

  /* Abrir/Fechar Modal de Senha */
  if (abrirModalSenha) {
    abrirModalSenha.addEventListener("click", (e) => {
      e.preventDefault();
      modalSenha.classList.add("ativo");
    });
  }
  if (fecharModalSenha) {
    fecharModalSenha.addEventListener("click", () => modalSenha.classList.remove("ativo"));
  }

  const exibirAlertaJS = (titulo, mensagem) => {
    const modal = document.getElementById("modalAlertaJS");
    const labelTitulo = document.getElementById("alertaTituloJS");
    const labelMensagem = document.getElementById("alertaMensagemJS");
    if (modal && labelTitulo && labelMensagem) {
      labelTitulo.innerText = titulo;
      labelMensagem.innerText = mensagem;
      modal.classList.add("ativo");
    }
  };

  const btnFecharAlerta = document.getElementById("fecharAlertaJS");
  if (btnFecharAlerta) {
    btnFecharAlerta.addEventListener("click", () => {
      document.getElementById("modalAlertaJS").classList.remove("ativo");
    });
  }

  /* Máscara de CPF */
  if (cpfRecuperacao) {
    cpfRecuperacao.addEventListener("input", (e) => {
      let v = e.target.value.replace(/\D/g, ""); // Remove todos os dígitos
      if (v.length > 11) v = v.slice(0, 11); // Limita para 11 dígitos
      if (v.length > 9) {
        v = v.replace(/^(\d{3})(\d{3})(\d{3})(\d{1,2}).*/, "$1.$2.$3-$4");
      } else if (v.length > 6) {
        v = v.replace(/^(\d{3})(\d{3})(\d{1,3}).*/, "$1.$2.$3");
      } else if (v.length > 3) {
        v = v.replace(/^(\d{3})(\d{1,3}).*/, "$1.$2");
      }
      e.target.value = v;
    });
  }

  /* Mostrar/Ocultar Senha */
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

  /* Requesitos de Login e Recuperação de Senha */
  const requisitos = [
    { id: "req-min", texto: "Mínimo 6 caracteres", teste: (v) => v.length >= 6 },
    { id: "req-maiuscula", texto: "Uma letra maiúscula", teste: (v) => /[A-Z]/.test(v) },
    { id: "req-numero", texto: "Um número", teste: (v) => /[0-9]/.test(v) },
    { id: "req-especial", texto: "Um caractere especial", teste: (v) => /[^A-Za-z0-9]/.test(v) },
  ];

  const criarChecklist = (inputAlvo, prefixo) => {
    if (!inputAlvo) return;
    const checklist = document.createElement("ul");
    checklist.id = `checklist-${prefixo}`;
    checklist.style.cssText = "list-style:none; padding:8px 0; margin:0; display:none;";

    requisitos.forEach(req => {
      const li = document.createElement("li");
      li.id = `${prefixo}-${req.id}`;
      li.style.cssText = "font-size:11px; color:#999; margin-bottom:4px; display:flex; align-items:center; gap:5px;";
      li.innerHTML = `<i class="fa-solid fa-circle-xmark"></i> ${req.texto}`;
      checklist.appendChild(li);
    });

    const container = inputAlvo.closest('.password-wrapper') || inputAlvo.closest('.password-wrapper-cs');
    container.appendChild(checklist);

    inputAlvo.addEventListener("input", () => {
      const valor = inputAlvo.value;
      checklist.style.display = valor.length > 0 ? "block" : "none";
      requisitos.forEach(req => {
        const li = document.getElementById(`${prefixo}-${req.id}`);
        const ok = req.teste(valor);
        const icone = li.querySelector("i");
        li.style.color = ok ? "#22c55e" : "#ef4444";
        icone.className = ok ? "fa-solid fa-circle-check" : "fa-solid fa-circle-xmark";
      });
    });
  };

  criarChecklist(novaSenhaInput, "login");
  criarChecklist(novaSenhaRecuperar, "recuperar");

  // --- VALIDAÇÃO VISUAL (BORDAS) ---
  const marcarCampo = (campo, valido) => {
    if (!campo) return;
    if (campo.value.trim() === "") {
      campo.classList.remove("campo-valido", "campo-invalido");
      return;
    }
    campo.classList.toggle("campo-valido", valido);
    campo.classList.toggle("campo-invalido", !valido);
  };

  const regras = {
    email: (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()),
    senha: (v) => v.length >= 6 && /[A-Z]/.test(v) && /[0-9]/.test(v) && /[^A-Za-z0-9]/.test(v),
    emailRecuperacao: (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()),
    cpfRecuperacao: (v) => v.replace(/\D/g, "").length === 11,
    novaSenhaRecuperar: (v) => v.length >= 6 && /[A-Z]/.test(v) && /[0-9]/.test(v) && /[^A-Za-z0-9]/.test(v),
    confirmarNovaSenhaRecuperar: (v) => {
      const nova = document.getElementById("novaSenhaRecuperar").value;
      return v === nova && v !== "" && regras.novaSenhaRecuperar(v);
    }
  };

  const validarCampo = (campo) => {
    const nome = campo.id || campo.getAttribute("name");
    if (regras[nome]) {
      marcarCampo(campo, regras[nome](campo.value));
    }
  };

  document.querySelectorAll("input").forEach(input => {
    input.addEventListener("input", () => validarCampo(input));
    input.addEventListener("blur", () => validarCampo(input));
  });

  if (formLogin) {
    formLogin.addEventListener("submit", (e) => {
      const email = formLogin.querySelector('input[name="email"]');
      const senha = formLogin.querySelector('input[name="senha"]');
      const emailOk = regras.email(email.value);
      const senhaOk = regras.senha(senha.value);

      marcarCampo(email, emailOk);
      marcarCampo(senha, senhaOk);

      if (!emailOk || !senhaOk) e.preventDefault();
    });
  }

  /* Recuperação de senha */
  const btnAcao = document.getElementById("btnAcaoRecuperar");
  const etapa1 = document.getElementById("etapaIdentificacao");
  const etapa2 = document.getElementById("etapaNovaSenha");
  let usuarioValidado = false;

  if (btnAcao) {
    btnAcao.addEventListener("click", async () => {
      const email = emailRecuperacao.value;
      const cpf = cpfRecuperacao.value;

      if (!usuarioValidado) {
        if (!regras.emailRecuperacao(email) || !regras.cpfRecuperacao(cpf)) {
          exibirAlertaJS("Dados Inválidos", "Por favor, preencha o e-mail e o CPF corretamente.");
          marcarCampo(emailRecuperacao, regras.emailRecuperacao(email));
          marcarCampo(cpfRecuperacao, regras.cpfRecuperacao(cpf));
          return;
        }

        try {
          const response = await fetch("../controllers/AutentController.php?acao=validarRecuperacao", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `email=${encodeURIComponent(email)}&cpf=${encodeURIComponent(cpf)}`
          });

          const texto = await response.text();
          const dados = JSON.parse(texto);

          if (dados.sucesso) {
            usuarioValidado = true;
            etapa1.style.display = "none";
            etapa2.style.display = "block";
            btnAcao.innerText = "Salvar Nova Senha";
          } else {
            exibirAlertaJS("Erro de Validação", dados.mensagem || "Dados incorretos.");
          }
        } catch (e) {
          console.error("Erro na requisição:", e);
          exibirAlertaJS("Erro de Conexão", "Erro ao conectar com o servidor.");
        }
      } else {
        const senha = novaSenhaRecuperar.value;
        const confirma = confirmarNovaSenhaRecuperar.value;

        if (!regras.novaSenhaRecuperar(senha) || senha !== confirma) {
          exibirAlertaJS("Aviso", "Verifique se a senha atende aos requisitos e se a confirmação é idêntica.");
          return;
        }

        const response = await fetch("../controllers/AutentController.php?acao=atualizarSenha", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `email=${encodeURIComponent(email)}&novaSenha=${encodeURIComponent(senha)}`
        });

        const resultado = await response.json();
        if (resultado.sucesso) {
          exibirAlertaJS("Sucesso", "Senha alterada com sucesso!");
          setTimeout(() => { location.reload(); }, 2000);
        }
      }
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
    if (!novaJanela || novaJanela.closed) window.location.href = mailtoUrl;
  }, 300);
}

function abrirWhatsApp(e) {
  e.preventDefault();
  const numero = "5521992141882";
  const url = `https://api.whatsapp.com/send?phone=${numero}`;
  window.open(url, "_blank");
}