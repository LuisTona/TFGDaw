const publicaciones = document.getElementById("content");
let pagina = 0;

window.addEventListener("DOMContentLoaded", () => {
  getPublicaciones();
});

function render(data) {
  if (data.length != 0) {
    document.getElementById("bienvenida").style.display = "none";
    data.forEach((element) => {
      let allPublicacion = createElemets("div", "allPublicacion", ""); //Donde va todo el contenido de una publicacion
      allPublicacion.append(createPublicationElements(element));
      // allPublicacion.append(createComentariosElements(element));
      publicaciones.append(allPublicacion);
    });
  } else {
    document.getElementById("bienvenida").style.display = "block";
  }
}

function createElemets(element, id, clase) {
  let elemento = document.createElement(`${element}`);
  id ? elemento.setAttribute("id", id) : "";
  clase ? elemento.classList.add(clase) : "";
  return elemento;
}

function createPublicationElements(e) {
  let publicacion = createElemets("div", "", "publicacion"); //Va la seccion de informacion de la publicacion

  //Informacion del usuario que publica
  let publicacionInfo = createElemets("div", "", "publicacionInfo");
  let userImage = createElemets("img", "", "userImage");
  let h3 = createElemets("h3", "", "");
  h3.textContent = e.autor_nombre;
  userImage.src = e.foto_perfil;
  userImage.alt = "userImage";
  publicacionInfo.appendChild(userImage);
  publicacionInfo.appendChild(h3);

  //Informacion del habito del usuario y su comentario
  let newLevel = createElemets("div", "", "newLevel");
  let h2 = createElemets("h2");
  let span = createElemets("span", "nombreHabito", "");
  let div = createElemets("div");
  let levelImage = createElemets("img", "", "");
  let comentarioUser = createElemets("div", "", "comentarioUser");
  let fecha = createElemets("p");

  if (esHoy(e.fecha)) {
    h2.textContent = "He empezado mi nuevo habito: ";
    span.textContent = e.nombre_habito;
    h2.append(span);
  }

  levelImage.src = e.imagen;
  comentarioUser.textContent = e.comentario;
  div.append(levelImage);
  div.append(comentarioUser);
  fecha.textContent = new Date(e.fecha.replace(" ", "T")).toLocaleString(
    "es-ES",
    {
      year: "numeric",
      month: "2-digit",
      day: "2-digit",
      hour: "2-digit",
      minute: "2-digit",
      hour12: false,
    }
  );
  newLevel.append(h2);
  newLevel.append(div);
  newLevel.append(fecha);

  // Juntamos ambas secciones y devolvemos el resultado
  publicacion.append(publicacionInfo);
  publicacion.append(newLevel);
  return publicacion;
}

// Creacion de los comentarios para posible implementacion de futuro
function createComentariosElements(e) {
  let comentarioSection = createElemets("div", "comentariosSection", "");

  let crearComentario = createElemets("div", "", "crearComentario");
  let userImage = createElemets("img", "", "userImage");
  let text = createElemets("textarea", "textComentario", "");
  let button = createElemets("button", "enviarComentario", "");
  button.setAttribute("idComent", e.id);
  button.textContent = "Enviar";

  userImage.src = e.foto_perfil;
  userImage.alt = "userImage";
  text.placeholder = "Añadir un comentario";

  crearComentario.append(userImage);
  crearComentario.append(text);
  crearComentario.append(button);
  createListener(button, "click");
  let comentariosPublicacion = createElemets(
    "div",
    "",
    "comentariosPublicacion"
  );

  e.comentarios.forEach((comentario) => {
    // creamos la caja para cada comentario
    let comentarios = createElemets("div", "", "comentarios");
    let userImage = createElemets("img", "", "userImage");
    userImage.src = e.foto_perfil;
    userImage.alt = "userImage";

    let divComentario = createElemets("div", "", "comentario");
    let h3 = createElemets("h3", "", "");
    let textoComentario = createElemets("div", "", "textoComentario");
    let fechaComentario = createElemets("div", "", "fechaComentario");
    // ponemos la informacion para cada comentario
    h3.textContent = e.autor_nombre;
    textoComentario.textContent = comentario.comentarioUser;
    fechaComentario.textContent = new Date(
      comentario.fecha.replace(" ", "T")
    ).toLocaleString("es-ES", {
      year: "numeric",
      month: "2-digit",
      day: "2-digit",
      hour: "2-digit",
      minute: "2-digit",
      hour12: false,
    });

    divComentario.append(h3);
    divComentario.append(textoComentario);
    divComentario.append(fechaComentario);

    // asignamos la informacion de cada comentario a la caja
    comentarios.append(userImage);
    comentarios.append(divComentario);
    // introducimos cada caja a la seccion de los comentarios
    comentariosPublicacion.append(comentarios);
  });
  comentarioSection.append(crearComentario);
  comentarioSection.append(comentariosPublicacion);
  // return comentarioSection;
}

function getPublicaciones() {
  let data = {
    user: localStorage.getItem("user"),
    pagina: pagina,
  };
  const options = {
    method: "POST",
    mode: "cors",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  };
  fetch("../php/getPublicaciones.php", options)
    .then((res) => {
      if (res.status === 200) {
        return res.json();
      }
    })
    .then((data) => {
      render(data);
    });
}

function esHoy() {
  const fechaStr = "2025-06-09 02:35:41";
  const fecha = new Date(fechaStr.replace(" ", "T"));

  const hoy = new Date();

  // Comparar año, mes y día
  const esHoy =
    fecha.getFullYear() === hoy.getFullYear() &&
    fecha.getMonth() === hoy.getMonth() &&
    fecha.getDate() === hoy.getDate();
  return esHoy;
}

function createListener(item, metodo) {
  item.addEventListener(metodo, () => {
    const textoComentario = document.getElementById("textComentario");
    if (textoComentario.trim()) {
      console.log("hola");
      // let data={
      //   'id_publicacion': item.getAttribute('idComent'),
      //   'comentario':textoComentario
      // }
      // fetch();
    }
  });
}
