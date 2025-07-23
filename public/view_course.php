<?php
session_start();
require_once '../config/db.php';

// Verifica se é professor
if ($_SESSION['user_tipo'] !== 'professor') {
    header("Location: error.php?mensagem=Acesso restrito ao professor.");
    exit;
}

// Verifica o ID do curso
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: list_courses.php?mensagem=ID inválido.");
    exit;
}

// Busca o curso
$stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ? AND professor_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$curso = $stmt->fetch();

if (!$curso) {
    header("Location: list_courses.php?mensagem=Curso não encontrado ou acesso negado.");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Curso | Plataforma</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">🎓 Plataforma</a>
        <div class="d-flex align-items-center">
            <span class="navbar-text text-white me-3">
                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['user_nome']); ?>
            </span>
            <a class="btn btn-sm btn-light" href="logout.php">
                <i class="bi bi-box-arrow-right"></i> Sair
            </a>
        </div>
    </div>
</nav>

<!-- CONTEÚDO -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-info shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-eye"></i> Detalhes do Curso</h5>
                </div>
                <div class="card-body">
                    <h4 class="card-title text-primary"><?php echo htmlspecialchars($curso['titulo']); ?></h4>
                    <p class="card-text"><strong>Descrição:</strong><br><?php echo nl2br(htmlspecialchars($curso['descricao'])); ?></p>
                    <p class="card-text">
                        <strong>Status:</strong>
                        <?php if ($curso['status'] === 'ativo'): ?>
                            <span class="badge bg-success">Ativo</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inativo</span>
                        <?php endif; ?>
                    </p>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <a href="edit_course.php?id=<?php echo $curso['id']; ?>" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="list_courses.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- RODAPÉ -->
<footer class="bg-primary text-white text-center py-3 mt-5">
    Plataforma de Cursos Online e Ensino à Distância (EAD) © 2025 <?php echo date("Y"); ?> | Desenvolvido por ALFREDO MIANGO
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
