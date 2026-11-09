<?php
require_once __DIR__ . '/conexao.php';

$sql = "
    SELECT
        p.COD_PRODUTO,
        p.NOME,
        p.DESCRICAO,
        p.PRECO,
        p.QUANTIDADE_ESTOQUE,
        c.NOME AS CATEGORIA
    FROM PRODUTO p
    LEFT JOIN CATEGORIA c
        ON p.COD_CATEGORIA_FK = c.COD_CATEGORIA
    WHERE p.STATUS = 'ATIVO'
    ORDER BY p.NOME ASC
";

try {
    $consulta = $conexao->query($sql);
    $produtos = $consulta->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $erro) {
    die("Erro ao buscar produtos: " . htmlspecialchars($erro->getMessage(), ENT_QUOTES, 'UTF-8'));
}

function escapar($texto) {
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

$categorias = [];
foreach ($produtos as $produto) {
    $nomeCategoria = trim((string)($produto['CATEGORIA'] ?? ''));
    if ($nomeCategoria !== '' && !in_array($nomeCategoria, $categorias, true)) {
        $categorias[] = $nomeCategoria;
    }
}
sort($categorias, SORT_NATURAL | SORT_FLAG_CASE);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos | FARMAURA</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<header>
    <div class="header">
        <div class="logo">
            <a href="index.html"><span>FARMAURA</span></a>
        </div>
        <div class="cadcar">
            <a class="cad" href="php/login.php"><i class="fa-solid fa-user" id="cad"></i><span>Minha conta</span></a>
            <button class="car" type="button" id="abrirCarrinho"><i class="fa-solid fa-cart-shopping" id="car"></i><span>Carrinho <b id="contadorCarrinhoTopo">0</b></span></button>
        </div>
    </div>
    <div class="search">
        <input type="text" id="campoBusca" placeholder="Buscar produtos..." aria-label="Buscar produtos">
        <button id="btnSearch" type="button" aria-label="Buscar"><i class="fa-solid fa-magnifying-glass"></i></button>
        <nav class="links">
            <a href="index.html">Início</a>
            <a href="#categorias">Categorias</a>
            <a href="#produtos">Produtos</a>
        </nav>
    </div>
</header>

<main class="pagina-produtos">
    <section class="produtos-banner">
        <div>
            <p class="sobretitulo">FARMAURA • SAÚDE E CUIDADO</p>
            <h1>Encontre o que você precisa</h1>
            <p>Produtos para cuidar de você todos os dias.</p>
        </div>
        <i class="fa-solid fa-capsules banner-icone" aria-hidden="true"></i>
    </section>

    <section class="categorias secao-produtos" id="categorias">
        <h2>Explore por categoria</h2>
        <div class="categorias-container categorias-produtos">
            <button type="button" class="categoria categoria-filtro ativa" data-category-filter="">
                <span>Todos os produtos</span>
            </button>
            <?php foreach ($categorias as $categoria): ?>
                <button type="button" class="categoria categoria-filtro" data-category-filter="<?= escapar($categoria) ?>">
                    <span><?= escapar($categoria) ?></span>
                </button>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="secao-produtos secao-listagem" id="produtos">
        <div class="titulo-listagem">
            <div>
                <p class="sobretitulo">NOSSA SELEÇÃO</p>
                <h2>Produtos</h2>
            </div>
            <span id="resultadoBusca"><?= count($produtos) ?> produto(s)</span>
        </div>

        <div class="lista-produtos" id="listaProdutos">
            <?php if (count($produtos) === 0): ?>
                <p class="sem-produtos">Nenhum produto ativo foi encontrado no banco de dados.</p>
            <?php else: ?>
                <?php foreach ($produtos as $produto): ?>
                    <article class="cartao-produto"
                        data-nome="<?= escapar($produto['NOME']) ?>"
                        data-categoria="<?= escapar($produto['CATEGORIA'] ?? '') ?>">
                        <div class="produto-imagem"><i class="fa-solid fa-pills" aria-hidden="true"></i></div>
                        <div class="produto-informacoes">
                            <p class="produto-categoria"><?= escapar($produto['CATEGORIA'] ?? 'Geral') ?></p>
                            <h3><?= escapar($produto['NOME']) ?></h3>
                            <p class="produto-descricao"><?= escapar($produto['DESCRICAO'] ?? 'Cuidados para o seu dia a dia.') ?></p>
                            <p class="produto-estoque">
                                <?php if ((int)$produto['QUANTIDADE_ESTOQUE'] > 0): ?>
                                    <i class="fa-solid fa-circle-check"></i> Disponível
                                <?php else: ?>
                                    <i class="fa-solid fa-circle-xmark"></i> Indisponível
                                <?php endif; ?>
                            </p>
                            <p class="preco-produto">R$ <?= number_format((float)$produto['PRECO'], 2, ',', '.') ?></p>
                            <div class="controle-quantidade">
                                <button type="button" class="diminuir" aria-label="Diminuir quantidade">−</button>
                                <span class="quantidade">1</span>
                                <button type="button" class="aumentar" aria-label="Aumentar quantidade">+</button>
                            </div>
                            <button type="button" class="botao-carrinho"
                                data-id="<?= escapar($produto['COD_PRODUTO']) ?>"
                                data-nome="<?= escapar($produto['NOME']) ?>"
                                data-preco="<?= escapar($produto['PRECO']) ?>"
                                data-estoque="<?= (int)$produto['QUANTIDADE_ESTOQUE'] ?>">
                                <i class="fa-solid fa-cart-plus"></i> Adicionar ao carrinho
                            </button>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <p class="mensagem-sem-resultado" id="mensagemSemResultado" hidden>Nenhum produto corresponde à sua busca.</p>
    </section>
</main>

<footer class="footer">
    <div class="voltar-topo"><a href="#">Voltar ao topo <i class="fa-solid fa-chevron-up"></i></a></div>
    <div class="footer-container">
        <div class="footer-logo"><h2>FARMAURA</h2><p>Saúde e cuidado para você.</p></div>
        <div class="footer-links"><h3>Navegação</h3><a href="index.html">Início</a><a href="produtos.php">Produtos</a><a href="php/login.php">Minha conta</a></div>
        <div class="footer-links"><h3>Categorias</h3><?php foreach ($categorias as $categoria): ?><a href="#categorias" data-footer-category="<?= escapar($categoria) ?>"><?= escapar($categoria) ?></a><?php endforeach; ?></div>
        <div class="footer-contato"><h3>Atendimento</h3><p><i class="fa-solid fa-phone"></i> (11) 0000-0000</p><p><i class="fa-solid fa-envelope"></i> contato@farmaura.com</p><p><i class="fa-solid fa-clock"></i> Seg. a Sáb. 08h às 20h</p></div>
    </div>
    <div class="footer-bottom"><p>© 2026 FARMAURA. Todos os direitos reservados.</p></div>
</footer>
<script src="../produtos.js"></script>
</body>
</html>
