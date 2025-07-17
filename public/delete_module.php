<?php
session_start();
require_once '../config/db.php';

if ($_SESSION['user_tipo'] !== 'professor') {
    header("Location: error.php?mensagem=Acesso não autorizado.");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
    !isset($_POST['csrf_token']) ||
    $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Requisição inválida ou token CSRF incorreto.");
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$curso_id = filter_input(INPUT_POST, 'curso_id', FILTER_VALIDATE_INT);

if (!$id || !$curso_id) {
    header("Location: error.php?mensagem=Parâmetros inválidos.");
    exit;
}

// Deleta
$stmt = $pdo->prepare("DELETE FROM modulos WHERE id = ?");
$stmt->execute([$id]);

header("Location: list_modules.php?curso_id=$curso_id&mensagem=Módulo excluído com sucesso.");
exit;
