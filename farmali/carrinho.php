<?php
session_start(); require_once __DIR__.'/php/conexao.php'; $c=$_SESSION['farmali_carrinho']??[]; $msg='';
function h($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
if($_SERVER['REQUEST_METHOD']==='POST'){ $acao=$_POST['acao']??''; $id=(int)($_POST['id']??0);
 if($acao==='remover'){unset($c[$id]);$_SESSION['farmali_carrinho']=$c;header('Location: carrinho.php');exit;}
 if($acao==='limpar'){unset($_SESSION['farmali_carrinho']);header('Location: carrinho.php');exit;}
 if($acao==='atualizar'&&isset($c[$id])){$q=max(1,(int)($_POST['quantidade']??1));$s=$conexao->prepare("SELECT QUANTIDADE_ESTOQUE FROM PRODUTO WHERE COD_PRODUTO=?");$s->execute([$id]);$est=(int)$s->fetchColumn();if($q>$est)$msg='A quantidade ultrapassa o estoque disponível.';else{$c[$id]['quantidade']=$q;$_SESSION['farmali_carrinho']=$c;header('Location: carrinho.php');exit;}}
}
$total=0;$itens=0;foreach($c as $i){$total+=$i['preco']*$i['quantidade'];$itens+=$i['quantidade'];}
?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Carrinho | FARMAURA</title><link rel="stylesheet" href="style.css"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"></head><body id="top">
<header>
<div class="header"><div class="logo"><a href="index.html"><span>FARMAURA</span></a></div>
<div class="cadcar"><a class="cad" href="php/login.php"><i class="fa-solid fa-user" id="cad"></i><span>Minha conta</span></a><a class="car" href="carrinho.php"><i class="fa-solid fa-cart-shopping" id="car"></i><span>Carrinho <b class="contadorCarrinhoTopo">0</b></span></a></div></div>
<div class="search"><form class="busca-site" action="produtos.php" method="get"><input type="text" name="q" id="campoBusca" placeholder="Buscar produtos..." aria-label="Buscar produtos"><button id="btnSearch" type="submit" aria-label="Buscar"><i class="fa-solid fa-magnifying-glass"></i></button></form>
<nav class="links"><a href="index.html">Início</a><a href="produtos.php#categorias">Categorias</a><a href="produtos.php#produtos">Produtos</a></nav></div></header>
<main class="pagina-fluxo"><p class="sobretitulo">FARMAURA • SEUS PRODUTOS</p><h1>Meu carrinho</h1><p class="texto-intro">Confira os produtos selecionados.</p>
<?php if($msg):?><p class="alerta-erro"><?=h($msg)?></p><?php endif;?>
<?php if(!$c):?><section class="carrinho-vazio"><i class="fa-solid fa-cart-shopping"></i><h2>Seu carrinho está vazio</h2><p>Adicione produtos para vê-los aqui.</p><a class="botao-primario" href="produtos.php">Ver produtos</a></section>
<?php else:?><div class="carrinho-layout"><section class="itens-carrinho">
<?php foreach($c as $id=>$i):?><article class="item-carrinho"><div class="item-icone"><i class="fa-solid fa-pills"></i></div><div class="item-detalhes"><h2><?=h($i['nome'])?></h2><p>R$ <?=number_format($i['preco'],2,',','.')?> por unidade</p><form method="post" class="form-quantidade"><input type="hidden" name="acao" value="atualizar"><input type="hidden" name="id" value="<?=$id?>"><label>Quantidade <input type="number" name="quantidade" min="1" max="<?=$i['estoque']?>" value="<?=$i['quantidade']?>"></label><button class="botao-secundario">Atualizar</button></form></div><div class="item-total"><strong>R$ <?=number_format($i['preco']*$i['quantidade'],2,',','.')?></strong><form method="post"><input type="hidden" name="acao" value="remover"><input type="hidden" name="id" value="<?=$id?>"><button class="link-remover">Remover</button></form></div></article><?php endforeach;?>
</section><aside class="resumo-compra"><h2>Resumo do pedido</h2><p><?=$itens?> item(ns)</p><div class="linha-total"><span>Total</span><strong>R$ <?=number_format($total,2,',','.')?></strong></div><a class="botao-primario largura-total" href="compra.php">Continuar para compra</a><a class="link-voltar" href="produtos.php">Continuar comprando</a><form method="post"><button class="link-remover" name="acao" value="limpar">Limpar carrinho</button></form></aside></div><?php endif;?></main>
<footer class="footer"><div class="voltar-topo"><a href="#top">Voltar ao topo <i class="fa-solid fa-chevron-up"></i></a></div><div class="footer-container">
<div class="footer-logo"><h2>FARMAURA</h2><p>Saúde e cuidado para você.</p></div>
<div class="footer-links"><h3>Navegação</h3><a href="index.html">Início</a><a href="produtos.php">Produtos</a><a href="carrinho.php">Carrinho</a><a href="compra.php">Finalizar compra</a></div>
<div class="footer-contato"><h3>Atendimento</h3><p>(11) 0000-0000</p><p>contato@farmaura.com</p></div></div><div class="footer-bottom"><p>© 2026 FARMAURA. Todos os direitos reservados.</p></div></footer>
<script src="carrinho.js"></script></body></html>