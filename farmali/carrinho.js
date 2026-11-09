document.addEventListener("DOMContentLoaded", async () => {
 try { const r=await fetch("carrinho_contagem.php"); const d=await r.json(); document.querySelectorAll(".contadorCarrinhoTopo").forEach(e=>e.textContent=d.total_itens??0); } catch(e) {}
});