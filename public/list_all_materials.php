<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'aluno') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Busca os cursos nos quais o aluno está inscrito
$stmt = $pdo->prepare("
    SELECT c.id, c.titulo, c.descricao
    FROM cursos c
    JOIN inscricoes i ON c.id = i.curso_id
    WHERE i.user_id = ?
");
$stmt->execute([$user_id]);
$cursos = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Materiais Disponíveis</title>
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
                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['user_nome']); ?> (Aluno)
            </span>
            <a class="btn btn-sm btn-light" href="logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="mb-4">Todos os Materiais Disponíveis</h2>

    <?php if (count($cursos) > 0): ?>
        <?php foreach ($cursos as $curso): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">
                    <?php echo htmlspecialchars($curso['titulo']); ?>
                </div>
                <div class="card-body">
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM materiais WHERE curso_id = ? ORDER BY criado_em DESC");
                    $stmt->execute([$curso['id']]);
                    $materiais = $stmt->fetchAll();
                    ?>

                    <?php if (count($materiais) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nome do Arquivo</th>
                                        <th>Data de Upload</th>
                                        <th class="text-end">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($materiais as $material): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($material['nome_arquivo']); ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($material['criado_em'])); ?></td>
                                            <td class="text-end">
                                                <a href="<?php echo htmlspecialchars($material['caminho_arquivo']); ?>" target="_blank" class="btn btn-sm btn-info" title="Visualizar">
                                                    <i class="bi bi-eye"></i> Ver
                                                </a>
                                                <a href="<?php echo htmlspecialchars($material['caminho_arquivo']); ?>" download="<?php echo htmlspecialchars($material['nome_arquivo']); ?>" class="btn btn-sm btn-secondary" title="Baixar">
                                                    <i class="bi bi-download"></i> Baixar
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Nenhum material disponível neste curso.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">Você ainda não está inscrito em nenhum curso com materiais disponíveis.</div>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-outline-secondary mt-3">
        <i class="bi bi-arrow-left-circle"></i> Voltar ao Painel
    </a>
</div>

<footer class="bg-primary text-white text-center py-3 mt-5">
    Plataforma de Cursos Online e Ensino à Distância (EAD) © <?php echo date("Y"); ?>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
