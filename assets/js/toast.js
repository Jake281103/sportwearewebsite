const toast = document.querySelector(".toasts");
(closeIcon = document.querySelector(".closes")),
  (progress = document.querySelector(".progress"));

let timer1, timer2;

function toastopen() {
  progress.classList.add("actives");

  timer1 = setTimeout(() => {
    toast.classList.remove("actives");
  }, 5000); //1s = 1000 milliseconds

  timer2 = setTimeout(() => {
    progress.classList.remove("actives");
  }, 5300);
};

if(toast.classList.contains("actives")) {
    toastopen();
}

closeIcon.addEventListener("click", () => {
  toast.classList.remove("actives");

  setTimeout(() => {
    progress.classList.remove("actives");
  }, 300);

  clearTimeout(timer1);
  clearTimeout(timer2);
});