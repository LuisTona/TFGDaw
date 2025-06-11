const listaHabitos = document.getElementById("listaHabitos");
const dialog = document.getElementById("myDialog");
const startHabito = document.getElementById("startHabito");

let infoHabitos;
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

window.addEventListener("DOMContentLoaded", () => {
  getHabitos();
});

function getHabitos() {
  fetch("../php/getHabitos.php", options)
    .then((res) => {
      if (res.status === 200) {
        return res.json();
      }
    })
    .then((data) => {
      infoHabitos = data;
      render(data);
    });
}

function render(data) {
  infoUser();

  data.forEach((element) => {
    //Donde va todo el contenido de los habitos
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
  let btnHabitos = createElements("div", "", "btnHabitos");
  let borrar = createElements("button", "delete", "btn");
  let start = createElements("button", "start", "btn");
  imgHabito.src = habito.url_imagen;
  imgHabito.alt = "nivel";
  span.textContent = `Has conseguido completar el habito propuesto de ${habito.habitoName},
  ${habito.nivel} veces`;

  borrar.textContent = "Eliminar habito";
  start.textContent = "Realizar habito";
  borrar.setAttribute(`idHabito`, habito.id);
  start.setAttribute(`idHabito`, habito.id);

  btnHabitos.append(start);
  btnHabitos.append(borrar);
  insigniaHabito.append(imgHabito);
  insigniaHabito.append(span);
  insigniaHabito.append(btnHabitos);

  let infoProgreso = createElements("div", "", "infoProgreso");
  let progress = createElements("div", "", "progress");
  let progressBar = createElements("div", "", "progress-bar");
  span = createElements("span", "", "");
  span.textContent = `${habito.nivel} ${
    habito.experiencia == null ? "" : "/" + habito.experiencia
  }`;

  let p = createElements("p", "", "");
  progress.append(progressBar);
  if (habito.experiencia != null) {
    progressBar.style.width = `${(habito.nivel / habito.experiencia) * 100}%`;
    p.textContent = "Estas progresando con el habito, sigue asi!";
  } else {
    progressBar.style.width = "100%";
    p.textContent = "HAS LLEGADO AL MAXIMO NIVEL INCREIBLE SIGUE ASI!";
  }

  infoProgreso.append(progress);
  infoProgreso.append(span);

  habitoMostrado.append(insigniaHabito);
  habitoMostrado.append(infoProgreso);
  habitoMostrado.append(p);

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
async function getTiempo(id) {
  let tiempo = [];
  let infoPararHabito = {
    function: "get",
    id: id,
  };
  const options = {
    method: "POST",
    mode: "cors",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(infoPararHabito),
  };
  return fetch("../php/actualizarTiempo.php", options).then((res) => {
    if (res.status === 200) {
      return res.json();
    }
  });
}
async function temporizadorHabito(proceso, id) {
  const display = document.getElementById("temporizador");
  const btnStart = document.getElementById("ready");
  const btnExit = document.getElementById("exit");
  const btnEnd = document.getElementById("endHabito");

  btnStart.style.display = "block";
  btnExit.style.display = "block";
  btnEnd.style.display = "none";

  let habitoCompletado = false;
  let habitoTiempo = await getTiempo(id);
  habitoTiempo = habitoTiempo[0];
  let tiempoRestante = habitoTiempo.duracion * 60;
  if (
    habitoTiempo.tiempoRealizado != tiempoRestante &&
    habitoTiempo.realizadoHoy != 0
  ) {
    tiempoRestante = habitoTiempo.tiempoRealizado;
  }
  actualizarDisplay();

  let temporizador;
  btnStart.onclick = comenzarTemporizador;
  btnExit.onclick = salirTemporizador;
  btnEnd.onclick = () => {
    startHabito.close();
  };

  function actualizarDisplay() {
    const minutos = Math.floor(tiempoRestante / 60);
    const segundos = tiempoRestante % 60;
    display.textContent = `${minutos.toString().padStart(2, "0")}:${segundos
      .toString()
      .padStart(2, "0")}`;
  }

  function comenzarTemporizador() {
    if (temporizador) return; // Evita crear múltiples intervalos

    temporizador = setInterval(() => {
      if (tiempoRestante > 0) {
        tiempoRestante--;
        actualizarDisplay();
      } else {
        btnStart.style.display = "none";
        btnExit.style.display = "none";
        btnEnd.style.display = "block";
        clearInterval(temporizador);
        temporizador = null;
        completarHabito();
      }
    }, 1000);
  }

  function salirTemporizador() {
    let infoPararHabito = {
      function: "update",
      id: id,
      tiempoRestante: tiempoRestante,
    };
    const options = {
      method: "POST",
      mode: "cors",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(infoPararHabito),
    };
    fetch("../php/actualizarTiempo.php", options).then((res) => {
      if (res.status === 200) {
        clearInterval(temporizador);
        temporizador = null;
        startHabito.close();
        actualizarDisplay();
      }
    });
  }

  function completarHabito() {
    if (!habitoCompletado) {
      habitoCompletado = true;
      let infoPararHabito = {
        function: "updateCompletoDia",
        id: id,
        realizado: +habitoTiempo.realizadoFrecuencia + 1,
      };
      const options = {
        method: "POST",
        mode: "cors",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(infoPararHabito),
      };
      fetch("../php/actualizarTiempo.php", options).then((res) => {
        if (res.status === 200) {
          actualizarDisplay();
        }
      });
    }
  }
}

listaHabitos.addEventListener("click", (e) => {
  if (e.target.getAttribute("id") == "delete") {
    let id = {
      id: e.target.getAttribute("idHabito"),
    };
    const options = {
      method: "POST",
      mode: "cors",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(id),
    };
    if (confirm("¿Esta seguro que quiere eliminarlo?")) {
      fetch("../php/deletePublicacion.php", options).then((res) => {
        if (res.status === 200) {
          dialog.showModal();
        }
      });
    }
  } else if (e.target.getAttribute("id") == "start") {
    startHabito.showModal();
    temporizadorHabito("start", e.target.getAttribute("idHabito"));
  }
});

dialog.addEventListener("click", (e) => {
  const rect = dialog.getBoundingClientRect();
  const isInDialog =
    e.clientX >= rect.left &&
    e.clientX <= rect.right &&
    e.clientY >= rect.top &&
    e.clientY <= rect.bottom;

  if (!isInDialog || e.target.getAttribute("id") == "cerrar") {
    window.location.reload();
    dialog.close();
  }
});
