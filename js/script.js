// Script general para la comprobacion del loggeo en todas las paginas y navegacion de la web

// Comprobar si ya se ha logeado
document.addEventListener("DOMContentLoaded", () => {
  const token = localStorage.getItem("token");
  if (!token) {
    if (!window.location.href.includes("habitsgo/index.html")) {
      window.location.href = "./index.html";
    }
  } else {
    if (!window.location.href.includes("habitsgo/html")) {
      window.location.href = "./html/home.html";
    }
  }
});

const aside = document.getElementById("aside");
aside.addEventListener("click", (e) => {
  if (!e.target.classList.contains("asideContent")) {
    selectPage(e.target.closest(".asideContent").id);
  } else {
    selectPage(e.target.id);
  }
});
// funcion para navegar entre las paginas
function selectPage(id) {
  if (id == "logout") {
    localStorage.removeItem("token");
    window.location.href = "../index.html";
  } else if (id == "buscar") {
    const items = document.getElementsByClassName("identificadorAside");
    let aside = document.getElementById("aside");
    let section = document.getElementById("searchSection");
    if (!aside.classList.contains("search")) {
      for (let item of items) {
        item.style.display = "none";
      }
    } else {
      for (let item of items) {
        item.style.display = "block";
      }
    }
    aside.classList.toggle("search");
    section.classList.toggle("searchSection");
  } else {
    window.location.href = `../html/${id}.html`;
  }
}

const search = document.getElementById("searchInput");
const lupaSearch = document.getElementById("btnSearch");
const userEncontrado = document.getElementById("userEncontrado");

search.addEventListener("submit", (e) => {
  e.preventDefault();
  searchUser();
});

lupaSearch.addEventListener("click", (e) => {
  e.preventDefault();
  searchUser();
});

userEncontrado.addEventListener("click", (e) => {
  if (localStorage.getItem("search")) {
    localStorage.removeItem("search");
  }
  localStorage.setItem("search", e.target.getAttribute("iduser"));
  window.location.href = "../html/seguirUser.html";
});

function searchUser() {
  const input = document.getElementById("searchBar");
  const info = {
    user: input.value,
  };
  const options = {
    method: "POST",
    mode: "cors",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(info),
  };
  fetch("../php/getInfoUser.php", options)
    .then((res) => {
      if (res.status === 200) {
        return res.json();
      }
    })
    .then((data) => {
      const div = document.createElement("div");
      div.classList.add("userEncontrado");
      const image = document.createElement("img");
      image.src = data.imagenUser;
      const span = document.createElement("span");
      span.textContent = data.name;
      userEncontrado.innerHTML = "";
      div.append(image);
      div.append(span);
      div.setAttribute("idUser", data.name);
      userEncontrado.append(div);
      userEncontrado.style.display = "flex";
    });
}
