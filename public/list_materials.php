<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Valida o curso_id
$curso_id = filter_input(INPUT_GET, 'curso_id', FILTER_VALIDATE_INT);
if (!$curso_id) {
    header("Location: error.php?mensagem=Curso inválido.");
    exit;
}

// Verifica se o curso existe
$stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ?");
$stmt->execute([$curso_id]);
$curso = $stmt->fetch();

if (!$curso) {
    header("Location: error.php?mensagem=Curso não encontrado.");
    exit;
}

// Verifica se o aluno está inscrito no curso (caso seja aluno)
if ($_SESSION['user_tipo'] === 'aluno') {
    $stmt = $pdo->prepare("SELECT * FROM inscricoes WHERE curso_id = ? AND user_id = ?");
    $stmt->execute([$curso_id, $_SESSION['user_id']]);
    if (!$stmt->fetch()) {
        header("Location: error.php?mensagem=Acesso negado ao material.");
        exit;
    }
}

// Busca os materiais
$stmt = $pdo->prepare("SELECT * FROM materiais WHERE curso_id = ? ORDER BY criado_em DESC");
$stmt->execute([$curso_id]);
$materiais = $stmt->fetchAll();

$mensagem = $_GET['mensagem'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Materiais do Curso</title>
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
                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['user_nome']); ?> (<?php echo htmlspecialchars($_SESSION['user_tipo']); ?>)
            </span>
            <a class="btn btn-sm btn-light" href="logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </div>
    </div>
</nav>

<!-- CONTEÚDO -->
<div class="container">
    <?php if ($mensagem): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($mensagem); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Materiais do Curso: <?php echo htmlspecialchars($curso['titulo']); ?></h2>
        <?php if ($_SESSION['user_tipo'] === 'professor'): ?>
            <a href="create_material.php?curso_id=<?php echo $curso_id; ?>" class="btn btn-success">
                <i class="bi bi-upload"></i> Adicionar Material
            </a>
        <?php endif; ?>
    </div>

    <?php if (count($materiais) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
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
                                <?php if ($_SESSION['user_tipo'] === 'professor'): ?>
                                    <a href="delete_material.php?id=<?php echo $material['id']; ?>&curso_id=<?php echo $curso_id; ?>"
                                       onclick="return confirm('Deseja remover este material?')"
                                       class="btn btn-sm btn-danger" title="Excluir">
                                        <i class="bi bi-trash"></i> Excluir
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Nenhum material encontrado para este curso.</div>
    <?php endif; ?>

    <a href="list_modules.php?curso_id=<?php echo $curso_id; ?>" class="btn btn-outline-secondary mt-4">
        <i class="bi bi-arrow-left-circle"></i> Voltar aos Módulos
    </a>
</div>

<!-- RODAPÉ -->
<footer class="bg-primary text-white text-center py-3 mt-5">
    Plataforma de Cursos Online e Ensino à Distância (EAD) © <?php echo date("Y"); ?>
</footer>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
