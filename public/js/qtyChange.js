$(document).ready(function () {
    let updateUrl = $('#cart-update-url').val(); // ここでルートを取得

    $('number-change').on('click', function () {
        let qty = $('input[name="qty"]').val();
        let productId = $('input[name="qty"]').closest('form').find('input[name="product_id"]').val();
        let token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: updateUrl,  // ここが修正ポイントそのまま最初は文字列を埋め込んでいたからエラーになったらしい
            type: "POST",
            data: {
                _token: token,
                product_id: productId,
                qty: qty
            },
            success: function (response) {
                if (response.success) {
                    
                    $('#total-price-' + productId).text('￥' + response.product_total);
                    $('#cart-total').text('￥' + response.cart_total);
                    if (qty == 0) {
                        console.log('aaa');
                    }
                    
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAXエラー:", xhr.responseText); // 開発者コンソールに詳細を出力
            
                let errorMessage = "通信に失敗しました。";
                
                if (xhr.status === 400) {
                    errorMessage = "リクエストが不正です（400 Bad Request）。";
                } else if (xhr.status === 401) {
                    errorMessage = "認証が必要です（401 Unauthorized）。";
                } else if (xhr.status === 403) {
                    errorMessage = "アクセス権限がありません（403 Forbidden）。";
                } else if (xhr.status === 404) {
                    errorMessage = "リソースが見つかりません（404 Not Found）。";
                } else if (xhr.status === 500) {
                    errorMessage = "サーバーエラーが発生しました（500 Internal Server Error）。";
                } else if (xhr.status === 503) {
                    errorMessage = "サービスが利用できません（503 Service Unavailable）。";
                } else {
                    errorMessage = "エラーが発生しました。管理者に問い合わせてください。";
                }
            
                alert(errorMessage);
            }
        });
    });
});