document.addEventListener("DOMContentLoaded", () => {
    const inputData = document.getElementById("dataAgendamento");
    const divHorarios = document.getElementById("listaHorarios");

    inputData.setAttribute("min", new Date().toISOString().split("T")[0]);

    inputData.addEventListener("change", function() {
        const data = this.value;
        if (!data) return;

        divHorarios.innerHTML = '<p>Buscando horários...</p>';

        fetch(`buscar_horarios.php?data=${data}`)
            .then(res => res.json())
            .then(horarios => {
                divHorarios.innerHTML = "";
                if (horarios.length === 0) {
                    divHorarios.innerHTML = '<p>Sem horários livres.</p>';
                    return;
                }
                horarios.forEach(hora => {
                    const btn = document.createElement("button");
                    btn.className = "btn-horario";
                    btn.innerText = hora;
                    btn.onclick = () => confirmar(data, hora);
                    divHorarios.appendChild(btn);
                });
            });
    });
});

// ... (o começo do arquivo continua igual)

function confirmar(data, hora) {
    if (confirm(`Agendar para ${data} às ${hora}?`)) {
        
        // Envia os dados para o PHP salvar
        fetch('salvar_agendamento.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({data: data, horario: hora})
        })
        .then(res => res.json())
        .then(resp => {
            // Mostra o alerta (seja de erro ou sucesso)
            alert(resp.mensagem);

            // SE O AGENDAMENTO DEU CERTO:
            if (resp.sucesso) {
                // --- A MÁGICA É ESSA LINHA AQUI ---
                // ".." sai da pasta Agendar
                // "/PaginaOpcoes" entra na pasta Opcoes
                window.location.href = "../PaginaOpcoes/opcoes.html";
            }
        })
        .catch(erro => {
            console.error(erro);
            alert("Erro de conexão.");
        });
    }
}