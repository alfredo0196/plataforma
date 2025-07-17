<?php
session_start();

// Gera token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Mensagens de feedback
$erro = '';
if (!empty($_SESSION['erro_registro'])) {
    $erro = $_SESSION['erro_registro'];
    unset($_SESSION['erro_registro']);
}

$sucesso = '';
if (!empty($_SESSION['sucesso_registro'])) {
    $sucesso = $_SESSION['sucesso_registro'];
    unset($_SESSION['sucesso_registro']);
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Cadastro | Plataforma</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 0.75rem;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center min-vh-100">

<div class="container">
    <div class="card shadow-sm p-4" style="max-width: 500px; margin: auto;">
        <h4 class="mb-4 text-center"><i class="bi bi-person-plus"></i> Criar Conta</h4>

        <?php if ($erro): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($sucesso); ?>
            </div>
        <?php endif; ?>

        <form action="../controllers/auth_register.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <div class="mb-3">
                <label class="form-label">Nome Completo</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tipo de Usuário</label>
                <select name="tipo" class="form-select" required>
                    <option value="">Selecione</option>
                    <option value="aluno">Aluno</option>
                    <option value="professor">Professor</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-check-circle"></i> Registrar
            </button>
        </form>
        <p class="text-center mt-3">Já tem conta? <a href="login.php">Faça login</a></p>
    </div>
</div>

</body>
</html>
