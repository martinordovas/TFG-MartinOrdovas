var objDiv = document.getElementById("chatBox");
if (objDiv) objDiv.scrollTop = objDiv.scrollHeight;
setInterval(function () {
    // Buscamos el input del mensaje por su nombre
    let campoTexto = document.querySelector('input[name="mensaje"]');

    // Si el campo existe y NO tiene nada escrito, refrescamos
    if (campoTexto && campoTexto.value.trim() === "") {
        location.reload();
    }
}, 10000);