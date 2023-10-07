document.addEventListener("DOMContentLoaded", function () {
  var botonesEliminar = document.querySelectorAll(".eliminar-pelicula");

  botonesEliminar.forEach(function (boton) {
      boton.addEventListener("click", function (event) {
          event.preventDefault();

          var formId = boton.getAttribute("data-form-id");
          var formularioEliminar = document.getElementById(formId);

          var nombre = boton.getAttribute("data-nombre");

          Swal.fire({
              title: `¿Estás seguro de eliminar ${nombre}?`,
              text: "Verifica antes de continuar",
              icon: "question",
              showCancelButton: true,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              confirmButtonText: "Sí, eliminar",
          }).then((result) => {
              if (result.isConfirmed) {
                  formularioEliminar.submit();
              }
          });
      });
  });
});
