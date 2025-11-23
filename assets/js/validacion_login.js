document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");
    
    // Crear contenedor de errores
    let errorContainer = document.createElement("div");
    errorContainer.id = "error-mensaje-login";
    errorContainer.style.color = "red";
    errorContainer.style.marginBottom = "15px";
    errorContainer.style.display = "none";
    errorContainer.style.fontWeight = "bold";
    
    const submitButton = form.querySelector("input[type='submit']");
    form.insertBefore(errorContainer, submitButton);

    // 1. Funcionalidad "Mostrar Contraseña"
    if (togglePassword) {
        togglePassword.addEventListener("change", function() {
            if (this.checked) {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        });
    }

    // 2. Validación al enviar
    form.addEventListener("submit", function(e) {
        let errores = [];
        
        // Validar formato de email simple
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailInput.value)) {
            errores.push("Por favor, introduce un correo electrónico válido.");
            emailInput.style.borderColor = "red";
        } else {
            emailInput.style.borderColor = "";
        }

        // Validar que la contraseña no esté vacía (aunque HTML lo hace)
        if (passwordInput.value.trim() === "") {
            errores.push("La contraseña es obligatoria.");
            passwordInput.style.borderColor = "red";
        } else {
            passwordInput.style.borderColor = "";
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
    emailInput.addEventListener("input", function() {
        emailInput.style.borderColor = "";
        errorContainer.style.display = "none";
    });
    passwordInput.addEventListener("input", function() {
        passwordInput.style.borderColor = "";
        errorContainer.style.display = "none";
    });
});
