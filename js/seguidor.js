const listaHabitos = document.getElementById("listaHabitos");
const seguirUser = document.getElementById("seguirUsuario");
checkUser();

let info = {
  user: localStorage.getItem("search"),
};
const options = {
  method: "POST",
  mode: "cors",
  headers: {
    "Content-Type": "application/json",
  },
  body: JSON.stringify(info),
};

window.addEventListener("DOMContentLoaded", () => {
  getHabitos();
});

function checkUser() {
  if (localStorage.getItem("user") == localStorage.getItem("search")) {
    window.location.href = "../html/habitos.html";
  } else {
    seguirUsuario("comprobar");
  }
}
function getHabitos() {
  fetch("../php/getHabitos.php", options)
    .then((res) => {
      if (res.status === 200) {
        return res.json();
      }
    })
    .then((data) => {
      render(data);
    });
}

function render(data) {
  infoUser();

  data.forEach((element) => {
    let habito = createElements("div", "", "habitoMostrado");
    habito.append(createHabitoElements(element));
    listaHabitos.append(habito);
  });
}

function createElements(element, id, clase) {
  let elemento = document.createElement(`${element}`);
  id ? elemento.setAttribute("id", id) : "";
  clase ? elemento.classList.add(clase) : "";
  return elemento;
}

function createHabitoElements(habito) {
  let habitoMostrado = createElements("div", "", "habitoMostrado");

  let insigniaHabito = createElements("div", "", "insigniaHabito");
  let imgHabito = createElements("img", "", "");
  let span = createElements("span", "", "");
  const nivel = habito.url_imagen.match(/lvl(\d+)/);
  span.textContent = habito.habitoName + ". Nivel: " + nivel[1];
  imgHabito.src = habito.url_imagen;
  imgHabito.alt = "nivel";

  insigniaHabito.append(imgHabito);
  insigniaHabito.append(span);

  habitoMostrado.append(insigniaHabito);
  return habitoMostrado;
}

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
      imagen.src = data.imagenUser;
      nameUser.textContent = data.name;
    });
}

function seguirUsuario(funcion) {
  const infoUser = {
    function: funcion,
    user: localStorage.getItem("user"),
    seguir: localStorage.getItem("search"),
  };
  const options = {
    method: "POST",
    mode: "cors",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(infoUser),
  };
  fetch("../php/seguirUsuario.php", options).then((res) => {
    if (res.status === 201) {
      seguirUser.textContent = "Siguiendo";
    } else {
      seguirUser.textContent = "Seguir";
    }
  });
}

seguirUser.addEventListener("click", () => {
  seguirUsuario("seguir");
});
