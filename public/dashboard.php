<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Inclui o cabeçalho e a navbar
include '../layouts/header.php';
?>

<!-- CONTEÚDO PRINCIPAL -->
<div class="container py-5 text-center">
    <h2 class="fw-bold mb-3">Bem-vindo(a), <?php echo htmlspecialchars($_SESSION['user_nome']); ?>!</h2>
    <p class="text-muted mb-4">Aqui você gerencia seus cursos e atividades.</p>

    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <a href="list_courses.php" class="btn btn-primary w-100 py-3 shadow-sm">
                <i class="bi bi-book"></i> Ver Meus Cursos
            </a>
        </div>

        <?php if ($_SESSION['user_tipo'] === 'professor'): ?>
        <div class="col-md-4">
            <a href="create_course.php" class="btn btn-success w-100 py-3 shadow-sm">
                <i class="bi bi-plus-circle"></i> Criar Novo Curso
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Inclui o rodapé com o mascote e scripts
include '../layouts/footer.php';
?>
