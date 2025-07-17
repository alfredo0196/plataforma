<?php
session_start();
require_once '../config/db.php';

// Verifica se o usuário está logado e se é aluno
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'aluno') {
    header("Location: login.php");
    exit;
}

// Verifica se a coluna user_id existe na tabela inscricoes
$checkColumn = $pdo->prepare("
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'inscricoes'
      AND COLUMN_NAME = 'user_id'
");
$checkColumn->execute();
$columnExists = $checkColumn->fetchColumn();

if (!$columnExists) {
    die("<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
        <h2 style='color:red;'>Erro de configuração</h2>
        <p>A coluna <strong>user_id</strong> não existe na tabela <strong>inscricoes</strong>.</p>
        <p>Verifique sua base de dados.</p>
    </div>");
}

// Consulta os cursos em que o aluno está inscrito
$stmt = $pdo->prepare("
    SELECT cursos.* FROM cursos
    JOIN inscricoes ON cursos.id = inscricoes.curso_id
    WHERE inscricoes.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
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
                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['user_nome'] ?? 'Usuário'); ?> (aluno)
            </span>
            <a class="btn btn-sm btn-light" href="logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </div>
    </div>
</nav>

<!-- CONTEÚDO -->
<div class="container">
    <h2 class="mb-4">Meus Cursos Inscritos</h2>

    <?php if (count($cursos) > 0): ?>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php foreach ($cursos as $curso): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-book"></i> <?php echo htmlspecialchars($curso['titulo']); ?></h5>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($curso['descricao'])); ?></p>
                            <span class="badge bg-<?php echo $curso['status'] === 'ativo' ? 'success' : 'secondary'; ?>">
                                <?php echo ucfirst($curso['status']); ?>
                            </span>
                            <div class="mt-3">
                                <a href="list_modules.php?curso_id=<?php echo $curso['id']; ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-journal-text"></i> Ver Conteúdo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="list_courses.php" class="btn btn-outline-secondary mt-4">
            <i class="bi bi-arrow-left-circle"></i> Ver Todos os Cursos
        </a>
    <?php else: ?>
        <div class="alert alert-info">Você ainda não está inscrito em nenhum curso.</div>
        <a href="list_courses.php" class="btn btn-outline-secondary mt-3">
            <i class="bi bi-arrow-left-circle"></i> Ver Todos os Cursos
        </a>
    <?php endif; ?>
</div>

<!-- RODAPÉ -->
<footer class="bg-primary text-white text-center py-3 mt-5">
    Plataforma de Cursos Online e Ensino à Distância (EAD) © <?php echo date("Y"); ?> | Desenvolvido por ALFREDO MIANGO
</footer>

</body>
</html>
