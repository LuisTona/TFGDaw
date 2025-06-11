const logForm = document.getElementById("logFormulario");
const error = document.getElementById("errorLog");
if (logForm) {
  logForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(logForm);
    const data = Object.fromEntries(formData);
    let options = {
      method: "POST",
      mode: "cors",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    };
    fetch("./php/login.php", options).then((res) => {
      if (res.status === 200) {
        fetch("./php/jwt.php", options)
          .then((res) => {
            if (res.status === 200) {
              return res.json();
            }
          })
          .then((data) => {
            if (data) {
              localStorage.setItem("token", data.codificada);
              localStorage.setItem("user", data.user);
              window.location.href = "./html/home.html";
            }
          });
      } else {
        error.style.display = "block";
      }
    });
  });
}
