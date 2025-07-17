<?php
session_start();
require_once '../config/db.php';

// Verifica se o usuário é professor
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'professor') {
    header("Location: dashboard.php");
    exit;
}

// Valida ID do curso
$curso_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$curso_id) {
    header("Location: list_courses.php?erro=ID inválido");
    exit;
}

// Verifica se o curso existe e pertence ao professor
$stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ? AND professor_id = ?");
$stmt->execute([$curso_id, $_SESSION['user_id']]);
$curso = $stmt->fetch();

if (!$curso) {
    header("Location: list_courses.php?erro=Curso não encontrado ou não permitido");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Exclusão</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center min-vh-100">
<div class="card shadow p-4">
    <h4 class="mb-3">Confirmar Exclusão</h4>
    <p>Tem certeza que deseja excluir o curso <strong><?php echo htmlspecialchars($curso['titulo']); ?></strong>?</p>
    <div class="d-flex gap-2">
        <a href="delete_course.php?id=<?php echo $curso['id']; ?>" class="btn btn-danger">
            <i class="bi bi-trash"></i> Sim, excluir
        </a>
        <a href="list_courses.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Cancelar
        </a>
    </div>
</div>
</body>
</html>
