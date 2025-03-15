document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll(".checkbox");
    const selectedCountText = document.getElementById("selectedCount");
    const submitBtn = document.getElementById("submitBtn");
    const maxSelection = 4;
    let checkedCount = document.querySelectorAll(".checkbox:checked").length;
    submitBtn.disabled=true;

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function () {
            checkedCount+=1;
            
            console.log(checkedCount);
            if (checkedCount > maxSelection) {
                this.checked = false;
                submitBtn.disabled=false; // これ以上チェックできない
                return;
            }
        });
    });

    
});