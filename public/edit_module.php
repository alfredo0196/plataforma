<?php
session_start();
require_once '../config/db.php';

// Permissão
if ($_SESSION['user_tipo'] !== 'professor') {
    header("Location: error.php?mensagem=Acesso restrito.");
    exit;
}

// Validação do ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: error.php?mensagem=Módulo não informado.");
    exit;
}

// Buscar módulo
$stmt = $pdo->prepare("SELECT * FROM modulos WHERE id = ?");
$stmt->execute([$id]);
$modulo = $stmt->fetch();

if (!$modulo) {
    header("Location: error.php?mensagem=Módulo não encontrado.");
    exit;
}

// Processar atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $ordem = filter_input(INPUT_POST, 'ordem', FILTER_VALIDATE_INT);

    if (!$ordem) {
        header("Location: error.php?mensagem=Ordem inválida.");
        exit;
    }

    $stmt = $pdo->prepare("UPDATE modulos SET titulo = ?, ordem = ? WHERE id = ?");
    $stmt->execute([$titulo, $ordem, $id]);

    header("Location: list_modules.php?curso_id={$modulo['curso_id']}&mensagem=Módulo atualizado com sucesso.");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Módulo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        footer { font-size: 0.9rem; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">🎓 Plataforma</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">
                <i class="bi bi-person-circle"></i>
                <?php echo htmlspecialchars($_SESSION['user_nome']); ?> (<?php echo htmlspecialchars($_SESSION['user_tipo']); ?>)
            </span>
            <a class="btn btn-sm btn-light" href="logout.php">
                <i class="bi bi-box-arrow-right"></i> Sair
            </a>
        </div>
    </div>
</nav>

<!-- FORMULÁRIO -->
<div class="container">
    <div class="card p-4 shadow-sm border-warning mb-5">
        <h3 class="mb-4 text-warning">
            <i class="bi bi-pencil"></i> Editar Módulo
        </h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Título</label>
                <input type="text" name="titulo" value="<?php echo htmlspecialchars($modulo['titulo']); ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Ordem</label>
                <input type="number" name="ordem" value="<?php echo (int)$modulo['ordem']; ?>" class="form-control" required>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save"></i> Salvar Alterações
                </button>
                <a href="list_modules.php?curso_id=<?php echo $modulo['curso_id']; ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- RODAPÉ -->
<footer class="bg-primary text-white text-center py-3">
    Plataforma de Cursos Online e Ensino à Distância (EAD) © 2025 <?php echo date("Y"); ?> | Desenvolvido por Alfredo Miango
</footer>

</body>
</html>
