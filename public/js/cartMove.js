const cartBtn=document.getElementById('cartBtn');

cartBtn.addEventListener('click',(event)=>{
    cartBtn.style.backgroundColor='green';
    cartBtn.innerHTML='カートに追加しました';
   

    setTimeout(()=>{
        cartBtn.style.backgroundColor='blue';
        cartBtn.innerHTML='カートに追加する';
    },2000)
})
