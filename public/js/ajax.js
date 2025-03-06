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
        .catch(error => console.error('Error:', error));
    });
});


