document.addEventListener("DOMContentLoaded", () => {
    carregarAgenda();
});

function carregarAgenda() {
    const lista = document.getElementById("lista-admin");

    fetch('listar_todos.php')
        .then(res => res.json())
        .then(dados => {
            lista.innerHTML = "";

            if (dados.erro) {
                alert(dados.mensagem);
                window.location.href = "../index.html"; // Chuta pra fora se não for admin
                return;
            }

            if (dados.length === 0) {
                lista.innerHTML = "<p class='carregando'>Nenhum corte agendado por enquanto.</p>";
                return;
            }

            dados.forEach(item => {
                // Limpa o telefone para o link (tira parenteses e traços)
                let zapLimpo = item.celular.replace(/\D/g, ""); 
                let linkZap = `https://api.whatsapp.com/send?phone=55${zapLimpo}&text=Olá ${item.usuario}, tudo bem? Sobre seu agendamento na Davi Carmo Barber...`;

                // Cria o Card HTML
                lista.innerHTML += `
                    <div class="card-admin">
                        <div class="info-cliente">
                            <h3>${item.usuario}</h3>
                            <p>📅 ${item.data_fmt} às ${item.horario}</p>
                            <p>📱 ${item.celular}</p>
                            <p class="status-ok">● Confirmado</p>
                        </div>
                        <div class="acoes">
                            <a href="${linkZap}" target="_blank" class="btn-zap">WhatsApp</a>
                            <button class="btn-excluir" onclick="concluirCorte(${item.id})">Concluir / Excluir</button>
                        </div>
                    </div>
                `;
            });
        })
        .catch(err => {
            console.error(err);
            lista.innerHTML = "<p class='carregando'>Erro ao carregar dados.</p>";
        });
}

function concluirCorte(id) {
    if(confirm("Deseja marcar esse corte como concluído e remover da lista?")) {
        fetch('excluir_admin.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: id})
        })
        .then(res => res.json())
        .then(resp => {
            if(resp.sucesso) {
                carregarAgenda(); // Recarrega a lista
            } else {
                alert("Erro: " + resp.mensagem);
            }
        });
    }
}