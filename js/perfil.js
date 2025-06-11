const infoUsuario = document.getElementById("infoUsuario");
const info = {
  user: localStorage.getItem("user"),
};
const options = {
  method: "POST",
  mode: "cors",
  headers: {
    "Content-Type": "application/json",
  },
  body: JSON.stringify(info),
};
function infoUser() {
  fetch("../php/getInfoUser.php", options)
    .then((res) => {
      if (res.status === 200) {
        return res.json();
      }
    })
    .then((data) => {
      let imagen = document.getElementById("imageBanner");
      let nameUser = document.getElementById("nameUser");
      document.getElementById("usuario").innerHTML = data.name;
      document.getElementById("mail").innerHTML = data.email;
      imagen.src = data.imagenUser;
      nameUser.textContent = data.name;
    });
}

infoUser();
