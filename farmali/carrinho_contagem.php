<?php
session_start(); header('Content-Type: application/json; charset=utf-8');
$c=$_SESSION['farmali_carrinho']??[];
echo json_encode(['total_itens'=>array_sum(array_column($c,'quantidade'))]);
