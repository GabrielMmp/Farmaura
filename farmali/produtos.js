function alterarQtd(botao, mudanca) {
    const popover = botao.closest('.produto-popover');
    const spanQtd = popover.querySelector('.num-qtd');
    const spanTotal = popover.querySelector('.valor-total-popover');
    
    const precoUnitario = parseFloat(popover.getAttribute('data-preco-unitario'));
    
    let qtdAtual = parseInt(spanQtd.innerText);
    qtdAtual += mudanca;
    
    if (qtdAtual < 1) qtdAtual = 1; // Impede que a quantidade seja menor que 1
    
    spanQtd.innerText = qtdAtual;
    
    const novoTotal = (precoUnitario * qtdAtual).toFixed(2);
    spanTotal.innerText = novoTotal;
}

// 3. AJAX para buscar a quantidade de itens salvos assim que a página é carregada
document.addEventListener("DOMContentLoaded", () => {
    fetch('/api/carrinho-total')
        .then(res => res.json())
        .then(dados => {
            document.getElementById('qtd-itens').innerText = dados.totalItens;
        })
        .catch(err => console.error("Erro ao carregar total do carrinho:", err));
});

// Captura e envia todas as informações corretas via AJAX
document.querySelectorAll('.btn-comprar').forEach(button => {
    button.addEventListener('click', function() {
        const popover = this.closest('.produto-popover');
        
        // Coleta as propriedades direto dos atributos do botão clicado
        const id = this.getAttribute('data-id');
        const nome = this.getAttribute('data-nome') || "Produto";
        
        // Coleta os valores numéricos gerados na interface do popover
        const quantidade = parseInt(popover.querySelector('.num-qtd').innerText) || 1;
        const precoTotal = parseFloat(popover.querySelector('.valor-total-popover').innerText);

        // Envia o pacote completo em formato JSON via AJAX para o Node.js
        fetch('/api/carrinho', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id: id,
                nome: nome,
                quantidade: quantidade,
                precoTotal: precoTotal
            })
        })
        .then(res => res.json())
        .then(dados => {
            if (dados.sucesso) {
                // Atualiza o contador vermelho de itens do topo na hora
                document.getElementById('qtd-itens').innerText = dados.totalItens;
               
                // Esconde o popover da tela após o sucesso
                popover.classList.remove('ativo');
                
                // Reseta os contadores do popover para que fiquem prontos para um próximo clique
                popover.querySelector('.num-qtd').innerText = "1";
                popover.querySelector('.valor-total-popover').innerText = popover.getAttribute('data-preco-unitario');
               
                alert(`${nome} adicionado ao carrinho com sucesso!`);
            }
        })
        .catch(err => console.error("Erro na requisição AJAX de compra:", err));
    });
});

// Fecha o botão flutuante se o cliente clicar em qualquer área vazia fora do card
document.addEventListener('click', function(evento) {
    if (!evento.target.closest('.produto-card')) {
        document.querySelectorAll('.produto-popover').forEach(p => p.classList.remove('ativo'));
    }
});