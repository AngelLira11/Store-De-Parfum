document.addEventListener("DOMContentLoaded", function() {
    
    // Función auxiliar para mostrar errores
    function mostrarError(form, mensaje) {
        let errorContainer = form.querySelector(".error-mensaje-producto");
        if (!errorContainer) {
            errorContainer = document.createElement("div");
            errorContainer.className = "error-mensaje-producto";
            errorContainer.style.color = "red";
            errorContainer.style.marginBottom = "10px";
            errorContainer.style.fontWeight = "bold";
            errorContainer.style.fontSize = "0.85rem";
            
            // Insertar antes del botón de submit
            const submitButton = form.querySelector("button[type='submit']");
            if (submitButton) {
                form.insertBefore(errorContainer, submitButton);
            } else {
                form.appendChild(errorContainer);
            }
        }
        errorContainer.innerHTML = mensaje;
        errorContainer.style.display = "block";
    }

    function limpiarError(form) {
        const errorContainer = form.querySelector(".error-mensaje-producto");
        if (errorContainer) {
            errorContainer.style.display = "none";
            errorContainer.innerHTML = "";
        }
    }

    // Validar formulario de producto (Agregar y Editar)
    function validarProducto(e) {
        const form = e.target;
        const nombre = form.querySelector("[name='nomb_product']");
        const precio = form.querySelector("[name='precio_product']");
        const stock = form.querySelector("[name='stock_product']");
        
        let errores = [];

        // Validar Nombre
        if (nombre && nombre.value.trim().length === 0) {
            errores.push("El nombre del producto es obligatorio.");
            nombre.style.borderColor = "red";
        } else if (nombre) {
            nombre.style.borderColor = "";
        }

        // Validar Precio
        if (precio) {
            const precioVal = parseFloat(precio.value);
            if (isNaN(precioVal) || precioVal <= 0) {
                errores.push("El precio debe ser un número mayor a 0.");
                precio.style.borderColor = "red";
            } else {
                precio.style.borderColor = "";
            }
        }

        // Validar Stock
        if (stock) {
            const stockVal = parseFloat(stock.value); // Usamos float para detectar decimales si alguien los pone
            if (isNaN(stockVal) || stockVal < 0 || !Number.isInteger(stockVal)) {
                errores.push("El stock debe ser un número entero no negativo.");
                stock.style.borderColor = "red";
            } else {
                stock.style.borderColor = "";
            }
        }

        if (errores.length > 0) {
            e.preventDefault();
            mostrarError(form, errores.join("<br>"));
        } else {
            limpiarError(form);
        }
    }

    // Validar formulario de búsqueda
    function validarBusqueda(e) {
        const form = e.target;
        const idInput = form.querySelector("[name='product_id']");
        
        let errores = [];

        if (idInput) {
            const idVal = parseFloat(idInput.value);
            if (isNaN(idVal) || idVal <= 0 || !Number.isInteger(idVal)) {
                errores.push("El ID debe ser un número entero positivo.");
                idInput.style.borderColor = "red";
            } else {
                idInput.style.borderColor = "";
            }
        }

        if (errores.length > 0) {
            e.preventDefault();
            // Para el formulario de búsqueda, el diseño es flex, así que insertamos el error de forma diferente o usamos alert si no cabe
            // Intentamos insertar debajo del input container
            let errorContainer = form.parentNode.querySelector(".error-mensaje-busqueda");
            if (!errorContainer) {
                errorContainer = document.createElement("div");
                errorContainer.className = "error-mensaje-busqueda";
                errorContainer.style.color = "red";
                errorContainer.style.marginTop = "5px";
                errorContainer.style.fontSize = "0.85rem";
                form.parentNode.insertBefore(errorContainer, form.nextSibling);
            }
            errorContainer.innerHTML = errores.join("<br>");
        } else {
            const errorContainer = form.parentNode.querySelector(".error-mensaje-busqueda");
            if (errorContainer) errorContainer.remove();
        }
    }

    // Asignar eventos
    const formAgregar = document.getElementById("form-agregar-producto");
    if (formAgregar) {
        formAgregar.addEventListener("submit", validarProducto);
        // Limpiar al escribir
        formAgregar.querySelectorAll("input").forEach(input => {
            input.addEventListener("input", () => {
                input.style.borderColor = "";
                limpiarError(formAgregar);
            });
        });
    }

    const formEditar = document.getElementById("form-editar-producto");
    if (formEditar) {
        formEditar.addEventListener("submit", validarProducto);
        formEditar.querySelectorAll("input").forEach(input => {
            input.addEventListener("input", () => {
                input.style.borderColor = "";
                limpiarError(formEditar);
            });
        });
    }

    const formBuscar = document.getElementById("form-buscar-producto");
    if (formBuscar) {
        formBuscar.addEventListener("submit", validarBusqueda);
        const idInput = formBuscar.querySelector("[name='product_id']");
        if (idInput) {
            idInput.addEventListener("input", function() {
                this.style.borderColor = "";
                const errorContainer = formBuscar.parentNode.querySelector(".error-mensaje-busqueda");
                if (errorContainer) errorContainer.remove();
            });
        }
    }
});
