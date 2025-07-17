<?php
session_start();
require_once '../config/db.php';

// Verifica se usuário é professor
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'professor') {
    header("Location: error.php?mensagem=Acesso não autorizado.");
    exit;
}

// Verifica se a requisição é POST e valida o token CSRF
if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
    !isset($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die("Requisição inválida ou token CSRF incorreto.");
}

// Valida ID do curso
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: list_courses.php?mensagem=ID inválido.");
    exit;
}

// Verifica se o curso pertence ao professor logado antes de excluir
$stmt = $pdo->prepare("SELECT id FROM cursos WHERE id = ? AND professor_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$curso = $stmt->fetch();

if (!$curso) {
    header("Location: list_courses.php?mensagem=Curso não encontrado ou acesso negado.");
    exit;
}

// Executa exclusão
$stmt = $pdo->prepare("DELETE FROM cursos WHERE id = ? AND professor_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);

header("Location: list_courses.php?mensagem=Curso excluído com sucesso.");
exit;
