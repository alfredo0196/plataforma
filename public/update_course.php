<?php
session_start();
require_once '../config/db.php';

if ($_SESSION['user_tipo'] !== 'professor') {
    header("Location: error.php?mensagem=Acesso não autorizado.");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validação de campos obrigatórios
    if (!isset($_POST['id'], $_POST['titulo'], $_POST['descricao'], $_POST['status'])) {
        header("Location: error.php?mensagem=Dados incompletos.");
        exit;
    }

    $id = $_POST['id'];
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $status = trim($_POST['status']);

    // Validar status permitido
    $status_permitidos = ['ativo', 'inativo'];
    if (!in_array($status, $status_permitidos)) {
        header("Location: error.php?mensagem=Status inválido.");
        exit;
    }

    // Atualizar curso
    $stmt = $pdo->prepare("UPDATE cursos SET titulo = ?, descricao = ?, status = ? WHERE id = ? AND professor_id = ?");
    $stmt->execute([$titulo, $descricao, $status, $id, $_SESSION['user_id']]);

    if ($stmt->rowCount() > 0) {
        header("Location: list_courses.php?mensagem=Curso atualizado com sucesso.");
    } else {
        header("Location: error.php?mensagem=Nenhum curso atualizado. Verifique se o curso pertence a você.");
    }
    exit;
} else {
    header("Location: error.php?mensagem=Requisição inválida.");
    exit;
}
