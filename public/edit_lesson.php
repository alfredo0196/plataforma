<?php
session_start();
require_once '../config/db.php';

// Verifica permissão
if ($_SESSION['user_tipo'] !== 'professor') {
    header("Location: dashboard.php");
    exit;
}

// Valida ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: error.php?mensagem=Aula não especificada.");
    exit;
}

// Busca dados da aula
$stmt = $pdo->prepare("SELECT * FROM aulas WHERE id = ?");
$stmt->execute([$id]);
$aula = $stmt->fetch();

if (!$aula) {
    header("Location: error.php?mensagem=Aula não encontrada.");
    exit;
}

// Processa atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $conteudo = trim($_POST['conteudo']);

    $stmt = $pdo->prepare("UPDATE aulas SET titulo = ?, conteudo = ? WHERE id = ?");
    $stmt->execute([$titulo, $conteudo, $id]);

    header("Location: list_lessons.php?modulo_id=" . $aula['modulo_id'] . "&mensagem=Aula atualizada com sucesso.");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Aula</title>
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

<!-- CONTEÚDO -->
<div class="container">
    <div class="card shadow-sm p-4 mb-5 border-warning">
        <h3 class="mb-4 text-warning">
            <i class="bi bi-pencil-square"></i> Editar Aula
        </h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Título da Aula</label>
                <input type="text" name="titulo" class="form-control" value="<?php echo htmlspecialchars($aula['titulo']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Conteúdo</label>
                <textarea name="conteudo" class="form-control" rows="6" required><?php echo htmlspecialchars($aula['conteudo']); ?></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Salvar Alterações
                </button>
                <a href="list_lessons.php?modulo_id=<?php echo $aula['modulo_id']; ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- RODAPÉ -->
<footer class="bg-primary text-white text-center py-3">
    Plataforma de Cursos Online e Ensino à Distância (EAD) © 2025 <?php echo date("Y"); ?> | Desenvolvido por ALFREDO MIANGO
</footer>

</body>
</html>
