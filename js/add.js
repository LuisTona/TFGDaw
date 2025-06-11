const steps = document.querySelectorAll(".step");
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");
const progressBar = document.getElementById("progressBar");
const endBtn = document.getElementById("myDialog");
const publicar = document.getElementById("publicar");
const dialog = document.getElementById("publicacion");
const volver = document.getElementById("volver");

let stepActual = document.getElementById("stepActual");
let currentStep = 0;
let auxHabito;
let auxPublicacion;
document.getElementById("maxSteps").textContent = steps.length;

function updateForm() {
  steps.forEach((step, i) => {
    step.classList.toggle("active", i === currentStep);
  });

  progressBar.style.width = `${((currentStep + 1) / steps.length) * 100}%`;

  stepActual.textContent = currentStep + 1;

  prevBtn.disabled = currentStep === 0;
  if (currentStep === steps.length - 1) {
    nextBtn.textContent = "Siguente";
    nextBtn.style.display = "none";
    endBtn.style.display = "block";
  } else {
    nextBtn.style.display = "block";
    endBtn.style.display = "none";
  }
  currentStep === steps.length - 1 ? "Finalizar" : "Siguiente";
}

function selectHabito() {
  fetch("../php/añadirHabito.php")
    .then((res) => {
      if (res.status === 200) {
        return res.json();
      } else {
        if (
          confirm(
            "Ha ocurrido un problema con el servidor, vuelve a intentarlo"
          )
        ) {
          window.location.reload();
        }
      }
    })
    .then((habito) => {
      let select = document.getElementById("habitSelect");
      habito.forEach((hab) => {
        let option = document.createElement("option");
        option.value = hab.id;
        option.textContent = hab.nombre;
        select.appendChild(option);
      });
    });
}

function guardarHabito() {
  const form = document.getElementById("habitForm");
  const formData = new FormData(form);
  let data = Object.fromEntries(formData);
  data.user = localStorage.getItem("user");
  aux = data.habito;
  auxPublicacion;
  const options = {
    method: "POST",
    mode: "cors",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  };
  fetch("../php/añadirHabito.php", options).then((res) => {
    if (res.status === 200) {
      dialog.showModal();
    } else {
      let error = document.getElementById("error");
      error.style.display = "block";
    }
  });
}

function guardarPublicacion() {
  const comentario = document.getElementById("comentario").value;
  const dialogForm = document.getElementById("formDialog");
  const data = {
    comentario: comentario,
    user: localStorage.getItem("user"),
    id_imagen: 1,
    id_habito: aux,
    id_publicacion_habito: auxPublicacion,
  };

  const options = {
    method: "POST",
    mode: "cors",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  };

  fetch("../php/publicacion.php", options).then((res) => {
    if (res.status === 200) {
      dialogForm.classList.toggle("oculto");
      document.getElementById("correcto").classList.toggle("oculto");
    } else {
      dialogForm.classList.toggle("oculto");
      document.getElementById("incorrecto").classList.toggle("oculto");
    }
  });
}

nextBtn.addEventListener("click", () => {
  const currentInputs = steps[currentStep].querySelectorAll("input, select");
  for (let input of currentInputs) {
    if (!input.checkValidity()) {
      input.reportValidity();
      return;
    }
  }

  if (currentStep < steps.length - 1) {
    currentStep++;
    updateForm();
  }
});

prevBtn.addEventListener("click", () => {
  if (currentStep > 0) {
    currentStep--;
    updateForm();
  }
});

endBtn.addEventListener("click", () => {
  guardarHabito();
});

publicar.addEventListener("click", () => {
  guardarPublicacion();
});

dialog.addEventListener("click", (e) => {
  const rect = dialog.getBoundingClientRect();
  const isInDialog =
    e.clientX >= rect.left &&
    e.clientX <= rect.right &&
    e.clientY >= rect.top &&
    e.clientY <= rect.bottom;

  if (!isInDialog) {
    dialog.close();
  }
});

volver.addEventListener("click", () => {
  window.location.href = "../html/home.html";
});

selectHabito();
updateForm();
