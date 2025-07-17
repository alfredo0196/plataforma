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

$aula_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$aula_id) {
    header("Location: error.php?mensagem=Aula não especificada.");
    exit;
}

// Buscar módulo da aula
$stmt = $pdo->prepare("SELECT titulo, modulo_id FROM aulas WHERE id = ?");
$stmt->execute([$aula_id]);
$aula = $stmt->fetch();

if (!$aula) {
    header("Location: error.php?mensagem=Aula não encontrada.");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Exclusão da Aula</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card border-danger shadow-sm p-4">
        <h4 class="text-danger"><i class="bi bi-exclamation-triangle"></i> Confirmar Exclusão</h4>
        <p>Deseja realmente excluir a aula <strong><?php echo htmlspecialchars($aula['titulo']); ?></strong>?</p>
        
        <form action="delete_lesson.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $aula_id; ?>">
            <input type="hidden" name="modulo_id" value="<?php echo $aula['modulo_id']; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Sim, Excluir</button>
            <a href="list_lessons.php?modulo_id=<?php echo $aula['modulo_id']; ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
</body>
</html>
