<?php

/**
 * Simulación en el que un punto blanco se mueve hacia puntos de diferentes colores,
 * acumulando puntos de vida o perdiendo vida según el color. Los puntos se regeneran periódicamente.
 */

/**
 * Creado por Eduardo Castillo (hellocodelinux@gmail.com
 */

$movimientos = [[0, 1], [0, -1], [1, 0], [-1, 0]]; // Definición de movimientos posibles (arriba, abajo, derecha, izquierda)
$puntosRojos = []; // Almacena las coordenadas de los puntos rojos
$puntosVerdes = []; // Almacena las coordenadas de los puntos verdes
$puntosAzules = []; // Almacena las coordenadas de los puntos azules
$vida = 1000; // Puntos de vida inicial
$contador = 0; // Contador de movimientos
$colorComida = ""; // Almacena el color de la comida
$ancho = 300; // Ancho del espacio de juego
$alto = 300; // Alto del espacio de juego
$puntoBlancoX = $ancho / 2; // Posición inicial en el eje X del punto blanco
$puntoBlancoY = $alto / 2; // Posición inicial en el eje Y del punto blanco
$radioX = 10; // Radio en el eje X del punto blanco
$radioY = 10; // Radio en el eje Y del punto blanco
$comio = 0; // Contador de veces que el punto blanco ha comido
$intervaloRegeneracion = 60; // Intervalo de tiempo para regenerar los puntos
$ultimoTiempoRegeneracion = time(); // Tiempo de la última regeneración
$fcomi = 100; // Número inicial de puntos a regenerar
$vvd = 1; // Valor inicial de pérdida de vida por movimiento

/**
 * Función para mover el punto blanco en el espacio de juego.
 * Calcula la dirección hacia el punto más cercano y realiza movimientos aleatorios.
 */

function moverPuntoBlanco()
{
    global $puntoBlancoX, $puntoBlancoY, $movimientos, $puntosRojos, $puntosVerdes, $puntosAzules, $vida, $contador, $colorComida, $comio, $radioX, $radioY, $ancho, $alto, $vvd;

    $vida = $vida - $vvd; // Reduce la vida por el valor definido

    // Encuentra el punto más cercano dentro del campo de visión
    $puntoCercano = encontrarPuntoCercano();

    // Calcula la dirección hacia el punto más cercano
    $direccion = calcularDireccion($puntoBlancoX, $puntoBlancoY, $puntoCercano[0], $puntoCercano[1]);

    // Ajusta la frecuencia de movimiento hacia el punto más cercano
    $probabilidadMovimiento = rand(1, 100);
    $frecuenciaMovimiento = 70; // Ajusta este valor según sea necesario

    // Si la probabilidad es menor que la frecuencia, mueve hacia el punto más cercano, de lo contrario, realiza un movimiento aleatorio
    if ($probabilidadMovimiento <= $frecuenciaMovimiento) {
        $puntoBlancoX += $direccion[0];
        $puntoBlancoY += $direccion[1];
    } else {
        // Movimiento aleatorio
        $movimientoAleatorio = $movimientos[array_rand($movimientos)];
        $puntoBlancoX += $movimientoAleatorio[0];
        $puntoBlancoY += $movimientoAleatorio[1];
    }

    // Restringe el punto blanco dentro de los límites del espacio de juego
    restringirLimites();

    // Comprobar colisiones con puntos Rojos, Verdes, y Azules
    foreach (['Rojo', 'Verde', 'Azul'] as $color) {
        $puntos = ($color === 'Rojo') ? $puntosRojos : (($color === 'Verde') ? $puntosVerdes : $puntosAzules);
        $puntosComidos = [];

        foreach ($puntos as $punto) {
            if ($puntoBlancoX - $radioX / 2 <= $punto[0] && $punto[0] <= $puntoBlancoX + $radioX / 2
                && $puntoBlancoY - $radioY / 2 <= $punto[1] && $punto[1] <= $puntoBlancoY + $radioY / 2) {
                $vida += obtenerValorVida($color);
                //$comio++;
            } else {
                $puntosComidos[] = $punto;
            }
        }

        // Actualizar el arreglo original con los puntos no comidos
        if ($color === 'Rojo') {
            $puntosRojos = $puntosComidos;
        } elseif ($color === 'Verde') {
            $puntosVerdes = $puntosComidos;
        } elseif ($color === 'Azul') {
            $puntosAzules = $puntosComidos;
        }
    }

    // Verificar si no hay puntos verdes o azules y terminar si es el caso
    if (empty($puntosVerdes) && empty($puntosAzules)) {
        exit("No hay comida, terminando...");
    }
}

/**
 * Asigna valores de vida según el color de la comida.
 * @param string $color Color de la comida ('Rojo', 'Verde' o 'Azul').
 * @return int Valor de vida asociado al color.
 */
function obtenerValorVida($color)
{
    global $comio;

    $comio++;

    // Asignar valores de vida según el color
    switch ($color) {
        case 'Rojo':
            return -200;
        case 'Verde':
            return 100;
        case 'Azul':
            return 50;
        default:
            return 0;
    }
}

/**
 * Encuentra el punto más cercano al punto blanco, filtrando puntos verdes cercanos a puntos rojos.
 * @return array Coordenadas del punto más cercano.
 */
function encontrarPuntoCercano()
{
    global $puntoBlancoX, $puntoBlancoY, $puntosRojos, $puntosVerdes, $puntosAzules, $colorComida;

    // Filtrar solo los puntos verdes
    $puntosVerdesFiltrados = array_filter($puntosVerdes, function ($punto) {
        global $puntoBlancoX, $puntoBlancoY, $puntosRojos, $colorComida;

        // Calcular la distancia a los puntos verdes que no están demasiado cerca de puntos rojos
        $distanciaMinima = min(array_map(function ($puntoRojo) use ($punto) {
            return calcularDistancia($punto[0], $punto[1], $puntoRojo[0], $puntoRojo[1]);
        }, $puntosRojos));

        // Actualizar el color de la comida si no hay puntos rojos cercanos
        if ($distanciaMinima > 10) {
            $colorComida = 'Verde';
        }

        return $distanciaMinima > 10; // Ajusta este valor según sea necesario
    });

    // Combinar los puntos verdes filtrados con los azules
    $todosLosPuntos = array_merge($puntosVerdesFiltrados, $puntosAzules);

    // Encontrar el punto más cercano
    $distanciaMinima = PHP_INT_MAX;
    $puntoCercano = null;

    foreach ($todosLosPuntos as $punto) {
        $distancia = calcularDistancia($puntoBlancoX, $puntoBlancoY, $punto[0], $punto[1]);
        if ($distancia < $distanciaMinima) {
            $distanciaMinima = $distancia;
            $puntoCercano = $punto;

            // Actualizar el color de la comida según el tipo de punto encontrado
            $colorComida = (in_array($punto, $puntosRojos)) ? 'Rojo' : ((in_array($punto, $puntosVerdes)) ? 'Verde' : 'Azul');
        }
    }

    return $puntoCercano;
}

/**
 * Calcula la dirección desde un punto inicial hacia un punto final.
 * @param float $x1 Coordenada X del punto inicial.
 * @param float $y1 Coordenada Y del punto inicial.
 * @param float $x2 Coordenada X del punto final.
 * @param float $y2 Coordenada Y del punto final.
 * @return array Dirección en forma de vector [$direccionX, $direccionY].
 */
function calcularDireccion($x1, $y1, $x2, $y2)
{
    // Calcula la dirección desde (x1, y1) hacia (x2, y2)
    $distancia = sqrt(($x2 - $x1) ** 2 + ($y2 - $y1) ** 2);
    $direccionX = ($x2 - $x1) / $distancia;
    $direccionY = ($y2 - $y1) / $distancia;

    return [$direccionX, $direccionY];
}

/**
 * Calcula la distancia entre dos puntos en el espacio bidimensional.
 * @param float $x1 Coordenada X del primer punto.
 * @param float $y1 Coordenada Y del primer punto.
 * @param float $x2 Coordenada X del segundo punto.
 * @param float $y2 Coordenada Y del segundo punto.
 * @return float Distancia entre los dos puntos.
 */
function calcularDistancia($x1, $y1, $x2, $y2)
{
    // Calcula la distancia entre dos puntos
    return sqrt(($x2 - $x1) ** 2 + ($y2 - $y1) ** 2);
}

/**
 * Restringe la posición del punto blanco dentro de los límites del espacio.
 */
function restringirLimites()
{
    global $puntoBlancoX, $puntoBlancoY, $radioX, $radioY, $ancho, $alto;

    // Restringe el punto blanco dentro de los límites del espacio
    $puntoBlancoX = max(0, min($puntoBlancoX, $ancho));
    $puntoBlancoY = max(0, min($puntoBlancoY, $alto));
}

/**
 * Crea una imagen del espacio, dibujando puntos de diferentes colores y el punto blanco.
 */
function crearImagen()
{
    global $puntoBlancoX, $puntoBlancoY, $movimientos, $puntosRojos, $puntosVerdes, $puntosAzules, $vida, $contador, $ancho, $alto, $radioX, $radioY, $vvd;

    // Crear la imagen
    $imagen = imagecreatetruecolor($ancho, $alto);
    $colorNegro = imagecolorallocate($imagen, 0, 0, 0);
    $colorRojo = imagecolorallocate($imagen, 255, 0, 0);
    $colorVerde = imagecolorallocate($imagen, 0, 255, 0);
    $colorAzul = imagecolorallocate($imagen, 0, 0, 255);
    $colorBlanco = imagecolorallocate($imagen, 255, 255, 255);
    $colorAmarillo = imagecolorallocate($imagen, 255, 255, 0);

    // Rellenar el fondo con color negro
    imagefilledrectangle($imagen, 0, 0, $ancho, $alto, $colorNegro);

    // Dibujar puntos rojos
    foreach ($puntosRojos as $punto) {
        imagesetpixel($imagen, $punto[0], $punto[1], $colorRojo);
    }

    // Dibujar puntos verdes
    foreach ($puntosVerdes as $punto) {
        imagesetpixel($imagen, $punto[0], $punto[1], $colorVerde);
    }

    // Dibujar puntos azules
    foreach ($puntosAzules as $punto) {
        imagesetpixel($imagen, $punto[0], $punto[1], $colorAzul);
    }

    // Mover el punto blanco aleatoriamente
    moverPuntoBlanco($puntoBlancoX, $puntoBlancoY, $movimientos, $puntosRojos, $puntosVerdes, $puntosAzules, $vida, $contador);

    // Dibujar punto blanco como una elipse más grande
    imagefilledellipse($imagen, $puntoBlancoX, $puntoBlancoY, $radioX, $radioY, $colorBlanco);

    // Visión del punto blanco
    $vradioX = $radioX * 4;
    $vradioY = $radioY * 4;
    imageellipse($imagen, $puntoBlancoX, $puntoBlancoY, $vradioX, $vradioY, $colorAmarillo);

    // Guardar la imagen (puedes cambiar el formato según tus necesidades)
    imagepng($imagen, 'imagen_salida.png');

    // Liberar memoria
    imagedestroy($imagen);
}

/**
 * Inicialización de los puntos rojos, verdes y azules en posiciones aleatorias dentro del espacio de juego.
 */
for ($i = 0; $i < 50; $i++) {
    $x = rand(0, $ancho - 1);
    $y = rand(0, $alto - 1);
    $puntosRojos[] = [$x, $y];

    $x = rand(0, $ancho - 1);
    $y = rand(0, $alto - 1);
    $puntosVerdes[] = [$x, $y];

    $x = rand(0, $ancho - 1);
    $y = rand(0, $alto - 1);
    $puntosAzules[] = [$x, $y];
}

/**
 * Registro del tiempo inicial para medir la duración total de la simulación.
 */
$tiempoI = time();

/**
 * Bucle principal de la simulación que se ejecuta mientras el punto blanco tiene vida.
 */
while ($vida > 0) {
    // Mover el punto blanco y obtener el color de la comida
    moverPuntoBlanco();
    $tiempoActual = time();
    $tiempoTranscurrido = $tiempoActual - $ultimoTiempoRegeneracion;

    // Verifica si ha pasado el intervalo de tiempo para regenerar los puntos
    if ($tiempoTranscurrido >= $intervaloRegeneracion) {
        // Regenerar los puntos
        $puntosRojos = [];
        $puntosVerdes = [];
        $puntosAzules = [];

        // Generar nuevos puntos rojos, verdes y azules
        for ($i = 0; $i < $fcomi; $i++) {
            $x = rand(0, $ancho - 1);
            $y = rand(0, $alto - 1);
            $puntosRojos[] = [$x, $y];

            $x = rand(0, $ancho - 1);
            $y = rand(0, $alto - 1);
            $puntosVerdes[] = [$x, $y];

            $x = rand(0, $ancho - 1);
            $y = rand(0, $alto - 1);
            $puntosAzules[] = [$x, $y];
        }

        // Ajustar variables relacionadas con la regeneración
        $fcomi = $fcomi - 20;
        $vvd++;

        // Actualizar el tiempo de la última regeneración
        $ultimoTiempoRegeneracion = $tiempoActual;
    }

    // Llama a la función crearImagen()
    crearImagen();

    // Calcular el tiempo transcurrido desde el inicio de la simulación
    $tiempoZ = time() - $tiempoI;

    $puntoCercano = encontrarPuntoCercano();

// Construir el texto para guardar en datos.txt
    $texto = "
     Total vida = $vida
Comida generada = " . $fcomi * 3 . "
          Comio = $comio
         Tiempo = $tiempoZ
  Ciclo de vida = $vvd
     Zoobit GPS = [" . (int) $puntoBlancoX . ", " . (int) $puntoBlancoY . "]
  Punto cercano = $colorComida " . ($puntoCercano ? "[" . (int) $puntoCercano[0] . ", " . (int) $puntoCercano[1] . "]" : "Ninguno") . "
";

    // Guardar la información en datos.txt
    file_put_contents('datos.txt', $texto);

    // Esperar un corto tiempo antes de la siguiente iteración
    usleep(50000);
}
