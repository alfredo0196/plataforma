<?php
session_start();
require_once '../config/db.php';

// Somente alunos
if ($_SESSION['user_tipo'] !== 'aluno') {
    header("Location: error.php?mensagem=Acesso apenas para alunos.");
    exit;
}

// Validação do curso_id
$curso_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$curso_id) {
    header("Location: error.php?mensagem=Curso inválido.");
    exit;
}

$user_id = $_SESSION['user_id'];

// Verificar se o curso existe e está ativo
$stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ? AND status = 'ativo'");
$stmt->execute([$curso_id]);
$curso = $stmt->fetch();

if (!$curso) {
    header("Location: error.php?mensagem=Curso não encontrado ou inativo.");
    exit;
}

// Verificar inscrição
$stmt = $pdo->prepare("SELECT 1 FROM inscricoes WHERE curso_id = ? AND user_id = ?");
$stmt->execute([$curso_id, $user_id]);
$ja_inscrito = $stmt->fetch();

if ($ja_inscrito) {
    $mensagem = "Você já está inscrito neste curso.";
    $tipo = "warning";
} else {
    $stmt = $pdo->prepare("INSERT INTO inscricoes (curso_id, user_id) VALUES (?, ?)");
    $stmt->execute([$curso_id, $user_id]);
    $mensagem = "Inscrição realizada com sucesso!";
    $tipo = "success";
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Inscrição no Curso</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            max-width: 450px;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center min-vh-100">

<div class="card p-4 shadow-sm text-center border-<?php echo htmlspecialchars($tipo); ?>">
    <div class="fs-1 text-<?php echo htmlspecialchars($tipo); ?>">
        <i class="bi <?php echo $tipo === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'; ?>"></i>
    </div>
    <h4 class="my-3"><?php echo htmlspecialchars($mensagem); ?></h4>
    <a href="my_courses.php" class="btn btn-outline-primary">
        <i class="bi bi-arrow-right-circle"></i> Ver Meus Cursos
    </a>
</div>

</body>
</html>
