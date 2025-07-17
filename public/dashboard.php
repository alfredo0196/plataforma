<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Plataforma</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: 600;
        }
        footer {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">🎓 Plataforma</a>
        <div class="d-flex align-items-center">
            <span class="navbar-text text-white me-3">
                <i class="bi bi-person-circle"></i>
                <?php echo htmlspecialchars($_SESSION['user_nome']); ?>
                (<?php echo htmlspecialchars($_SESSION['user_tipo']); ?>)
            </span>
            <a class="btn btn-sm btn-light" href="logout.php">
                <i class="bi bi-box-arrow-right"></i> Sair
            </a>
        </div>
    </div>
</nav>

<!-- CONTEÚDO -->
<div class="container py-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold">Bem-vindo(a), <?php echo htmlspecialchars($_SESSION['user_nome']); ?>!</h2>
        <p class="text-muted">Aqui você gerencia seus cursos e atividades.</p>
    </div>

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

<!-- RODAPÉ -->
<footer class="bg-primary text-white text-center py-3 mt-5">
    Plataforma de Cursos Online e Ensino à Distância (EAD) ©<?php echo date("Y"); ?> | Desenvolvido por ALFREDO MIANGO
</footer>

</body>
</html>
