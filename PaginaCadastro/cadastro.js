document.querySelector(".botaoteste").addEventListener("click", function(event) {
  const usuario = document.getElementById("usuario").value.trim();
  const senha = document.getElementById("senha").value.trim();
  const email = document.getElementById("email").value.trim();
  const celular = document.getElementById("celular").value.trim();

  if (!usuario || !email || !senha || !celular) {
    alert("Por favor, preencha todos os campos.");
    return;
  }

  const emailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailValido.test(email)) {
    alert("Insira um e-mail válido!");
    return;
  }

  alert("Cadastro realizado com sucesso!");
  this.reset();
});
