document.querySelectorAll('.close-form-btn').forEach(btn => {
    btn.addEventListener('click', function(){
        if (confirm("Are you sure you want to leave?")){
       window.location.href = "dashboard.php";
        }
    }); 
});
