function logout(){
    if(confirm("Are you sure?")) {
        Utils.clear_cache();
        window.location=".";
    }
}