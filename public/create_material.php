<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'professor') {
    header("Location: error.php?mensagem=Acesso negado.");
    exit;
}

$curso_id = filter_input(INPUT_GET, 'curso_id', FILTER_VALIDATE_INT);
if (!$curso_id) {
    header("Location: error.php?mensagem=Curso inválido.");
    exit;
}

// Verificar se o professor é dono do curso
$stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ? AND professor_id = ?");
$stmt->execute([$curso_id, $_SESSION['user_id']]);
$curso = $stmt->fetch();
if (!$curso) {
    header("Location: error.php?mensagem=Curso não encontrado ou acesso negado.");
    exit;
}

// Upload de arquivo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
        $nome_arquivo = $_FILES['arquivo']['name'];
        $caminho_temporario = $_FILES['arquivo']['tmp_name'];
        $pasta_destino = '../uploads/';

        if (!is_dir($pasta_destino)) {
            mkdir($pasta_destino, 0777, true);
        }

        $caminho_arquivo = $pasta_destino . time() . '_' . basename($nome_arquivo);

        if (move_uploaded_file($caminho_temporario, $caminho_arquivo)) {
            $stmt = $pdo->prepare("INSERT INTO materiais (curso_id, nome_arquivo, caminho_arquivo) VALUES (?, ?, ?)");
            $stmt->execute([$curso_id, $nome_arquivo, $caminho_arquivo]);

            header("Location: list_materials.php?curso_id=$curso_id&mensagem=Material enviado com sucesso!");
            exit;
        } else {
            $erro = "Erro ao mover o arquivo.";
        }
    } else {
        $erro = "Selecione um arquivo válido.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Enviar Material</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

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

<div class="container">
    <h2 class="mb-4">Enviar Material para o Curso: <?php echo htmlspecialchars($curso['titulo']); ?></h2>

    <?php if (isset($erro)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="arquivo" class="form-label">Selecionar Arquivo</label>
            <input type="file" name="arquivo" id="arquivo" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="bi bi-upload"></i> Enviar
        </button>

        <a href="list_materials.php?curso_id=<?php echo $curso_id; ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Voltar
        </a>
    </form>
</div>

<footer class="bg-primary text-white text-center py-3 mt-5">
    Plataforma de Cursos Online e Ensino à Distância (EAD) © <?php echo date("Y"); ?>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
