<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$erro = '';
if (!empty($_SESSION['erro_login'])) {
    $erro = $_SESSION['erro_login'];
    unset($_SESSION['erro_login']);
}

$sucesso = '';
if (isset($_GET['cadastro']) && $_GET['cadastro'] === '1') {
    $sucesso = 'Cadastro realizado com sucesso! Faça login abaixo.';
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login | Plataforma</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <div class="card shadow-sm p-4" style="max-width: 400px; margin: auto;">
        <h1 class="text-center mb-3 fw-bold">🎓 Plataforma EAD</h1>
        <h4 class="mb-4 text-center">
            <i class="bi bi-box-arrow-in-right"></i> Acesso à Plataforma
        </h4>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($sucesso)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form action="../controllers/auth_login.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <button type="submit" id="loginBtn" class="btn btn-primary w-100">
                <i class="bi bi-door-open"></i> Entrar
            </button>
        </form>
        <p class="text-center mt-3">
            Ainda não tem conta? <a href="register.php">Cadastre-se</a>
        </p>
    </div>
</div>

<script>
    const btn = document.getElementById('loginBtn');
    btn.addEventListener('click', () => {
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Entrando...';
    });
</script>

</body>
</html>
