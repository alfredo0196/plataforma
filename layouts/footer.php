</main> <!-- Fecha o conteúdo principal aberto no header.php -->

<!-- RODAPÉ FIXADO NO FINAL -->
<footer class="bg-primary text-white text-center py-3 mt-auto">
    Plataforma de Cursos Online e Ensino à Distância (EAD) © <?php echo date("Y"); ?> |
    Desenvolvido por ALFREDO MIANGO
</footer>

<!-- MASCOTE INTERATIVO -->
<div id="alfabot-container">
    <img src="/plataforma/assets/img/alfabot/mascote_alfabot.png" alt="Alfabot" id="alfabot-img">
    <div id="alfabot-balao" class="shadow">
        <p id="alfabot-msg">Olá! Pronto para aprender algo incrível hoje? 🚀</p>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- JS customizado -->
<script src="/plataforma/assets/js/validacoes.js"></script>

<!-- Mascote: mensagens e animação -->
<script>
    const mensagens = [
        "💡 Dica: Revise o conteúdo antes de fazer os exercícios!",
        "📚 Você pode baixar os materiais de apoio na aula.",
        "🎯 Mantenha o foco. Cada dia é uma nova chance de aprender!",
        "🏆 Você está indo muito bem. Continue assim!",
        "🤖 O Alfabot está aqui se precisar de ajuda!"
    ];

    setInterval(() => {
        const msg = mensagens[Math.floor(Math.random() * mensagens.length)];
        document.getElementById('alfabot-msg').textContent = msg;
    }, 10000);

    setInterval(() => {
        document.getElementById('alfabot-img').classList.toggle('piscar');
    }, 1500);
</script>

<!-- Estilos do Alfabot -->
<style>
    #alfabot-container {
        position: fixed;
        bottom: 60px;
        right: 20px;
        z-index: 9999;
        display: flex;
        align-items: flex-end;
    }

    #alfabot-img {
        height: 160px; /* aumentado */
        margin-right: 10px;
        transition: opacity 0.5s;
    }

    .piscar {
        opacity: 0.6;
    }

    #alfabot-balao {
        background: #fff;
        color: #333;
        padding: 10px 15px;
        border-radius: 10px;
        max-width: 240px;
        font-size: 0.95rem;
        border: 1px solid #ccc;
    }
</style>

</body>
</html>
