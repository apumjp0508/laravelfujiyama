document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll(".checkbox");
    const submitBtn = document.getElementById("submitBtn");
    const maxSelection = 4;

    function updateButtonState() {
        const checkedCount = document.querySelectorAll(".checkbox:checked").length;
        if (checkedCount >= maxSelection) {
            submitBtn.style.display = 'inline';
            submitBtn.disabled = false;
        } else {
            submitBtn.style.display = 'none';
            submitBtn.disabled = true;
        }
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function () {
            const checkedCount = document.querySelectorAll(".checkbox:checked").length;

            // 制限超えてチェックしようとしたら元に戻す
            if (checkedCount > maxSelection) {
                this.checked = false;
                return;
            }

            updateButtonState(); // ボタン表示状態の更新
        });
    });

    updateButtonState(); // 初期状態の更新
});
