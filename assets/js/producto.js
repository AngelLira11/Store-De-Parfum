document.addEventListener("DOMContentLoaded", function() {
    const btnRestar = document.getElementById("btn-restar");
    const btnSumar = document.getElementById("btn-sumar");
    const inputCantidad = document.getElementById("input-cantidad");
    const inputHiddenCantidad = document.getElementById("cantidad-final");
    const stockMaximo = parseInt(document.getElementById("stock-maximo").value);

    if (btnRestar && btnSumar && inputCantidad) {
        
        btnRestar.addEventListener("click", function() {
            let cantidad = parseInt(inputCantidad.value);
            if (cantidad > 1) {
                cantidad--;
                inputCantidad.value = cantidad;
                inputHiddenCantidad.value = cantidad;
            }
        });

        btnSumar.addEventListener("click", function() {
            let cantidad = parseInt(inputCantidad.value);
            if (cantidad < stockMaximo) {
                cantidad++;
                inputCantidad.value = cantidad;
                inputHiddenCantidad.value = cantidad;
            }
        });
    }
});
