document.addEventListener("DOMContentLoaded", function() {
    const inputBusqueda = document.getElementById("buscador");
    const items = document.querySelectorAll(".item");
    const noResultados = document.getElementById("no-resultados");

    if (inputBusqueda) {
        inputBusqueda.addEventListener("keyup", function(e) {
            const termino = e.target.value.toLowerCase().trim();
            let visibles = 0;

            items.forEach(item => {
                const descripcion = item.querySelector(".descripcion").textContent.toLowerCase();
                
                if (descripcion.includes(termino)) {
                    item.style.display = ""; // Restaura el display original (block/flex/grid)
                    visibles++;
                } else {
                    item.style.display = "none";
                }
            });

            // Mostrar mensaje si no hay resultados
            if (visibles === 0) {
                if (noResultados) noResultados.style.display = "block";
            } else {
                if (noResultados) noResultados.style.display = "none";
            }
        });
    }
});
