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
    <link href="/plataforma/assets/css/custom.css" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        main {
            flex: 1;
        }

        footer {
            font-size: 0.9rem;
        }

        /* Mascote Alfabot */
        #alfabot-container {
            position: fixed;
            bottom: 60px;
            right: 20px;
            z-index: 9999;
            display: flex;
            align-items: flex-end;
        }

        #alfabot-img {
            height: 80px;
            margin-right: 100px;
            transition: opacity 0.5s;
        }

        .piscar {
            opacity: 0.6;
        }

        #alfabot-balao {
            background: #fff;
            color: #333;
            padding: 10px 15px;
            border-radius: 10px;
            max-width: 240px;
            font-size: 0.9rem;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

<!-- NAVBAR GLOBAL -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/plataforma/public/dashboard.php">
            <img src="/plataforma/assets/img/alfabot/logo_alfabot.png" alt="Alfabot Logo" style="height: 70px; margin-right: 0px;">
            <strong>ALFABOT</strong>
        </a>
        <div class="d-flex align-items-center">
            <?php if (isset($_SESSION['user_nome'])): ?>
                <span class="navbar-text text-white me-3">
                    <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['user_nome']); ?>
                </span>
                <a class="btn btn-sm btn-light" href="/plataforma/logout.php">
                    <i class="bi bi-box-arrow-right"></i> Sair
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- CONTEÚDO PRINCIPAL -->
<main class="container py-4">
