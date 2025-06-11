const form = document.getElementById("form");

const registerForm = document.getElementById("registerForm");
const loginForm = document.getElementById("logForm");

form.addEventListener("click", (e) => {
  if (e.target.id === "btnRegisterForm") {
    registerForm.classList.toggle("noneDisplay");
    loginForm.classList.toggle("noneDisplay");
  } else if (e.target.id === "btnLoginForm") {
    registerForm.classList.toggle("noneDisplay");
    loginForm.classList.toggle("noneDisplay");
  }
});
