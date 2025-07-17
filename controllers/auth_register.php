<?php
session_start();

// CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Token CSRF inválido.");
}

require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validação e sanitização
    $nome = strip_tags(trim($_POST['nome']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo'];

    // Validar campos obrigatórios
    if (!$email || empty($nome) || empty($senha)) {
        $_SESSION['erro_registro'] = "Preencha todos os campos corretamente.";
        header("Location: ../public/register.php");
        exit;
    }

    // Validar tipo
    $tipos_validos = ['aluno', 'professor'];
    if (!in_array($tipo, $tipos_validos)) {
        $_SESSION['erro_registro'] = "Tipo de usuário inválido.";
        header("Location: ../public/register.php");
        exit;
    }

    // Hash da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");

    try {
        $stmt->execute([$nome, $email, $senha_hash, $tipo]);

        $_SESSION['sucesso_registro'] = "Conta criada com sucesso! Faça login.";
        header("Location: ../public/login.php");
        exit;

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            $_SESSION['erro_registro'] = "Este email já está cadastrado.";
        } else {
            $_SESSION['erro_registro'] = "Erro ao registrar. Tente novamente.";
        }
        header("Location: ../public/register.php");
        exit;
    }
}
?>
