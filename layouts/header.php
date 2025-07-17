<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle ?? 'Plataforma EAD'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap + Ícones -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/css/custom.css" rel="stylesheet"> <!-- se quiser CSS próprio -->
</head>
<body class="bg-light">

<!-- NAVBAR GLOBAL -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/dashboard.php">🎓 Plataforma</a>
        <div class="d-flex align-items-center">
            <?php if (isset($_SESSION['user_nome'])): ?>
                <span class="navbar-text text-white me-3">
                    <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['user_nome']); ?>
                </span>
                <a class="btn btn-sm btn-light" href="/logout.php">
                    <i class="bi bi-box-arrow-right"></i> Sair
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container">
