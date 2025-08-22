document.addEventListener('DOMContentLoaded', function () {
    const cartBtn = document.getElementById('cartBtn');
    if (cartBtn) {
        // 元の背景色を保存
        const originalBackgroundColor = cartBtn.style.backgroundColor || 'white';
        const originalText = cartBtn.innerHTML;
        
        cartBtn.addEventListener('click', () => {
            // ボタンの背景色を変更
            cartBtn.style.backgroundColor = 'rgb(231, 108, 108)';
            cartBtn.innerHTML = 'カートに追加しました';

            // 1.5秒後に元の状態に戻す
            setTimeout(() => {
                cartBtn.style.backgroundColor = originalBackgroundColor;
                cartBtn.innerHTML = originalText;
            }, 1500);
        });
    }
});
