<?php
session_start();
require_once '../config/db.php';

// Verifica se é professor
if ($_SESSION['user_tipo'] !== 'professor') {
    header("Location: dashboard.php");
    exit;
}

// Valida módulo
if (!isset($_GET['modulo_id'])) {
    echo "Módulo não informado.";
    exit;
}

$modulo_id = (int)$_GET['modulo_id'];

// Processa formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $conteudo = trim($_POST['conteudo']);

    $stmt = $pdo->prepare("INSERT INTO aulas (modulo_id, titulo, conteudo) VALUES (?, ?, ?)");
    $stmt->execute([$modulo_id, $titulo, $conteudo]);

    header("Location: list_lessons.php?modulo_id=$modulo_id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Criar Aula | Plataforma</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">🎓 Plataforma</a>
        <div class="d-flex align-items-center">
            <span class="navbar-text text-white me-3">
                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['user_nome']); ?> (<?php echo htmlspecialchars($_SESSION['user_tipo']); ?>)
            </span>
            <a class="btn btn-sm btn-light" href="logout.php">
                <i class="bi bi-box-arrow-right"></i> Sair
            </a>
        </div>
    </div>
</nav>

<!-- FORMULÁRIO -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-success shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-journal-plus"></i> Criar Nova Aula</h5>
                </div>
                <div class="card-body">
                    <form method="POST" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label">Título da Aula</label>
                            <input type="text" name="titulo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Conteúdo</label>
                            <textarea name="conteudo" class="form-control" rows="6" required></textarea>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Criar Aula
                            </button>
                            <a href="list_lessons.php?modulo_id=<?php echo htmlspecialchars($modulo_id); ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- RODAPÉ -->
<footer class="bg-primary text-white text-center py-3 mt-5">
    Plataforma de Cursos Online e Ensino à Distância (EAD) © 2025 <?php echo date("Y"); ?> | Desenvolvido por ALFREDO MIANGO
</footer>

</body>
</html>
