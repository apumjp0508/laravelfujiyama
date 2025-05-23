
$(document).ready(function () {
    // 初期化：数量が1のときは、−ボタン非表示、削除ボタン表示
    $('.form-product').each(function () {
        const $row = $(this);
        const $form = $row.closest('form');
        const $input = $form.find('input[name="qty"]');
        let qty=$input.val();
        const $decrementBtn = $form.find('.decrement-btn');
        const $deleteBtn = $row.find('.delete-form .delete-btn');
        if (qty == 1) {
            $decrementBtn.hide();
            $deleteBtn.show();
        } else {
            $decrementBtn.show();
            $deleteBtn.hide();
        }
    });
    $('.number-change').on('click', function () {
        const $btn = $(this);
        const $form = $btn.closest('form');
        const $input = $form.find('input[name="qty"]');
        const $productIdInput = $form.find('input[name="product_id"]');
        const updateUrl = $form.find('#cart-update-url').val();
        const token = $('meta[name="csrf-token"]').attr('content');
        

        let qty = parseInt($input.val(), 10);
        const productId = $productIdInput.val();
        
        if ($btn.data('role') === 'increment') {
            qty++;
        } else if ($btn.data('role') === 'decrement' && qty > 0) {
            qty--;
        }

        $input.val(qty);

    // ボタン切り替え処理
        const $decrementBtn = $form.find('.decrement-btn');
        const $deleteBtn = $form.siblings('.delete-form').find('.delete-btn');

        if (qty === 1) {
            $decrementBtn.hide();       // − ボタン隠す
            $deleteBtn.show();          // 削除ボタン表示
        } else {
            $decrementBtn.show();
            $deleteBtn.hide();
        }


        

        $.ajax({
            url: updateUrl,
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

