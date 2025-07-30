document.addEventListener('DOMContentLoaded', () => {
    const cartForm = document.getElementById('cartForm');

    cartForm.addEventListener('submit', function(event) {
        event.preventDefault(); // フォームのデフォルト送信（ページ遷移）を防ぐ

        const formData = new FormData(this); // フォームデータを取得

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message || 'カートに追加しました！');
                // カート数を更新する場合
                if (data.cart_count) {
                    const cartCountElement = document.querySelector('.cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = data.cart_count;
                    }
                }
            } else {
                alert('エラーが発生しました: ' + (data.message || '不明なエラー'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('ネットワークエラーが発生しました。');
        });
    });
});


