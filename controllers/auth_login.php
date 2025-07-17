<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar e sanitizar email
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'];

    if (!$email || empty($senha)) {
        $_SESSION['erro_login'] = "Preencha todos os campos corretamente.";
        header("Location: ../public/login.php");
        exit;
    }

    // Buscar usuário
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nome'] = $user['nome'];
        $_SESSION['user_tipo'] = $user['tipo'];

        header("Location: ../public/dashboard.php");
        exit;
    } else {
        $_SESSION['erro_login'] = "Email ou senha incorretos.";
        header("Location: ../public/login.php");
        exit;
    }
} else {
    header("Location: ../public/login.php");
    exit;
}
