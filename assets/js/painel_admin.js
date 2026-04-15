function toggleMenu() {
  document.querySelector('.sidebar').classList.toggle('open');
}

// garante que o script só roda depois que o DOM estiver pronto
  document.addEventListener("DOMContentLoaded", function() {
    const btnToggle = document.getElementById("btn-dashboard");
    const home = document.getElementById("Painel de Gerenciamento");
    const dashboard = document.getElementById("dashboard");

    btnToggle.addEventListener("click", function() {
      if (dashboard.style.display === "none") {
        // mostra dashboard e esconde home
        home.style.display = "none";
        dashboard.style.display = "block";
        btnToggle.textContent = "Voltar à Home"; // muda o texto do botão
      } else {
        // volta para home e esconde dashboard
        dashboard.style.display = "none";
        home.style.display = "block";
        btnToggle.textContent = "Ver Dashboard"; // volta o texto original
      }
    });
  });