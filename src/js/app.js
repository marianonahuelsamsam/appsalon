let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

// Objeto de citas que se irá llenando con el nombre del usuario, la fecha y hora que elija y los servicios que seleccione.
const cita = {
   nombre: "",
   fecha: "",
   hora: "",
   servicios: []
}

document.addEventListener("DOMContentLoaded", function() {
    iniciarApp();
})

function iniciarApp() {
    // Paginador
    tabs(); // Cambia la sección cuando se presionen los tabs (/citas).
    mostrarSeccion(); // Es mandada a llamar por la función "tabs".
    botonesPaginacion();
    paginaAnterior();
    paginaSiguiente();

    consultarApi(); // Consultar a la base de datos conectando con el back-end (PHP).

    nombreCLiente(); // Guardar el nombre del usuario en el objeto de citas.
    seleccionarFecha(); // Guardar la fecha que el usuario elija en el objeto de citas
    seleccionarHora(); // Guardar la hora que el usuario elija en el objeto de citas.

    mostrarResumen(); // MOstrar el resumen de la cita.
}

// Paginar las secciones.
function mostrarSeccion() {
    // Primero eliminamos la sección actual
    const seccionAnterior = document.querySelector(".mostrar");

    /* Para no recibir errores en consola, nos aseguramos que la app no intente eliminar la sección si todavía no
    contiene la clase "mostrar"*/
    if (seccionAnterior){
        seccionAnterior.classList.remove("mostrar");
    } 

    /* Seleccionamos la sección actual a través de su id ("paso-1"/"paso-2"/"paso-3"). Para especificar el paso
    nos ayudamos de la variabe que definimos al principio del código ("paso").*/
    const pasoSelector = `#paso-${paso}`;

    // Le agregamos la clase mostrar a la sección actual
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add("mostrar");

    // RESALTAR EL TAB ACTUAL
    // Buscamos el tab que tenga la clase "actual" y si existe lo eliminamos.
    const tabAnterior = document.querySelector(".actual");
    if(tabAnterior) {
        tabAnterior.classList.remove("actual");
    }
    const tabActual = document.querySelector(`[data-paso="${paso}"]`);
    tabActual.classList.add("actual");
} 

function tabs() {
    // Seleccionamos todos los botones.
    const botones = document.querySelectorAll('.tabs button')

    // Iteramos sobre los botones
    botones.forEach(boton => {
        boton.addEventListener("click", function(e) {
            paso = parseInt(e.target.dataset.paso); // Reasignamos la variable con el paso actual.
             
            mostrarSeccion(); // Mostrar las secciones según el tab.
            botonesPaginacion(); //COnfiguración de los botones del paginador.
        })
    })
}

function botonesPaginacion () {
    const paginaAnterior = document.querySelector("#anterior");
    const paginaSiguiente = document.querySelector("#siguiente");

    if (paso === 1) {
        paginaAnterior.classList.add("ocultar");
        paginaSiguiente.classList.remove("ocultar");
    }
    if (paso === 3) {
        paginaSiguiente.classList.add("ocultar");
        paginaAnterior.classList.remove("ocultar");

        mostrarResumen();
    }
    if (paso === 2) {
        paginaSiguiente.classList.remove("ocultar");
        paginaAnterior.classList.remove("ocultar");
    }

    mostrarSeccion();
}
function paginaAnterior() {
    paginaAnterior = document.querySelector("#anterior");

    paginaAnterior.addEventListener("click", function() {
        if (paso <= pasoInicial) return;
        paso--;

        botonesPaginacion();
    })
}
function paginaSiguiente() {
    paginaSiguiente = document.querySelector("#siguiente");

    paginaSiguiente.addEventListener("click", function() {
        if (paso >= pasoFinal) return;
        paso++;

        botonesPaginacion();
    })
}

async function consultarApi() {

    try {
        const url = "http://localhost:3000/api/servicios";
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);
    } catch (error) {
        
    }
    
}
// Mandada a llamar por "consultarApi()". Recibimos el listado de servicios a través de la api.
function mostrarServicios(servicios) {

    servicios.forEach( servicio => {

        const {id, nombre, precio} = servicio;
        // Creamos el párrafo para el nombre del servicio.
        const nombreServicio = document.createElement("P");
        nombreServicio.classList.add("nombre-servicio");
        nombreServicio.textContent = nombre;

        // Creamos el párrafo para el precio del servicio.
        const precioServicio = document.createElement("P");
        precioServicio.classList.add("precio-servicio");
        precioServicio.textContent = precio;

        // Creamos el div para el precio y nombre del servicio
        const divServicio = document.createElement("DIV");
        divServicio.classList.add("servicio");
        divServicio.dataset.idServicio = id;

        divServicio.appendChild(nombreServicio);
        divServicio.appendChild(precioServicio);
        divServicio.onclick = function() {
            seleccionarServicio(servicio);
        }

        // Conectamos el Div creado mediante JS con la sección que definimos con el id "servicios" en "views/cita/index.php".
        document.querySelector("#servicios").appendChild(divServicio);

    })

}

function seleccionarServicio(servicio) {
    const {servicios} = cita; // Extraemos el arreglo del objeto de Citas.
    const {id} = servicio; // Extraemos el id de el objeto servicio que nos retornó la función mostrarServicios().

    // Identificar el servicio al que se le da click.
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    // Comparamos si el servicio al que se le dio click ya está almacenado en el objeto "citas".
    if(servicios.some( agregado => agregado.id === id)) {
        // Deseleccionar
        // Creamos un nuevo arreglo unicamente con los servicios cuyo id sea distinto al almacenado en el objeto.
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        // Quitamos el estilo de selección
        divServicio.classList.remove("seleccionado");
    } else {
        // Seleccionar
        // Sincronizamos lo guardado en el objeto "citas" con el servico clickeado. 
        cita.servicios = [...servicios, servicio];
        // Agregamos el estilo de selección
        divServicio.classList.add("seleccionado");
    }
}

function nombreCLiente() {
    cita.nombre = document.querySelector("#nombre").value;
}

function seleccionarFecha() {
    const inputFecha = document.querySelector("#fecha");

    inputFecha.addEventListener("input", function(e) {
        const dia = new Date(e.target.value).getUTCDay();

        if (dia === 0 || dia === 6) {
            e.target.value = "";
            mostrarAlerta("Sábados y Domingos no disponibles", "error", ".formulario");
        } else {
            cita.fecha = e.target.value;
        }
    })
}

function seleccionarHora() {
    const inputHora = document.querySelector("#hora");
    inputHora.addEventListener("input", function(e) {
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];

        if (hora < 10 || hora > 18) {
            e.target.value = "";
            mostrarAlerta("Horario no disponible", "error", ".formulario");
        } else {
            cita.hora = e.target.value;
        }
    });
}

function mostrarResumen() {
    // Seleccionamos el div principal de la sección "resumen".
    const resumen = document.querySelector(".cita-resumen");

    /* Eliminamos el contenido previo (para quitar el alerta en caso de anteriormente haber ingresado a la sección sin 
    ingresar fecha ni hora)*/
    while(resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    // Mostrar el alerta en caso de que no exista fecha ni hora y detener el código.
    if (Object.values(cita).includes("") || cita.servicios.length === 0) {
        mostrarAlerta("Faltan datos o Servicios", "error", ".cita-resumen", false);

        return;
    } 

    // Destructuring al objeto de cita con la informaión completa ya disponible.
    const { nombre, fecha, hora, servicios} = cita;

    // Insertar nombre del cliente, fecha y hora de la cita.
    const nombreCita = document.createElement("P")
    nombreCita.innerHTML = `<span>Nombre:</span> ${nombre}`;
    const fechaCita = document.createElement("P");
    fechaCita.innerHTML = `<span>Fecha:</span> ${fecha}`;
    const horaCita = document.createElement("P");
    horaCita.innerHTML = `<span>Hora:</span> ${hora}`;
    resumen.appendChild(nombreCita);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);

    // Iteramos el arreglo con los servicios y lo insertamos en la página.
    servicios.forEach(servicio=> {
        const {id, nombre, precio} = servicio;
        // Div contenedor del servicio.
        const servicioContenedor = document.createElement("DIV");
        servicioContenedor.classList.add("servicio-contenedor");

        // Nombre y precio del servicio
        const textoServicio = document.createElement("P");
        textoServicio.innerHTML = `<span>Nombre:</span>: ${nombre}`;
        const precioServicio = document.createElement("P");
        precioServicio.innerHTML = `<span>Precio</span>: ${precio}`;

        // Armamos el div con la información del servicio insertando nombre y precio en servicioContenedor
        servicioContenedor.appendChild(textoServicio);
        servicioContenedor.appendChild(precioServicio);
        // Lo insertamos en la página de resumen
        resumen.appendChild(servicioContenedor);
    })

}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
    const alertaPrevia = document.querySelector(".alerta");
    if (alertaPrevia) {
        alertaPrevia.remove();
    };

    const alerta = document.createElement("DIV");
    alerta.textContent = mensaje;
    alerta.classList.add("alerta");
    alerta.classList.add(tipo);

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    if (desaparece === true) {
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }
}

