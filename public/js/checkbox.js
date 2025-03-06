document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll(".checkbox");
    const selectedCountText = document.getElementById("selectedCount");
    const submitBtn = document.getElementById("submitBtn");
    const maxSelection = 4;

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function () {
            let checkedCount = document.querySelectorAll(".checkbox:checked").length;
            
            if (checkedCount > maxSelection) {
                this.checked = false; // これ以上チェックできない
                return;
            }

            selectedCountText.textContent = `選択: ${checkedCount} / ${maxSelection}`;
            submitBtn.disabled = (checkedCount !== maxSelection);
        });
    });
});