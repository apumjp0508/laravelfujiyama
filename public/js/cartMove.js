document.addEventListener('DOMContentLoaded', function () {
    const cartBtn = document.getElementById('cartBtn');
    console.log("aaa");
    if (cartBtn) {
        console.log('nnn');
        cartBtn.addEventListener('click', () => {
            cartBtn.style.backgroundColor = 'green';
            cartBtn.innerHTML = 'カートに追加しました';
            console.log('カートに追加しました');

            setTimeout(() => {
                cartBtn.style.backgroundColor = 'blue';
                cartBtn.innerHTML = 'カートに追加する';
            }, 1500);
        });
    }
});
