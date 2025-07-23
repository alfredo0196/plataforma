<?php
session_start();
$_SESSION = [];
session_destroy();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Saindo...</title>
    <meta http-equiv="refresh" content="2; URL=login.php">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center min-vh-100">
    <div class="text-center">
        <div class="spinner-border text-primary mb-3" role="status"></div>
        <h4 class="text-muted">
            <i class="bi bi-box-arrow-right"></i> Saindo da plataforma...
        </h4>
        <p class="mt-3">
            <a href="login.php" class="btn btn-outline-primary">Clique aqui se não for redirecionado</a>
        </p>
    </div>
</body>
</html>
