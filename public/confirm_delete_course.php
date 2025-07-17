<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'professor') {
    header("Location: dashboard.php");
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$curso_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$curso_id) {
    header("Location: list_courses.php?mensagem=ID de curso inválido.");
    exit;
}

// 🔹 Buscar título do curso no banco
$stmt = $pdo->prepare("SELECT titulo FROM cursos WHERE id = ? AND professor_id = ?");
$stmt->execute([$curso_id, $_SESSION['user_id']]);
$curso = $stmt->fetch();

if (!$curso) {
    header("Location: list_courses.php?mensagem=Curso não encontrado ou você não tem permissão.");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Exclusão do Curso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm border-danger p-4">
        <h4 class="text-danger"><i class="bi bi-exclamation-triangle"></i> Confirmar Exclusão</h4>
        <p>Tem certeza de que deseja excluir o curso <strong><?php echo htmlspecialchars($curso['titulo']); ?></strong>? Esta ação não poderá ser desfeita.</p>
        
        <form action="delete_course.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $curso_id; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i> Sim, excluir
            </button>
            <a href="list_courses.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
</body>
</html>
