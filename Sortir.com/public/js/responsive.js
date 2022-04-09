let nav = document.querySelector(".site-nav");
let burger = document.querySelector(".burger");
let noBurger = document.querySelector(
    ".cross"
);

burger.addEventListener("click", function () {
    nav.classList.add("burgerOn");
});

noBurger.addEventListener("click", function () {
    nav.classList.remove("burgerOn");
});