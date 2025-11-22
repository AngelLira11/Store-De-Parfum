document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form[action='actualizar_perfil.php']");
    
    if (!form) return;

    const emailInput = document.getElementById("nuevo_email");
    const telefonoInput = document.getElementById("nuevo_telefono");
    const passwordInput = document.getElementById("nueva_contrasena");

    // Crear contenedor de errores
    let errorContainer = document.createElement("div");
    errorContainer.id = "error-mensaje-perfil";
    errorContainer.style.color = "red";
    errorContainer.style.marginBottom = "15px";
    errorContainer.style.display = "none";
    errorContainer.style.fontWeight = "bold";
    errorContainer.style.fontSize = "0.85rem";

    // Insertar antes del botón de submit
    const submitButton = form.querySelector("button[type='submit']");
    form.insertBefore(errorContainer, submitButton);

    form.addEventListener("submit", function(e) {
        let errores = [];

        // 1. Validar Email (si no está vacío)
        if (emailInput.value.trim() !== "") {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailInput.value)) {
                errores.push("El formato del nuevo correo electrónico no es válido.");
                emailInput.style.borderColor = "red";
            } else {
                emailInput.style.borderColor = "";
            }
        }

        // 2. Validar Teléfono (si no está vacío)
        if (telefonoInput.value.trim() !== "") {
            // Limpiar caracteres no numéricos para validar
            const telefonoLimpio = telefonoInput.value.replace(/\D/g, '');
            
            if (telefonoLimpio.length !== 10) {
                errores.push("El nuevo teléfono debe tener 10 dígitos numéricos.");
                telefonoInput.style.borderColor = "red";
            } else {
                telefonoInput.style.borderColor = "";
            }
        }

        // 3. Validar Contraseña (si no está vacía)
        if (passwordInput.value !== "") {
            if (passwordInput.value.length < 6) {
                errores.push("La nueva contraseña debe tener al menos 6 caracteres.");
                passwordInput.style.borderColor = "red";
            } else {
                passwordInput.style.borderColor = "";
            }
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
    [emailInput, telefonoInput, passwordInput].forEach(input => {
        if (input) {
            input.addEventListener("input", function() {
                this.style.borderColor = "";
                errorContainer.style.display = "none";
            });
        }
    });
});
