document.addEventListener('DOMContentLoaded', () => {
    console.log('ajax.js loaded - DOM ready');
    
    const cartForm = document.getElementById('cartForm');
    console.log('Cart form found:', cartForm);
    
    if (!cartForm) {
        console.error('Cart form not found! Check if element with id="cartForm" exists');
        return;
    }
    
    // CSRFトークンの確認
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    console.log('CSRF token meta element:', csrfToken);
    
    if (!csrfToken) {
        console.error('CSRF token meta element not found! Check if meta[name="csrf-token"] exists');
        return;
    }
    
    const csrfTokenValue = csrfToken.getAttribute('content');
    console.log('CSRF token value:', csrfTokenValue);
    
    if (!csrfTokenValue) {
        console.error('CSRF token value is empty! Check if {{ csrf_token() }} is working');
        return;
    }
    
    // フォームのaction属性の確認
    console.log('Form action:', cartForm.action);
    console.log('Form method:', cartForm.method);
    
    cartForm.addEventListener('submit', function(event) {
        event.preventDefault(); // フォームのデフォルト送信（ページ遷移）を防ぐ
        console.log('Form submit event triggered');
        
        const formData = new FormData(this); // フォームデータを取得
        
        // フォームデータの内容をログ出力
        console.log('Form data contents:');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
        
        // リクエストヘッダーの確認
        const headers = {
            'X-CSRF-TOKEN': csrfTokenValue
        };
        console.log('Request headers:', headers);
        
        console.log('Sending fetch request to:', this.action);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: headers
        })
        .then(response => {
            console.log('Response received:', response);
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            // レスポンスの内容を確認
            if (!response.ok) {
                console.error('Response not OK. Status:', response.status);
                // エラーレスポンスの内容を取得
                return response.text().then(text => {
                    console.error('Error response body:', text);
                    throw new Error(`HTTP ${response.status}: ${text}`);
                });
            }
            
            // レスポンスのContent-Typeを確認
            const contentType = response.headers.get('content-type');
            console.log('Response content-type:', contentType);
            
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                console.warn('Response is not JSON. Content-Type:', contentType);
                return response.text().then(text => {
                    console.log('Response text:', text);
                    // HTMLレスポンスの場合は、成功として扱う
                    return { success: true, message: 'カートに追加しました！' };
                });
            }
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success) {
                console.log('Success response received');
                alert(data.message || 'カートに追加しました！');
                // カート数を更新する場合
                if (data.cart_count) {
                    const cartCountElement = document.querySelector('.cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = data.cart_count;
                        console.log('Cart count updated to:', data.cart_count);
                    } else {
                        console.warn('Cart count element not found');
                    }
                }
            } else {
                console.error('Error response received:', data);
                alert('エラーが発生しました: ' + (data.message || '不明なエラー'));
            }
        })
        .catch(error => {
            console.error('Fetch error details:', error);
            console.error('Error name:', error.name);
            console.error('Error message:', error.message);
            console.error('Error stack:', error.stack);
            
            // エラーの種類に応じてメッセージを表示
            let errorMessage = 'ネットワークエラーが発生しました。';
            
            if (error.name === 'TypeError') {
                errorMessage = 'リクエストの送信に失敗しました。';
            } else if (error.message.includes('HTTP 419')) {
                errorMessage = 'CSRFトークンが無効です。ページを再読み込みしてください。';
            } else if (error.message.includes('HTTP 422')) {
                errorMessage = '入力データに問題があります。';
            } else if (error.message.includes('HTTP 500')) {
                errorMessage = 'サーバーエラーが発生しました。';
            }
            
            alert(errorMessage);
        });
    });
    
    console.log('Cart form event listener attached successfully');
});
