<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['curso_id'])) {
    header("Location: error.php?mensagem=Curso não especificado.");
    exit;
}

$curso_id = $_GET['curso_id'];

// Verificar se o curso existe
$stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ?");
$stmt->execute([$curso_id]);
$curso = $stmt->fetch();

if (!$curso) {
    header("Location: error.php?mensagem=Curso não encontrado.");
    exit;
}

// Verificar se o professor tem acesso ao curso
if ($_SESSION['user_tipo'] === 'professor' && $curso['professor_id'] != $_SESSION['user_id']) {
    header("Location: error.php?mensagem=Acesso não autorizado.");
    exit;
}

// Buscar os módulos
$stmt = $pdo->prepare("SELECT * FROM modulos WHERE curso_id = ? ORDER BY ordem ASC");
$stmt->execute([$curso_id]);
$modulos = $stmt->fetchAll();

$mensagem = $_GET['mensagem'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Módulos do Curso</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">🎓 Plataforma</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">
                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['user_nome']); ?> (<?php echo htmlspecialchars($_SESSION['user_tipo']); ?>)
            </span>
            <a class="btn btn-sm btn-light" href="logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </div>
    </div>
</nav>

<div class="container">
    <?php if ($mensagem): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($mensagem); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Módulos do Curso: <?php echo htmlspecialchars($curso['titulo']); ?></h2>
        <?php if ($_SESSION['user_tipo'] === 'professor'): ?>
            <div class="d-flex gap-2">
                <a href="create_module.php?curso_id=<?php echo $curso_id; ?>" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Adicionar Módulo
                </a>
                <a href="list_materials.php?curso_id=<?php echo $curso_id; ?>" class="btn btn-secondary">
                    <i class="bi bi-paperclip"></i> Materiais
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php if (count($modulos) > 0): ?>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php foreach ($modulos as $modulo): ?>
                <div class="col">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($modulo['titulo']); ?></h5>
                            <p class="card-text"><strong>Ordem:</strong> <?php echo $modulo['ordem']; ?></p>
                        </div>
                        <div class="card-footer bg-white d-flex justify-content-end gap-2">
                            <a href="list_lessons.php?modulo_id=<?php echo $modulo['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-journal-text"></i> Ver Aulas
                            </a>
                            <?php if ($_SESSION['user_tipo'] === 'professor'): ?>
                                <a href="edit_module.php?id=<?php echo $modulo['id']; ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </a>
                                <a href="delete_module.php?id=<?php echo $modulo['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja excluir este módulo?')">
                                    <i class="bi bi-trash"></i> Excluir
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info mt-4">Nenhum módulo encontrado para este curso.</div>
    <?php endif; ?>

    <a href="list_courses.php" class="btn btn-outline-secondary mt-4">
        <i class="bi bi-arrow-left-circle"></i> Voltar aos Cursos
    </a>
</div>

<footer class="bg-primary text-white text-center py-3 mt-5">
    Plataforma de Cursos Online e Ensino à Distância (EAD) © <?php echo date("Y"); ?>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
