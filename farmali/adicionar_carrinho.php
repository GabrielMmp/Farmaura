<?php
session_start();
require_once __DIR__ . '/php/conexao.php';
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['ok'=>false,'mensagem'=>'Método não permitido.']); exit; }
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$qtd = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);
if (!$id || !$qtd || $qtd < 1) { http_response_code(400); echo json_encode(['ok'=>false,'mensagem'=>'Produto ou quantidade inválidos.']); exit; }
$stmt=$conexao->prepare("SELECT COD_PRODUTO,NOME,PRECO,QUANTIDADE_ESTOQUE,STATUS FROM PRODUTO WHERE COD_PRODUTO=?");
$stmt->execute([$id]); $produto=$stmt->fetch(PDO::FETCH_ASSOC);
if (!$produto || $produto['STATUS']!=='ATIVO') { http_response_code(404); echo json_encode(['ok'=>false,'mensagem'=>'Produto não encontrado ou inativo.']); exit; }
$_SESSION['farmali_carrinho']=$_SESSION['farmali_carrinho']??[];
$atual=(int)($_SESSION['farmali_carrinho'][$id]['quantidade']??0);
if ($atual+$qtd>(int)$produto['QUANTIDADE_ESTOQUE']) { http_response_code(409); echo json_encode(['ok'=>false,'mensagem'=>'A quantidade ultrapassa o estoque disponível.']); exit; }
$_SESSION['farmali_carrinho'][$id]=['id'=>(int)$id,'nome'=>$produto['NOME'],'preco'=>(float)$produto['PRECO'],'quantidade'=>$atual+$qtd,'estoque'=>(int)$produto['QUANTIDADE_ESTOQUE']];
echo json_encode(['ok'=>true,'total_itens'=>array_sum(array_column($_SESSION['farmali_carrinho'],'quantidade'))]);
