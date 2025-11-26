// Seleciona o formulário, não o botão
const formulario = document.getElementById("formCadastro");

formulario.addEventListener("submit", function(event) {
    const usuario = document.getElementById("usuario").value.trim();
    const senha = document.getElementById("senha").value.trim();
    const email = document.getElementById("email").value.trim();
    const celular = document.getElementById("celular").value.trim();

    // 1. Validação de campos vazios
    if (!usuario || !email || !senha || !celular) {
        alert("Por favor, preencha todos os campos.");
        event.preventDefault(); // CANCELA o envio para o PHP
        return;
    }

    // 2. Validação de Email
    const emailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailValido.test(email)) {
        alert("Insira um e-mail válido!");
        event.preventDefault(); // CANCELA o envio para o PHP
        return;
    }

    // SE PASSOU POR TUDO:
    // Não fazemos nada aqui. O formulário segue naturalmente para o PHP,
    // que vai salvar no banco e redirecionar para 'cabelo.html'.
});