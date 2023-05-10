let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

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
    tabs(); // Cambia la sección cuando se presionen los tabs (/citas).
    mostrarSeccion(); // Es mandada a llamar por la función "tabs".
    botonesPaginacion();
    paginaAnterior();
    paginaSiguiente();
    consultarApi(); // Consultar a la base de datos conectando con el back-end (PHP).
}

function mostrarSeccion() {
    // Primero eliminamos la sección actual
    const seccionAnterior = document.querySelector(".mostrar");
    /* Para no recibir errores en consola, mos aseguramos que la app no intente eliminar la sección si todavía no
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
    const botones = document.querySelectorAll('.tabs button')

    botones.forEach(boton => {
        boton.addEventListener("click", function(e) {
            paso = parseInt(e.target.dataset.paso);
             
            mostrarSeccion();
            botonesPaginacion();
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