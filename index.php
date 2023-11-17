<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Configuración básica del documento HTML -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZooBit by Eduardo Castillo</title>

    <!-- Estilos CSS para la apariencia de la página -->
    <style>
    body {
        background-color: #111;
        color: white;
        font-family: Arial, sans-serif;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        overflow: hidden;
    }

    #imagenContainer {
        position: relative;
    }

    #imagen {
        width: 600px;
        height: auto;
        display: block;
        margin: 0 auto;
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

    #runButton {
        position: absolute;
        top: 10px;
        left: 50%;
        transform: translateX(-50%);
        display: inline-block;
        padding: 10px 20px;
        background-color: black;
        color: red;
        border: 2px solid red;
        box-shadow: 0 0 10px cyan;
        text-decoration: none;
        font-size: 12px;
        font-weight: bold;
        cursor: pointer;
    }
    </style>
</head>

<body>
    <!-- Contenedor principal que incluye la imagen y la información -->
    <div id="imagenContainer">
        <!-- Botón RUN -->
        <button id="runButton" onclick="ejecutarPHP()">RUN</button>
        <!-- Imagen que se actualizará en tiempo real -->
        <img id="imagen" src="imagen_salida.png" alt="Imagen en tiempo real">
        <!-- Contenedor para mostrar información adicional -->
                <div id="infoContainer">
            <!-- Contenedor de texto que se actualizará con datos.txt -->
            <div id="textoDatos"></div>

        </div>

    </div>
    </div>
    <script>
    // Función para verificar si la imagen existe
    function imagenExiste(img) {
        return img.complete && img.naturalWidth !== 0;
    }

    // Función para actualizar la imagen en la página
    function actualizarImagen() {
        var imagen = document.getElementById('imagen');
        var nuevaImagen = new Image();

        // Evento que se ejecuta cuando la nueva imagen se carga correctamente
        nuevaImagen.onload = function() {
            // Verifica si la imagen existe antes de actualizarla
            if (imagenExiste(nuevaImagen)) {
                // Actualiza la imagen en la página
                imagen.src = nuevaImagen.src;
            }

            // Hace una solicitud para obtener el contenido de datos.txt
            var infoContainer = document.getElementById('textoDatos');
            var xhttpDatos = new XMLHttpRequest();

            xhttpDatos.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Actualiza el contenido de datos.txt en el contenedor de texto
                    infoContainer.innerHTML = this.responseText;
                }
            };

            xhttpDatos.open("GET", "datos.txt", true);
            xhttpDatos.send();
        };

        // Asigna la URL de la nueva imagen (agregando un timestamp para evitar la caché)
        nuevaImagen.src = "imagen_salida.png?timestamp=" + new Date().getTime();
    }

    // Función para ejecutar PHP mediante una solicitud XMLHttpRequest
    function ejecutarPHP() {
        var xhttp = new XMLHttpRequest();

        // Función que se ejecuta cuando el estado de la solicitud XMLHttpRequest cambia
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4) {
                if (this.status == 200) {
                    // La ejecución del PHP fue exitosa
                    console.log("PHP ejecutado correctamente.");
                    // También puedes agregar lógica adicional aquí si es necesario
                } else {
                    console.error("Error al ejecutar PHP. Estado: " + this.status);
                }
            }
        };

        // Realiza una solicitud POST al script PHP
        xhttp.open("POST", "run.php", true);
        xhttp.send();
    }

    // Ejecuta la función actualizarImagen cada 100 milisegundos
    setInterval(actualizarImagen, 100);
    </script>
</body>

</html>
