<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Configuración básica del documento HTML -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZooBIT by Eduardo Castillo</title>

    <!-- Estilos CSS para la apariencia de la página -->
    <style>
        body {
            background-color: #111;
            color: white;
            font-family: Arial, sans-serif;
        }

        #imagenContainer {
            position: absolute;
            background-color: #111;
            overflow: hidden;
        }

        #imagen {
            width: 600px;
            height: auto;
            display: block;
        }

        #infoContainer {
            position: absolute;
            top: 0;
            left: 10px;
            color: white;
            font-family: Monospace;
            font-size: 12px;
        }

        #textoDatos {
            white-space: pre;
        }
    </style>
</head>

<body>
    <!-- Contenedor principal que incluye la imagen y la información -->
    <div id="imagenContainer">
        <!-- Imagen que se actualizará en tiempo real -->
        <img id="imagen" src="imagen_salida.png" alt="Imagen en tiempo real">
        <!-- Contenedor para mostrar información adicional -->
        <div id="infoContainer">
            <!-- Contenedor de texto que se actualizará con datos.txt -->
            <div id="textoDatos"></div>
        </div>
    </div>

    <!-- Script JavaScript para actualizar la imagen y obtener datos en tiempo real -->
    <script>
        // Función para actualizar el contenido de la página
        function actualizarContenido() {
            var imagen = document.getElementById('imagen');
            var nuevaImagen = new Image();

            // Evento que se ejecuta cuando la nueva imagen se carga correctamente
            nuevaImagen.onload = function() {
                // Actualiza la imagen en la página
                imagen.src = nuevaImagen.src;

                // Obtiene el contenedor de información
                var infoContainer = document.getElementById('textoDatos');
                var xhttp = new XMLHttpRequest();

                // Función que se ejecuta cuando el estado de la solicitud XMLHttpRequest cambia
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4) {
                        if (this.status == 200) {
                            // Actualiza el contenido con los datos obtenidos de datos.txt
                            infoContainer.innerHTML = this.responseText;
                        } else {
                            console.error("Error al cargar datos.txt. Estado: " + this.status);
                        }
                    }
                };

                // Realiza una solicitud GET para obtener datos.txt, incluyendo un timestamp para evitar la caché
                xhttp.open("GET", "datos.txt?timestamp=" + new Date().getTime(), true);
                xhttp.send();
            };

            // Asigna la URL de la nueva imagen (agregando un timestamp para evitar la caché)
            nuevaImagen.src = "imagen_salida.png?timestamp=" + new Date().getTime();
        }

        // Ejecuta la función actualizarContenido cada 100 milisegundos
        setInterval(actualizarContenido, 100);
    </script>
</body>

</html>
