<?php
session_start();
require_once '../config/db.php';

if ($_SESSION['user_tipo'] !== 'professor') {
    header("Location: ../public/dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validação de campos obrigatórios
    if (
        empty($_POST['id']) ||
        empty($_POST['titulo']) ||
        empty($_POST['descricao']) ||
        empty($_POST['status'])
    ) {
        header("Location: ../public/error.php?mensagem=Dados incompletos.");
        exit;
    }

    $id = (int) $_POST['id'];
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $status = trim($_POST['status']);

    // Validar status permitido
    $valid_status = ['ativo', 'inativo'];
    if (!in_array($status, $valid_status)) {
        header("Location: ../public/error.php?mensagem=Status inválido.");
        exit;
    }

    // Atualizar
    $stmt = $pdo->prepare("UPDATE cursos SET titulo = ?, descricao = ?, status = ? WHERE id = ? AND professor_id = ?");

    try {
        $stmt->execute([$titulo, $descricao, $status, $id, $_SESSION['user_id']]);
        header("Location: ../public/list_courses.php?mensagem=Curso atualizado com sucesso.");
        exit;
    } catch (PDOException $e) {
        header("Location: ../public/error.php?mensagem=Erro ao atualizar curso.");
        exit;
    }
} else {
    header("Location: ../public/error.php?mensagem=Requisição inválida.");
    exit;
}
?>
