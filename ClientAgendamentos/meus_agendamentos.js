document.addEventListener("DOMContentLoaded", () => {
    carregarAgendamentos();
});

function carregarAgendamentos() {
    const container = document.getElementById("container-lista");

    fetch('listar_agendamentos.php')
        .then(response => response.json())
        .then(dados => {
            container.innerHTML = ""; // Limpa o carregando

            // Se der erro de login
            if (dados.erro) {
                alert(dados.mensagem);
                window.location.href = "../PaginaCadastro/cadastro.html";
                return;
            }

            // Se a lista estiver vazia
            if (dados.length === 0) {
                container.innerHTML = '<p style="text-align:center; padding:20px;">Você não tem agendamentos marcados.</p>';
                return;
            }

            // Cria o HTML igualzinho ao seu modelo
            dados.forEach(item => {
                const div = document.createElement("div");
                div.className = "agendamento";
                
                div.innerHTML = `
                                <div class="info">
                                    <h3>Corte / Barba</h3>
                                    <p><strong>Data:</strong> ${item.data_formatada}</p>
                                    <p><strong>Horário:</strong> ${item.horario}</p>
                                </div>
                                <button class="btn-cancelar" onclick="cancelarAgendamento(${item.id})">Cancelar</button>
                            `;
                
                container.appendChild(div);
            });
        })
        .catch(erro => {
            console.error(erro);
            container.innerHTML = "<p>Erro ao carregar.</p>";
        });
}

function cancelarAgendamento(id) {
    if (confirm("Tem certeza que deseja cancelar este agendamento?")) {
        fetch('cancelar_agendamento.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_agendamento: id })
        })
        .then(res => res.json())
        .then(resposta => {
            if (resposta.sucesso) {
                alert("Agendamento cancelado!");
                carregarAgendamentos(); // Recarrega a lista para sumir o item
            } else {
                alert("Erro: " + resposta.mensagem);
            }
        });
    }
}