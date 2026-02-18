var objDiv = document.getElementById("chatBox");
if (objDiv) objDiv.scrollTop = objDiv.scrollHeight;
setInterval(function () {
    let campoTexto = document.querySelector('input[name="mensaje"]');
    if (campoTexto && campoTexto.value.trim() === "") {
        location.reload();
    }
}, 10000);