document.addEventListener("DOMContentLoaded", function () {
 const busca=document.getElementById("campoBusca"), cards=[...document.querySelectorAll(".cartao-produto")];
 const resultado=document.getElementById("resultadoBusca"), sem=document.getElementById("mensagemSemResultado");
 let categoria="";
 async function contador(){try{const r=await fetch("carrinho_contagem.php");const d=await r.json();document.querySelectorAll(".contadorCarrinhoTopo").forEach(e=>e.textContent=d.total_itens??0);}catch(e){}}
 function filtrar(){const termo=(busca?.value||"").toLocaleLowerCase("pt-BR").trim();let n=0;cards.forEach(c=>{const ok=(c.dataset.nome||"").toLocaleLowerCase("pt-BR").includes(termo)||(c.dataset.categoria||"").toLocaleLowerCase("pt-BR").includes(termo);const cat=!categoria||(c.dataset.categoria||"").toLocaleLowerCase("pt-BR")===categoria.toLocaleLowerCase("pt-BR");c.hidden=!(ok&&cat);if(ok&&cat)n++;});if(resultado)resultado.textContent=n+" produto(s)";if(sem)sem.hidden=n!==0;}
 busca?.addEventListener("input",filtrar);
 document.querySelectorAll("[data-category-filter]").forEach(b=>b.addEventListener("click",()=>{categoria=b.dataset.categoryFilter||"";document.querySelectorAll("[data-category-filter]").forEach(x=>x.classList.toggle("ativa",x===b));filtrar();}));
 cards.forEach(c=>{const menos=c.querySelector(".diminuir"),mais=c.querySelector(".aumentar"),q=c.querySelector(".quantidade"),btn=c.querySelector(".botao-carrinho"),estoque=Number(btn.dataset.estoque||0);
 menos?.addEventListener("click",()=>{if(Number(q.textContent)>1)q.textContent=Number(q.textContent)-1;});
 mais?.addEventListener("click",()=>{if(Number(q.textContent)<estoque)q.textContent=Number(q.textContent)+1;});
 btn.addEventListener("click",async()=>{btn.disabled=true;try{const body=new URLSearchParams({id:btn.dataset.id,quantidade:q.textContent});const r=await fetch("adicionar_carrinho.php",{method:"POST",headers:{"Content-Type":"application/x-www-form-urlencoded"},body});const d=await r.json();if(!r.ok||!d.ok)throw new Error(d.mensagem||"Não foi possível adicionar.");await contador();btn.innerHTML='<i class="fa-solid fa-check"></i> Adicionado';setTimeout(()=>btn.innerHTML='<i class="fa-solid fa-cart-plus"></i> Adicionar ao carrinho',1200);}catch(e){alert(e.message);}finally{btn.disabled=estoque<=0;}});
 if(estoque<=0){btn.disabled=true;btn.textContent="Sem estoque";mais.disabled=true;}
 });
 contador();filtrar();
});