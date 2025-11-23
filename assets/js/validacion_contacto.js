document.addEventListener("DOMContentLoaded", function() {
    console.log("Script de validación de contacto cargado.");
    
    const form = document.querySelector(".form-container form");
    if (!form) {
        console.error("No se encontró el formulario de contacto.");
        return;
    }

    const nombreInput = document.getElementById("nombre");
    const emailInput = document.getElementById("email");
    const telefonoInput = document.getElementById("telefono");
    const asuntoInput = document.getElementById("asunto");
    const mensajeInput = document.getElementById("mensaje");

    // Crear contenedor de errores
    let errorContainer = document.createElement("div");
    errorContainer.id = "error-mensaje-contacto";
    errorContainer.style.color = "red";
    errorContainer.style.marginBottom = "15px";
    errorContainer.style.display = "none";
    errorContainer.style.fontWeight = "bold";
    errorContainer.style.fontSize = "0.85rem"; // Tamaño de letra más pequeño

    // Insertar antes del contenedor del botón de submit
    const submitButton = form.querySelector("input[type='submit']");
    // El botón está dentro de un div class="form-group", así que buscamos ese contenedor
    const submitContainer = submitButton.closest('.form-group');
    
    if (submitContainer) {
        form.insertBefore(errorContainer, submitContainer);
    } else {
        // Si no encuentra el contenedor, lo agregamos al final del formulario
        form.appendChild(errorContainer);
    }

    form.addEventListener("submit", function(e) {
        console.log("Intentando enviar formulario...");
        let errores = [];

        // 1. Validar Nombre (mínimo 3 caracteres)
        if (nombreInput.value.trim().length < 3) {
            errores.push("El nombre debe tener al menos 3 caracteres.");
            nombreInput.style.borderColor = "red";
        } else {
            nombreInput.style.borderColor = "";
        }

        // 2. Validar Email (formato simple)
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailInput.value)) {
            errores.push("Por favor, introduce un correo electrónico válido.");
            emailInput.style.borderColor = "red";
        } else {
            emailInput.style.borderColor = "";
        }

        // 3. Validar Teléfono (10 dígitos numéricos)
        // Eliminamos espacios y guiones para validar solo los números
        const telefonoLimpio = telefonoInput.value.replace(/\D/g, ''); 
        if (telefonoLimpio.length !== 10) {
            errores.push("El teléfono debe tener 10 dígitos numéricos.");
            telefonoInput.style.borderColor = "red";
        } else {
            telefonoInput.style.borderColor = "";
        }

        // 4. Validar Asunto (no vacío)
        if (asuntoInput.value.trim() === "") {
            errores.push("El asunto es obligatorio.");
            asuntoInput.style.borderColor = "red";
        } else {
            asuntoInput.style.borderColor = "";
        }

        // 5. Validar Mensaje (mínimo 10 caracteres)
        // Usamos trim() para evitar que cuenten solo espacios
        if (mensajeInput.value.trim().length < 10) {
            errores.push("El mensaje es muy corto. Por favor escribe al menos 10 caracteres.");
            mensajeInput.style.borderColor = "red";
        } else {
            mensajeInput.style.borderColor = "";
        }

        if (errores.length > 0) {
            console.log("Errores encontrados:", errores);
            e.preventDefault();
            errorContainer.innerHTML = errores.join("<br>");
            errorContainer.style.display = "block";
            errorContainer.style.color = "red";
        } else {
            console.log("Formulario válido. Simulando envío.");
            // Simulación de envío exitoso (ya que el action es "#")
            e.preventDefault(); // Quitamos esto si tuvieras un backend real para contacto
            form.reset();
            
            // Ocultar mensaje de éxito después de 3 segundos
            setTimeout(() => {
                errorContainer.style.display = "none";
            }, 3000);
        }
    });

    // Limpiar errores al escribir
    [nombreInput, emailInput, telefonoInput, asuntoInput, mensajeInput].forEach(input => {
        input.addEventListener("input", function() {
            this.style.borderColor = "";
            // Solo ocultamos el error si es rojo (error), no si es verde (éxito)
            if (errorContainer.style.color === "red") {
                errorContainer.style.display = "none";
            }
        });
    });
});
