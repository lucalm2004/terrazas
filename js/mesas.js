function validarNombre() {
    var nombreInput = document.getElementById('nombre_reserva');
    var nombreError = document.getElementById('nombreError');
    var regex = /^[a-zA-Z\s]+$/;

    if (!regex.test(nombreInput.value)) {
        nombreError.textContent = 'El nombre no puede contener números ni caracteres especiales.';
        nombreInput.value = '';
    } else {
        nombreError.textContent = '';
    }
}

function validarHora() {
    var horaInput = document.getElementById('hora_reserva');
    var horaError = document.getElementById('horaError');
    var ahora = new Date();
    var horaActual = ahora.getHours() + ':' + (ahora.getMinutes() < 10 ? '0' : '') + ahora.getMinutes();

    if (horaInput.value < horaActual) {
        horaError.textContent = 'La hora no puede ser anterior a la hora actual.';
        horaInput.value = '';
    } else {
        horaError.textContent = '';
    }
}

function validarHoraFin() {
    var horaInput = document.getElementById('hora_reserva');
    var horaFinInput = document.getElementById('hora_fin_reserva');
    var errorFechaFin = document.getElementById('errorFechaFin');

    if (horaFinInput.value <= horaInput.value) {
        errorFechaFin.textContent = 'La hora de fin debe ser mayor que la hora de inicio.';
        horaFinInput.value = '';
    } else {
        errorFechaFin.textContent = '';
    }
}

function validarDia() {
    var diaInput = document.getElementById('dia_reserva');
    var diaError = document.getElementById('diaError');
    var ahora = new Date();
    var hoy = ahora.toISOString().split('T')[0];

    if (diaInput.value < hoy) {
        diaError.textContent = 'El día no puede ser anterior al día actual.';
        diaInput.value = hoy;
    } else {
        diaError.textContent = '';
    }
}

function validarReserva() {
    // Agregar más validaciones si es necesario
    // Devolver false si hay algún error, true si todo está bien
    return true;
}

function mostrarReservaModal(idMesa, nombreMesa, idSala) {
    $("#id_mesa_reserva").val(idMesa);
    $("#infoMesa").html("<p>Reservando mesa: " + nombreMesa + "</p>");
    $("#reservaModal").modal("show");
}