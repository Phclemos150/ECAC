document.addEventListener("DOMContentLoaded", () => {
  const modalErro       = document.getElementById("modalErro");
  const fecharModalErro = document.getElementById("fecharModalErro");
  const modalSucesso    = document.getElementById("modalSucesso");
  const btnSucessoOk    = document.getElementById("btnSucessoOk");

  // ── Redirecionar para o login ─────────────────────────────────────────────
  const irParaLogin = () => {
    window.location.href = "../views/login.php";
  };

  const configurarModal = (modal, botao, usarFlagErro = false) => {
    if (!modal) return;
    const podeRedirecionar = () =>
      !usarFlagErro || window.redirecionarAoFechar === true;

    const timeout = setTimeout(() => {
      if (modal.classList.contains("ativo") && podeRedirecionar()) irParaLogin();
    }, 5000);

    if (botao) {
      botao.addEventListener("click", (e) => {
        e.preventDefault();
        clearTimeout(timeout);
        modal.classList.remove("ativo");
        if (podeRedirecionar()) irParaLogin();
      });
    }
  };

  configurarModal(modalSucesso, btnSucessoOk);
  configurarModal(modalErro, fecharModalErro, true);

  // ── Mostrar / Ocultar Senha ───────────────────────────────────────────────
  const toggleSenha = document.getElementById("toggleSenha");
  const senhaInput  = document.getElementById("senha");

  if (toggleSenha && senhaInput) {
    toggleSenha.addEventListener("click", () => {
      const tipo = senhaInput.getAttribute("type") === "password" ? "text" : "password";
      senhaInput.setAttribute("type", tipo);
      toggleSenha.classList.toggle("fa-eye");
      toggleSenha.classList.toggle("fa-eye-slash");
    });
  }

  // ── Limite de 100 caracteres na senha ────────────────────────────────────
  if (senhaInput) {
    senhaInput.setAttribute("maxlength", "100");
  }

  // ═══════════════════════════════════════════════════════════════════════════
  // CHECKLIST VISUAL DA SENHA
  // ═══════════════════════════════════════════════════════════════════════════

  const requisitos = [
    { id: "req-min",       texto: "Mínimo 6 caracteres",             teste: (v) => v.length >= 6            },
    { id: "req-maiuscula", texto: "Uma letra maiúscula",              teste: (v) => /[A-Z]/.test(v)          },
    { id: "req-minuscula", texto: "Uma letra minúscula",              teste: (v) => /[a-z]/.test(v)          },
    { id: "req-numero",    texto: "Um número",                        teste: (v) => /[0-9]/.test(v)          },
    { id: "req-especial",  texto: "Um caractere especial (!@#$%...)", teste: (v) => /[^A-Za-z0-9]/.test(v)  },
  ];

  const senhaGrupo = document.querySelector(".senha-grupo");

  if (senhaGrupo) {
    const checklist = document.createElement("ul");
    checklist.id = "senha-checklist";
    checklist.style.cssText = `
      list-style: none;
      padding: 8px 0 0 0;
      margin: 0;
      display: none;
    `;

    requisitos.forEach((req) => {
      const li = document.createElement("li");
      li.id = req.id;
      li.style.cssText = `
        font-size: 12px;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
        color: #999;
        transition: color 0.2s ease;
      `;
      li.innerHTML = `<i class="fa-solid fa-circle-xmark" style="font-size:13px;"></i> ${req.texto}`;
      checklist.appendChild(li);
    });

    senhaGrupo.appendChild(checklist);
  }

  const atualizarChecklist = (valor) => {
    const checklist = document.getElementById("senha-checklist");
    if (!checklist) return;

    checklist.style.display = valor.length > 0 ? "block" : "none";

    requisitos.forEach((req) => {
      const li = document.getElementById(req.id);
      if (!li) return;
      const ok    = req.teste(valor);
      const icone = li.querySelector("i");

      if (ok) {
        li.style.color    = "#22c55e";
        icone.className   = "fa-solid fa-circle-check";
        icone.style.color = "#22c55e";
      } else {
        li.style.color    = "#ef4444";
        icone.className   = "fa-solid fa-circle-xmark";
        icone.style.color = "#ef4444";
      }
    });
  };

  if (senhaInput) {
    senhaInput.addEventListener("input", () => atualizarChecklist(senhaInput.value));
    senhaInput.addEventListener("focus", () => {
      if (senhaInput.value.length > 0) atualizarChecklist(senhaInput.value);
    });
    senhaInput.addEventListener("blur", () => {
      const checklist = document.getElementById("senha-checklist");
      if (checklist) checklist.style.display = "none";
    });
  }

  // ═══════════════════════════════════════════════════════════════════════════
  // MÁSCARAS
  // ═══════════════════════════════════════════════════════════════════════════

  const aplicarMascara = (input, fn, maxDigits) => {
    input.addEventListener("input", () => {
      const pos        = input.selectionStart;
      const valorAntes = input.value;
      const digitos    = valorAntes.replace(/\D/g, "").slice(0, maxDigits);
      const novoValor  = fn(digitos);
      input.value      = novoValor;
      const diff        = novoValor.length - valorAntes.length;
      const novaPosicao = Math.max(0, pos + diff);
      input.setSelectionRange(novaPosicao, novaPosicao);
    });

    input.addEventListener("keydown", (e) => {
      const permitidas = [
        "Backspace","Delete","ArrowLeft","ArrowRight",
        "ArrowUp","ArrowDown","Tab","Home","End"
      ];
      if (!permitidas.includes(e.key) && !/^\d$/.test(e.key)) e.preventDefault();
    });
  };

  const formatarCPF = (d) => {
    if (d.length <= 3) return d;
    if (d.length <= 6) return `${d.slice(0,3)}.${d.slice(3)}`;
    if (d.length <= 9) return `${d.slice(0,3)}.${d.slice(3,6)}.${d.slice(6)}`;
    return `${d.slice(0,3)}.${d.slice(3,6)}.${d.slice(6,9)}-${d.slice(9,11)}`;
  };

  const formatarTelefone = (d) => {
    if (d.length <= 2)  return d.length ? `(${d}` : d;
    if (d.length <= 6)  return `(${d.slice(0,2)}) ${d.slice(2)}`;
    if (d.length <= 10) return `(${d.slice(0,2)}) ${d.slice(2,6)}-${d.slice(6)}`;
    return `(${d.slice(0,2)}) ${d.slice(2,7)}-${d.slice(7,11)}`;
  };

  const formatarData = (d) => {
    if (d.length <= 2) return d;
    if (d.length <= 4) return `${d.slice(0,2)}/${d.slice(2)}`;
    return `${d.slice(0,2)}/${d.slice(2,4)}/${d.slice(4,8)}`;
  };

  const campoCPF      = document.querySelector('input[name="documento"]');
  const campoTelefone = document.querySelector('input[name="telefone"]');
  const campoData     = document.querySelector('input[name="data_nascimento"]');

  if (campoCPF)      aplicarMascara(campoCPF,      formatarCPF,      11);
  if (campoTelefone) aplicarMascara(campoTelefone, formatarTelefone, 11);
  if (campoData)     aplicarMascara(campoData,     formatarData,      8);

  // ═══════════════════════════════════════════════════════════════════════════
  // VALIDAÇÃO VISUAL DOS CAMPOS
  // ═══════════════════════════════════════════════════════════════════════════

  const marcarCampo = (campo, valido) => {
    campo.classList.remove("campo-valido", "campo-invalido");
    campo.classList.add(valido ? "campo-valido" : "campo-invalido");
  };

  const senhaValida = (v) =>
    v.length >= 6         &&
    v.length <= 100       &&
    /[A-Z]/.test(v)       &&
    /[a-z]/.test(v)       &&
    /[0-9]/.test(v)       &&
    /[^A-Za-z0-9]/.test(v);

  const regras = {
    nome_usuario:    (v) => v.trim().length >= 3,
    email:           (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()),
    senha:           senhaValida,
    documento:       (v) => v.replace(/\D/g, "").length === 11,
    data_nascimento: (v) => {
      if (!/^\d{2}\/\d{2}\/\d{4}$/.test(v)) return false;
      const [dia, mes, ano] = v.split("/").map(Number);
      const nasc = new Date(ano, mes - 1, dia);
      const hoje = new Date();
      if (
        nasc.getFullYear() !== ano ||
        nasc.getMonth()    !== mes - 1 ||
        nasc.getDate()     !== dia
      ) return false;
      const idade = hoje.getFullYear() - nasc.getFullYear();
      return nasc < hoje && idade >= 1 && idade <= 120;
    },
    telefone:       (v) => { const d = v.replace(/\D/g, ""); return d.length >= 10 && d.length <= 11; },
    instagram:      (v) => v.trim() === "" || v.trim() === "@" || v.trim().length >= 2,
    grau_academico: (v) => v !== "",
    nome_curso:     (v) => v.trim().length >= 2,
    cidade:         (v) => v.trim().length >= 2,
    estado:         (v) => /^[A-Za-z]{2}$/.test(v.trim()),
    pais:           (v) => v.trim().length >= 2,
  };

  const validarCampo = (campo) => {
    const nome = campo.getAttribute("name");
    if (!nome || !(nome in regras)) return true;
    const valido = regras[nome](campo.value);
    marcarCampo(campo, valido);
    return valido;
  };

  const form = document.querySelector("form");

  if (form) {
    form.querySelectorAll("input, select, textarea").forEach((campo) => {
      const nome = campo.getAttribute("name");
      if (!nome || !(nome in regras)) return;
      campo.addEventListener("input",  () => validarCampo(campo));
      campo.addEventListener("change", () => validarCampo(campo));
      campo.addEventListener("blur",   () => validarCampo(campo));
    });

    form.addEventListener("submit", (e) => {
      let tudoValido = true;

      form.querySelectorAll("input, select, textarea").forEach((campo) => {
        const nome = campo.getAttribute("name");
        if (!nome || !(nome in regras)) return;
        if (!validarCampo(campo)) tudoValido = false;
      });

      if (!tudoValido) {
        e.preventDefault();
        const primeiroInvalido = form.querySelector(".campo-invalido");
        if (primeiroInvalido) {
          primeiroInvalido.scrollIntoView({ behavior: "smooth", block: "center" });
          primeiroInvalido.focus();
        }
      }
    });
  }
});

// ── Contato ───────────────────────────────────────────────────────────────────

function abrirEmail(e) {
  e.preventDefault();
  const gmailUrl  = "https://mail.google.com/mail/?view=cm&fs=1&to=missaodesenvolver@gmail.com";
  const mailtoUrl = "mailto:missaodesenvolver@gmail.com";
  const novaJanela = window.open(gmailUrl, "_blank");
  setTimeout(() => {
    if (!novaJanela || novaJanela.closed) window.location.href = mailtoUrl;
  }, 300);
}

function abrirWhatsApp(e) {
  e.preventDefault();
  const numero = "5521992141882";
  const url    = `https://api.whatsapp.com/send?phone=${numero}&text=`;
  window.open(url, "_blank");
}