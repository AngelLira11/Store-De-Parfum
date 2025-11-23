document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    const nombreInput = document.getElementById("nombre");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("contrasena");
    const telefonoInput = document.getElementById("telefono");

    // Crear contenedor de errores
    let errorContainer = document.createElement("div");
    errorContainer.id = "error-mensaje-admin-crear";
    errorContainer.style.color = "red";
    errorContainer.style.marginBottom = "15px";
    errorContainer.style.display = "none";
    errorContainer.style.fontWeight = "bold";
    errorContainer.style.fontSize = "0.85rem";

    // Insertar antes del botón de submit
    const submitButton = form.querySelector("button[type='submit']");
    // Buscar el contenedor del botón si existe, o insertarlo antes del botón
    const submitContainer = submitButton.closest('.form-group') || submitButton;
    
    if (submitContainer.parentNode === form) {
        form.insertBefore(errorContainer, submitContainer);
    } else {
        // Fallback si la estructura es diferente
        form.insertBefore(errorContainer, submitButton);
    }

    form.addEventListener("submit", function(e) {
        let errores = [];

        // 1. Validar Nombre (mínimo 3 caracteres)
        if (nombreInput.value.trim().length < 3) {
            errores.push("El nombre debe tener al menos 3 caracteres.");
            nombreInput.style.borderColor = "red";
        } else {
            nombreInput.style.borderColor = "";
        }

        // 2. Validar Email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailInput.value)) {
            errores.push("El formato del correo electrónico no es válido.");
            emailInput.style.borderColor = "red";
        } else {
            emailInput.style.borderColor = "";
        }

        // 3. Validar Contraseña (obligatoria en creación)
        if (passwordInput.value.length < 6) {
            errores.push("La contraseña debe tener al menos 6 caracteres.");
            passwordInput.style.borderColor = "red";
        } else {
            passwordInput.style.borderColor = "";
        }

        // 4. Validar Teléfono
        const telefonoLimpio = telefonoInput.value.replace(/\D/g, '');
        if (telefonoLimpio.length !== 10) {
            errores.push("El teléfono debe tener 10 dígitos numéricos.");
            telefonoInput.style.borderColor = "red";
        } else {
            telefonoInput.style.borderColor = "";
        }

        if (errores.length > 0) {
            e.preventDefault();
            errorContainer.innerHTML = errores.join("<br>");
            errorContainer.style.display = "block";
        } else {
            errorContainer.style.display = "none";
        }
    });

    // Limpiar errores al escribir
    [nombreInput, emailInput, passwordInput, telefonoInput].forEach(input => {
        input.addEventListener("input", function() {
            this.style.borderColor = "";
            errorContainer.style.display = "none";
        });
    });
});
