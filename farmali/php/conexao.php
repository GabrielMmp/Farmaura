<?php
$servidor = '127.0.0.1';
$porta = 3307;
$banco = 'FARMALI';
$usuario = 'root';
$senha = '';

try {
    $conexao = new PDO(
        "mysql:host=$servidor;port=$porta;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha
    );
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $erro) {
    die("Erro ao conectar com o banco de dados: " . htmlspecialchars($erro->getMessage(), ENT_QUOTES, 'UTF-8'));
}
?>
