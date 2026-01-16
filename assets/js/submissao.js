document.addEventListener("DOMContentLoaded", () => {
  const userPerfil = document.querySelector('.user-profile');
  const arrowPerfil = document.querySelector('.arrow-icon');
  const modalErro = document.getElementById("modalErro");
  const fecharModalErro = document.getElementById("fecharModalErro");

  /* Função de Redirecionamento para o Login */
  const irParaLogin = () => {
    window.location.href = "./login.php";
  };

  if (modalErro && modalErro.classList.contains("ativo")) {
    let timeout;

    if (window.redirecionarAoFechar) {
      timeout = setTimeout(() => {
        irParaLogin();
      }, 5000);
    }

    if (fecharModalErro) {
      fecharModalErro.addEventListener("click", (e) => {
        e.preventDefault();
        if (timeout) clearTimeout(timeout);
        modalErro.classList.remove("ativo");
        if (window.redirecionarAoFechar) {
          irParaLogin();
        }
      });
    }
  }

  /* Ativar DropDown */
  const userProfile = document.querySelector('.user-profile');
  const dropdownMenu = document.getElementById("dropdownMenu");

  if (userProfile && dropdownMenu) {
    userProfile.addEventListener('click', (e) => {
      e.stopPropagation(); // Impede o clique de propagar para o window
      dropdownMenu.classList.toggle("ativo");
    });
  }

  // Fecha o dropdown ao clicar fora
  window.addEventListener('click', (event) => {
    if (dropdownMenu && !event.target.closest('.user-profile')) {
      if (dropdownMenu.classList.contains("ativo")) {
        dropdownMenu.classList.remove("ativo");
      }
    }
  });

  userPerfil.addEventListener('mouseover', () => {
    userPerfil.classList.add("user-profile-hover");
    arrowPerfil.classList.add("arrow-icon-hover");
  });

  userPerfil.addEventListener('mouseleave', () => {
    userPerfil.classList.remove("user-profile-hover");
    arrowPerfil.classList.remove("arrow-icon-hover");
  });

});

/* Validação do Arquivo */
function atualizarNomeArquivo(input) {
  const display = document.getElementById('nome-arquivo');
  const arquivo = input.files[0];

  // Se o usuário abrir o seletor e cancelar, não faz nada
  if (!arquivo) {
    display.innerText = "";
    return;
  }

  const extensoesPermitidas = /(\.pdf|\.docx)$/i;
  const limiteTamanho = 10 * 1024 * 1024; // 10MB

  // 1. Validação de Extensão
  if (!extensoesPermitidas.exec(arquivo.name)) {
    input.value = ''; // Limpa o campo para o PHP não receber arquivo errado
    display.innerText = "Arquivo inválido. Por favor, selecione apenas arquivos PDF ou DOCX.";
    display.style.color = "red";
    display.style.fontWeight = "bold";
    return;
  }

  // 2. Validação de Tamanho
  if (arquivo.size > limiteTamanho) {
    input.value = ''; // Limpa o campo
    display.innerText = "O arquivo é muito grande! O limite é de 10MB.";
    display.style.color = "red";
    display.style.fontWeight = "bold";
    return;
  }

  // 3. Caso passe em todas as validações (Sucesso)
  display.innerText = "Arquivo pronto: " + arquivo.name;
  display.style.color = "#2ecc71"; // Verde sucesso
  display.style.fontWeight = "normal";
}

function atualizarEstadoBotao() {
  const container = document.getElementById('container-coautores');
  const botaoAdd = document.querySelector('.btn-coautor');
  const totalCoautores = container.getElementsByClassName('coautor-item').length;

  if (totalCoautores >= 5) {
    botaoAdd.style.backgroundColor = "#ccc";
    botaoAdd.style.color = "black";
    botaoAdd.style.cursor = "not-allowed";
    botaoAdd.innerHTML = '<i class="fa fa-ban"></i> Limite de 5 coautores atingido';
    botaoAdd.disabled = true; // Impede cliques indesejados
  } else {
    // Restaura as propriedades originais
    botaoAdd.style.backgroundColor = "";
    botaoAdd.style.color = "";
    botaoAdd.style.cursor = "pointer";
    botaoAdd.innerHTML = '<i class="fa fa-plus"></i> Adicionar outro Coautor';
    botaoAdd.disabled = false;
  }
}

/* Adicionando Coautores */
function addCoautor() {
  const container = document.getElementById('container-coautores');

  if (container.getElementsByClassName('coautor-item').length >= 5) {
    return;
  }

  const novoItem = document.createElement('div');
  novoItem.className = 'coautor-item';
  novoItem.style = 'background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 10px; border: 1px solid #ddd; position: relative;';

  novoItem.innerHTML = `
        <button type="button" class="remover-js" style="position: absolute; right: 10px; top: 4px; border: none; background: none; color: red; cursor: pointer;">&times; Remover</button>
        <input type="text" name="coautor_nome[]" placeholder="Nome Completo" style="margin-bottom:5px;">
        <input type="email" name="coautor_email[]" placeholder="E-mail" style="margin-bottom:5px;">
        <input type="text" name="coautor_inst[]" placeholder="Instituição/Afiliação">
    `;

  container.appendChild(novoItem);

  const btnRemover = novoItem.querySelector('.remover-js');

  btnRemover.addEventListener('click', function () {
    novoItem.remove();
    atualizarEstadoBotao();
  });

  atualizarEstadoBotao();
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