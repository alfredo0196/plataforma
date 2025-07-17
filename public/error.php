<?php
session_start();

$mensagem = $_GET['mensagem'] ?? "Algo deu errado. Tente novamente mais tarde.";

// Redirecionamento opcional (ex: ?mensagem=Erro&id=123&voltar=pagina.php)
$voltar_para = $_GET['voltar'] ?? 'dashboard.php';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Erro | Plataforma</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .error-card {
            max-width: 480px;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center min-vh-100">

<!-- CARTÃO DE ERRO -->
<div class="card error-card shadow-sm border-danger text-center p-4 bg-white">
    <div class="fs-1 text-danger mb-3">
        <i class="bi bi-exclamation-triangle-fill"></i>
    </div>
    <h4 class="mb-3">Ops! Ocorreu um erro.</h4>
    <p class="text-muted"><?php echo htmlspecialchars($mensagem); ?></p>
    <a href="<?php echo htmlspecialchars($voltar_para); ?>" class="btn btn-outline-primary mt-3">
        <i class="bi bi-arrow-left-circle"></i> Voltar
    </a>
</div>

</body>
</html>
