const nb = document.getElementById("main-navbar");
const t = document.getElementById("navbar-toggle");
const img = document.querySelector("#navbar-toggle img")

t.addEventListener("click", () => {
  if (nb.style.display == "flex") {
    nb.style.display = "none";
    img.src = "/img/navbar-toggle.svg";
  }
  else {
    nb.style.display = "flex";
    img.src = "/img/navbar-toggle-close.svg";
  }
})