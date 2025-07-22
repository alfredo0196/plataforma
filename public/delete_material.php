<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'professor') {
    header("Location: error.php?mensagem=Acesso negado.");
    exit;
}

$material_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$material_id) {
    header("Location: error.php?mensagem=Material inválido.");
    exit;
}

// Verificar se o material existe e se o professor é o dono do curso
$stmt = $pdo->prepare("
    SELECT m.*, c.professor_id 
    FROM materiais m
    JOIN cursos c ON m.curso_id = c.id
    WHERE m.id = ?
");
$stmt->execute([$material_id]);
$material = $stmt->fetch();

if (!$material) {
    header("Location: error.php?mensagem=Material não encontrado.");
    exit;
}

if ($material['professor_id'] != $_SESSION['user_id']) {
    header("Location: error.php?mensagem=Você não tem permissão para excluir este material.");
    exit;
}

// Remover o arquivo físico (se existir)
if (file_exists($material['caminho_arquivo'])) {
    unlink($material['caminho_arquivo']);
}

// Excluir o registo no banco de dados
$stmt = $pdo->prepare("DELETE FROM materiais WHERE id = ?");
$stmt->execute([$material_id]);

header("Location: list_materials.php?curso_id=" . $material['curso_id'] . "&mensagem=Material excluído com sucesso.");
exit;
?>
