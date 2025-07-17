<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'professor') {
    header("Location: dashboard.php");
    exit;
}

// Gera CSRF token se não existir
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$modulo_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$modulo_id) {
    header("Location: error.php?mensagem=Módulo não especificado.");
    exit;
}

// Busca título e curso
$stmt = $pdo->prepare("SELECT titulo, curso_id FROM modulos WHERE id = ?");
$stmt->execute([$modulo_id]);
$modulo = $stmt->fetch();

if (!$modulo) {
    header("Location: error.php?mensagem=Módulo não encontrado.");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Exclusão do Módulo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card border-danger shadow-sm p-4">
        <h4 class="text-danger"><i class="bi bi-exclamation-triangle"></i> Confirmar Exclusão</h4>
        <p>Deseja realmente excluir o módulo <strong><?php echo htmlspecialchars($modulo['titulo']); ?></strong>?</p>

        <form action="delete_module.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $modulo_id; ?>">
            <input type="hidden" name="curso_id" value="<?php echo $modulo['curso_id']; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Sim, Excluir</button>
            <a href="list_modules.php?curso_id=<?php echo $modulo['curso_id']; ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
</body>
</html>
