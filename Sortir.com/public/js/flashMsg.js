let close = document.getElementById("close-flash");
close && close.addEventListener("click", function(){
    let fade = setInterval(function () {
        if (!close.style.opacity) {
            close.style.opacity = 1;
        }
        if (close.style.opacity > 0) {
            close.style.opacity -= 0.1;
        }
        else {
            clearInterval(fade);
        }
        close.style.display = "none";
    }, 200);
});