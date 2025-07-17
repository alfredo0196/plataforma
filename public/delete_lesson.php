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
$modulo_id = filter_input(INPUT_POST, 'modulo_id', FILTER_VALIDATE_INT);

if (!$id || !$modulo_id) {
    header("Location: error.php?mensagem=Parâmetros inválidos.");
    exit;
}

$stmt = $pdo->prepare("DELETE FROM aulas WHERE id = ?");
$stmt->execute([$id]);

header("Location: list_lessons.php?modulo_id=$modulo_id&mensagem=Aula excluída com sucesso.");
exit;
