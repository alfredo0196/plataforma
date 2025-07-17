<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['modulo_id'])) {
    header("Location: error.php?mensagem=Módulo não especificado.");
    exit;
}

$modulo_id = $_GET['modulo_id'];

$stmt = $pdo->prepare("SELECT m.*, c.professor_id FROM modulos m INNER JOIN cursos c ON m.curso_id = c.id WHERE m.id = ?");
$stmt->execute([$modulo_id]);
$modulo = $stmt->fetch();

if (!$modulo) {
    header("Location: error.php?mensagem=Módulo não encontrado.");
    exit;
}

if ($_SESSION['user_tipo'] === 'professor' && $modulo['professor_id'] != $_SESSION['user_id']) {
    header("Location: error.php?mensagem=Acesso não autorizado.");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM aulas WHERE modulo_id = ? ORDER BY id ASC");
$stmt->execute([$modulo_id]);
$aulas = $stmt->fetchAll();

$mensagem = $_GET['mensagem'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Aulas do Módulo</title>
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
                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['user_nome']); ?>
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
        <h2>Aulas do Módulo: <?php echo htmlspecialchars($modulo['titulo']); ?></h2>
        <?php if ($_SESSION['user_tipo'] === 'professor'): ?>
            <a href="create_lesson.php?modulo_id=<?php echo $modulo_id; ?>" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nova Aula
            </a>
        <?php endif; ?>
    </div>

    <?php if (count($aulas) > 0): ?>
        <ul class="list-group">
            <?php foreach ($aulas as $aula): ?>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div>
                        <h5><i class="bi bi-play-circle text-success"></i> <?php echo htmlspecialchars($aula['titulo']); ?></h5>
                        <p class="mb-1"><?php echo nl2br(htmlspecialchars($aula['conteudo'])); ?></p>
                    </div>
                    <?php if ($_SESSION['user_tipo'] === 'professor'): ?>
                        <div class="text-end">
                            <a href="edit_lesson.php?id=<?php echo $aula['id']; ?>" class="btn btn-sm btn-warning mb-1">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>
                            <a href="delete_lesson.php?id=<?php echo $aula['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja excluir esta aula?')">
                                <i class="bi bi-trash"></i> Excluir
                            </a>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-info mt-4">Nenhuma aula cadastrada neste módulo.</div>
    <?php endif; ?>
</div>

<footer class="bg-primary text-white text-center py-3 mt-5">
    Plataforma de Cursos Online e Ensino à Distância (EAD) © <?php echo date("Y"); ?> | Desenvolvido por ALFREDO MIANGO
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
