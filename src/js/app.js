// Variables para ubicar la sección
let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

// Objeto de citas que se irá llenando con el nombre del usuario, la fecha y hora que elija y los servicios que seleccione.
const cita = {
    id: "",
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
    mostrarSeccion(); // Es mandada a llamar por la función "tabs" para mostrar la sección correspondiente.
    botonesPaginacion(); // Define el comportamiento de los botones de paginación de la sección.
    paginaAnterior();
    paginaSiguiente();

    consultarApi(); // Consultar a la base de datos conectando con el back-end (PHP) para obtener los servicios y mostrarlos en pantalla.
    
    // Llenado del objeto de Cita que enviaremos al servidor.
    idCliente();
    nombreCLiente(); // Guardar el nombre del usuario en el objeto de citas.
    seleccionarFecha(); // Guardar la fecha que el usuario elija en el objeto de citas
    seleccionarHora(); // Guardar la hora que el usuario elija en el objeto de citas.

    mostrarResumen(); // Mostrar el resumen de la cita.
}

/* En un principio, ocultamos las 3 secciones de la página con código CSS. La clase "mostrar" contiene "display: block". 
Para visibilizar cada sección nos basaremos en la variable "paso" definida en el top del código para mostrar solo una sección 
por vez. */
function mostrarSeccion() {
    /* Para no recibir errores en consola, nos aseguramos que la app no intente eliminar la sección si todavía no
    contiene la clase "mostrar"*/
    const seccionAnterior = document.querySelector(".mostrar");
    if (seccionAnterior){
        seccionAnterior.classList.remove("mostrar");
    } 

    /* Seleccionamos la sección actual a través de su id ("paso-1"/"paso-2"/"paso-3"). Para especificar el paso
    nos ayudamos de la variabe que definimos al principio del código ("paso") que por default contiene 1.*/
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add("mostrar");

    // RESALTAR EL TAB ACTUAL
    const tabAnterior = document.querySelector(".actual");
    if(tabAnterior) {
        tabAnterior.classList.remove("actual");
    }
    const tabActual = document.querySelector(`[data-paso="${paso}"]`);
    tabActual.classList.add("actual");
} 

// Con esta función iteramos sobre los botones para reasignar la variable "paso" con el fin de ubicarnos y mostrar la sección correspondiente

function tabs() {
    const botones = document.querySelectorAll('.tabs button')

    botones.forEach(boton => {
        boton.addEventListener("click", function(e) {
            paso = parseInt(e.target.dataset.paso); // Reasignamos la variable con el paso actual.
             
            mostrarSeccion(); // Mostrar las secciones según el tab.
            botonesPaginacion(); //Configuración de los botones del paginador.
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
// Una vez definido el comportamiento de la paginación, consultamos al back-end para recibir los servicios desde la DB.
async function consultarApi() {

    try {
        const url = "/api/servicios";
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);
    } catch (error) {
        
    }
}
// Mandada a llamar por "consultarApi()". Recibimos el listado de servicios a través de la api.
function mostrarServicios(servicios) {
    
    servicios.forEach( servicio => {
        // Destruturing al arreglo que recibimos de seleccionarServicio().
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
    const {servicios} = cita; // Extraemos el arreglo del objeto de Citas (top del código).
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

function idCliente() {
    cita.id = document.querySelector("#usuarioId").value;
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

    // Destructuring al objeto de cita con la información completa ya disponible.
    const { nombre, fecha, hora, servicios} = cita;

    // Le damos un heading a la parte donde se muestra que servicio fueron seleccionados
    const headingServicios = document.createElement("H3");
    headingServicios.textContent = "Resumen de Servicios";
    resumen.appendChild(headingServicios);
    
    // Iteramos el arreglo con los servicios y lo insertamos en la página.
    servicios.forEach(servicio=> {
        const {id, nombre, precio} = servicio;
        // Div contenedor del servicio.
        const servicioContenedor = document.createElement("DIV");
        servicioContenedor.classList.add("servicio-contenedor");

        // Nombre y precio del servicio
        const textoServicio = document.createElement("P");
        textoServicio.innerHTML = `<span>Nombre:</span> ${nombre}`;
        const precioServicio = document.createElement("P");
        precioServicio.innerHTML = `<span>Precio</span> ${precio}`;

        // Armamos el div con la información del servicio insertando nombre y precio en servicioContenedor
        servicioContenedor.appendChild(textoServicio);
        servicioContenedor.appendChild(precioServicio);
        // Lo insertamos en la página de resumen
        resumen.appendChild(servicioContenedor);
    })

    // Le damos un heading a la parte donde se muestran los datos del usuario que creó la cita.
    const headingCita = document.createElement("H3");
    headingCita.textContent = "Resumen de Cita";
    resumen.appendChild(headingCita);

    // Obtenemos la fecha formateada al español.
    formatearFecha(fecha);

    // Insertar nombre del cliente, fecha y hora de la cita.
    const nombreCita = document.createElement("P")
    nombreCita.innerHTML = `<span>Nombre:</span> ${nombre}`;
    const fechaCita = document.createElement("P");
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;
    const horaCita = document.createElement("P");
    horaCita.innerHTML = `<span>Hora:</span> ${hora}`;

    // Boton para reservar cita
    const botonReserva = document.createElement("BUTTON");
    botonReserva.classList.add("boton");
    botonReserva.textContent = "Reservar Cita"
    botonReserva.onclick = reservarCita;

    resumen.appendChild(nombreCita);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(botonReserva);
}

async function reservarCita() {

    const { nombre, fecha, hora, servicios, id } = cita;
    const idServicio = servicios.map(servicio => servicio.id);

    datos = new FormData();
    datos.append("usuarioId", id);
    datos.append("nombre", nombre);
    datos.append("fecha", fecha);
    datos.append("hora", hora);
    datos.append("servicios", idServicio);
    
    try {
        // Peticion
        const url = "/api/cita";

        const respuesta = await fetch(url, {
            method: "POST",
            body: datos
        });

        const resultado = await respuesta.json();

        if(resultado.resultado) {
            Swal.fire({
                icon: 'success',
                title: 'Cita Creada',
                text: 'Tu cita fue asignada correctamente',
                button: "Ok"
            }).then( () => {
                window.location.reload();
            })
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error al crear la cita',
            text: 'Intente de nuevo más tarde',
            button: "Ok"
        })
    }
}

function formatearFecha(fecha) {
    // Formatear fecha a español
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() +2;
    const year = fechaObj.getFullYear();

    // Pasamos la fecha a UTC;
    const fechaUTC = new Date(Date.UTC(year, mes, dia));

    // Formateamos la fecha que pasamos a UTC
    const opciones = {weekday: "long", year: "numeric", month: "long", day: "numeric"};
    fechaFormateada = fechaUTC.toLocaleDateString("Es-AR", opciones);    
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

