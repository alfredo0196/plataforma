<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'professor') {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT cursos.id, cursos.titulo AS curso, COUNT(inscricoes.id) AS inscritos
    FROM cursos
    LEFT JOIN inscricoes ON cursos.id = inscricoes.curso_id
    WHERE cursos.professor_id = ?
    GROUP BY cursos.id
    ORDER BY inscritos DESC
");
$stmt->execute([$_SESSION['user_id']]);
$relatorio = $stmt->fetchAll();

$totalInscritos = array_sum(array_column($relatorio, 'inscritos'));
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatório do Professor</title>
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
                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['user_nome'] ?? 'Professor'); ?>
            </span>
            <a class="btn btn-sm btn-light" href="logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="mb-4">Relatório de Inscrições por Curso</h2>

    <?php if (count($relatorio) > 0): ?>
        <p><strong>Total de inscrições em todos os cursos:</strong> <?php echo $totalInscritos; ?></p>
        <table class="table table-striped table-hover shadow-sm">
            <thead class="table-primary">
                <tr>
                    <th><i class="bi bi-book"></i> Curso</th>
                    <th><i class="bi bi-people-fill"></i> Inscritos</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($relatorio as $linha): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($linha['curso']); ?>
                            <a href="list_modules.php?curso_id=<?php echo $linha['id']; ?>" class="btn btn-sm btn-outline-primary ms-2">
                                <i class="bi bi-journal-text"></i> Gerenciar
                            </a>
                        </td>
                        <td><?php echo $linha['inscritos']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">
            Você ainda não possui cursos cadastrados ou nenhum aluno se inscreveu.
            <a href="create_course.php" class="alert-link">Criar um curso</a>.
        </div>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-outline-secondary mt-4">
        <i class="bi bi-arrow-left-circle"></i> Voltar
    </a>
</div>

<footer class="bg-primary text-white text-center py-3 mt-5">
    Plataforma de Cursos Online e Ensino à Distância (EAD) © <?php echo date("Y"); ?> | Desenvolvido por ALFREDO MIANGO
</footer>

</body>
</html>
