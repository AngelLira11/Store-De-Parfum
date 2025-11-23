document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("confirm_password");
    const phoneInput = document.getElementById("phone");
    
    // Crear contenedor de errores si no existe
    let errorContainer = document.createElement("div");
    errorContainer.id = "error-mensaje";
    errorContainer.style.color = "red";
    errorContainer.style.marginBottom = "15px";
    errorContainer.style.display = "none";
    errorContainer.style.fontWeight = "bold";
    
    // Insertar el contenedor antes del botón de submit
    const submitButton = form.querySelector("input[type='submit']");
    form.insertBefore(errorContainer, submitButton);

    form.addEventListener("submit", function(e) {
        let errores = [];
        
        // 1. Validar que las contraseñas coincidan
        if (passwordInput.value !== confirmPasswordInput.value) {
            errores.push("Las contraseñas no coinciden.");
            confirmPasswordInput.style.borderColor = "red";
        } else {
            confirmPasswordInput.style.borderColor = ""; // Resetear estilo
        }

        // 2. Validar longitud del teléfono (aunque HTML lo hace, JS es doble seguridad)
        if (phoneInput.value.length !== 10 || isNaN(phoneInput.value)) {
            errores.push("El teléfono debe tener 10 dígitos numéricos.");
            phoneInput.style.borderColor = "red";
        } else {
            phoneInput.style.borderColor = "";
        }

        // Si hay errores, prevenimos el envío y mostramos los mensajes
        if (errores.length > 0) {
            e.preventDefault(); // Detiene el envío del formulario
            errorContainer.innerHTML = errores.join("<br>");
            errorContainer.style.display = "block";
        } else {
            errorContainer.style.display = "none";
            // El formulario se enviará normalmente
        }
    });

    // Función para validar en tiempo real
    function validarTiempoReal() {
        const pass = passwordInput.value;
        const confirm = confirmPasswordInput.value;

        // Solo validamos si ambos campos tienen algo escrito
        if (pass !== "" && confirm !== "") {
            if (pass === confirm) {
                confirmPasswordInput.style.borderColor = "green";
                errorContainer.style.display = "none";
            } else {
                confirmPasswordInput.style.borderColor = ""; // Quitamos el verde si dejan de coincidir
            }
        } else {
            confirmPasswordInput.style.borderColor = ""; // Quitamos estilos si están vacíos
        }
    }

    // Escuchar en ambos campos
    confirmPasswordInput.addEventListener("input", validarTiempoReal);
    passwordInput.addEventListener("input", validarTiempoReal);
});
