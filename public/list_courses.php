<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Geração de token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$mensagem = $_GET['mensagem'] ?? null;

// Cursos por tipo de usuário
if ($_SESSION['user_tipo'] === 'professor') {
    $stmt = $pdo->prepare("SELECT * FROM cursos WHERE professor_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM cursos WHERE status = 'ativo'");
    $stmt->execute();
}
$cursos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Meus Cursos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">🎓 Plataforma</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">
                <i class="bi bi-person-circle"></i> <?php echo $_SESSION['user_nome']; ?> (<?php echo $_SESSION['user_tipo']; ?>)
            </span>
            <a class="btn btn-sm btn-light" href="logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </div>
    </div>
</nav>

<!-- MENSAGEM DE ALERTA -->
<div class="container">
    <?php if ($mensagem): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($mensagem); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Meus Cursos</h2>
        <?php if ($_SESSION['user_tipo'] === 'professor'): ?>
            <a href="create_course.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Criar Novo Curso</a>
        <?php endif; ?>
    </div>

    <?php if (count($cursos) > 0): ?>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php foreach ($cursos as $curso): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($curso['titulo']); ?></h5>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($curso['descricao'])); ?></p>
                            <span class="badge bg-<?php echo $curso['status'] === 'ativo' ? 'success' : 'secondary'; ?>">
                                <?php echo ucfirst($curso['status']); ?>
                            </span>
                        </div>
                        <div class="card-footer d-flex justify-content-end flex-wrap gap-2 bg-white border-top-0">
                            <?php if ($_SESSION['user_tipo'] === 'aluno'): ?>
                                <a href="enroll_course.php?id=<?php echo $curso['id']; ?>" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-check-circle"></i> Inscrever-se
                                </a>
                            <?php endif; ?>

                            <?php if ($_SESSION['user_tipo'] === 'professor'): ?>
                                <a href="edit_course.php?id=<?php echo $curso['id']; ?>" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </a>

                                <a href="view_course.php?id=<?php echo $curso['id']; ?>" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-eye"></i> Visualizar
                                </a>

                                <form method="POST" action="delete_course.php" onsubmit="return confirm('Tem certeza que deseja excluir este curso?');" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $curso['id']; ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
                                </form>

                                <a href="list_modules.php?curso_id=<?php echo $curso['id']; ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-list-ul"></i> Módulos
                                </a>

                                <a href="list_materials.php?curso_id=<?php echo $curso['id']; ?>" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-paperclip"></i> Materiais
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info mt-4">Nenhum curso disponível.</div>
    <?php endif; ?>
</div>

<!-- RODAPÉ -->
<footer class="bg-primary text-white text-center py-3 mt-5">
    Plataforma de Cursos Online e Ensino à Distância (EAD) © <?php echo date("Y"); ?> | Desenvolvido por ALFREDO MIANGO
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
