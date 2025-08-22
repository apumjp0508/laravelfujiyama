document.addEventListener('DOMContentLoaded', () => {
    const cartForm = document.getElementById('cartForm');
    
    if (!cartForm) {
        return;
    }
    
    // CSRFトークンの確認
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken || !csrfToken.getAttribute('content')) {
        return;
    }
    
    const csrfTokenValue = csrfToken.getAttribute('content');
    
    cartForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfTokenValue
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                return response.text().then(() => {
                    return { success: true, message: 'カートに追加しました！' };
                });
            }
        })
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
            let errorMessage = 'ネットワークエラーが発生しました。';
            
            if (error.message.includes('HTTP 419')) {
                errorMessage = 'CSRFトークンが無効です。ページを再読み込みしてください。';
            } else if (error.message.includes('HTTP 422')) {
                errorMessage = '入力データに問題があります。';
            } else if (error.message.includes('HTTP 500')) {
                errorMessage = 'サーバーエラーが発生しました。';
            }
            
            alert(errorMessage);
        });
    });
});
