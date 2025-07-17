<?php
session_start();
require_once '../config/db.php';
require_once '../utils/validador.php'; // ✅ Incluí validação reutilizável

if ($_SESSION['user_tipo'] !== 'professor') {
    header("Location: error.php?mensagem=Acesso não autorizado.");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: list_courses.php?mensagem=ID inválido.");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ? AND professor_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$curso = $stmt->fetch();

if (!$curso) {
    header("Location: list_courses.php?mensagem=Curso não encontrado.");
    exit;
}

$erro = '';
$titulo = $curso['titulo'];
$descricao = $curso['descricao'];
$status = $curso['status'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $status = $_POST['status'];

    // ✅ Validação centralizada
    if (!titulo_valido($titulo)) {
        $erro = "O título só pode conter letras, números, espaços e símbolos comuns como hífen (-), dois-pontos (:), ponto (.) ou parênteses.";
    } else {
        $stmt = $pdo->prepare("UPDATE cursos SET titulo = ?, descricao = ?, status = ? WHERE id = ? AND professor_id = ?");
        $stmt->execute([$titulo, $descricao, $status, $id, $_SESSION['user_id']]);

        header("Location: list_courses.php?mensagem=Curso atualizado com sucesso.");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Curso | Plataforma</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">🎓 Plataforma</a>
        <div class="d-flex align-items-center">
            <span class="navbar-text text-white me-3">
                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['user_nome']); ?>
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
            <div class="card border-warning shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Curso</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($erro)): ?>
                        <div class="alert alert-danger"><?php echo $erro; ?></div>
                    <?php endif; ?>
                    <form method="POST" autocomplete="off" id="cursoForm">
                        <div class="mb-3">
                            <label class="form-label">Título</label>
                            <input type="text" name="titulo" id="titulo" class="form-control" required value="<?php echo htmlspecialchars($titulo); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descrição</label>
                            <textarea name="descricao" class="form-control" rows="5" required><?php echo htmlspecialchars($descricao); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="ativo" <?php echo $status === 'ativo' ? 'selected' : ''; ?>>Ativo</option>
                                <option value="inativo" <?php echo $status === 'inativo' ? 'selected' : ''; ?>>Inativo</option>
                            </select>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-warning text-white">
                                <i class="bi bi-check-circle"></i> Salvar Alterações
                            </button>
                            <a href="list_courses.php" class="btn btn-secondary">
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
    Plataforma de Cursos Online © <?php echo date("Y"); ?> | Desenvolvido por Alfredo Miango
</footer>

<!-- Bootstrap JS + Validação -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('cursoForm').addEventListener('submit', function(event) {
    const titulo = document.getElementById('titulo').value.trim();
    const regex = /^[a-zA-ZÀ-ÿ0-9\s\-:\.\(\)\[\]]+$/;

    if (!regex.test(titulo)) {
        event.preventDefault();
        alert("O título só pode conter letras, números, espaços e símbolos como hífen (-), dois-pontos (:), ponto (.), colchetes e parênteses.");
    }
});
</script>
</body>
</html>
