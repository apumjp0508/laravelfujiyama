document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll(".checkbox");
    const submitBtn = document.getElementById("submitBtn");
    const maxSelection = 4;
    let checkedCount = document.querySelectorAll(".checkbox:checked").length;
    submitBtn.disabled=true;


    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function () {
            if(checkbox.checked){
                checkedCount++;
            }else{
                checkedCount--;
            }

            if (checkedCount >= maxSelection) {
                submitBtn.style.display='inline';
            }
            
            if (checkedCount > maxSelection) {
                submitBtn.style.display='inline';
                this.checked = false;
                submitBtn.disabled=false; 
            }
        });
    });
    if(checkedCount < maxSelection){
        submitBtn.style.display='none';
    }
    
});