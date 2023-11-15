
## Hecho con PHP en Slackware


# Zoobit: Simulación de una Neurona

Este programa en PHP, llamado Zoobit, es una simulación de una inteligencia basada en una neurona. En esta simulación, un punto blanco se mueve en un espacio bidimensional, interactuando con puntos de diferentes colores para acumular puntos de vida o perder vida según el color. El objetivo es sobrevivir en el entorno, moviéndose estratégicamente y consumiendo puntos de comida.

## Autor

Eduardo Castillo ([hellocodelinux@gmail.com](mailto:hellocodelinux@gmail.com))

## Descripción

La simulación se desarrolla en un espacio de juego con dimensiones definidas por `$ancho` y `$alto`. El punto blanco se mueve hacia puntos de diferentes colores (rojo, verde y azul) con el objetivo de acumular puntos de vida y sobrevivir. La posición y cantidad de puntos de cada color se generan de forma aleatoria.

El punto blanco tiene un radio definido por `$radioX` y `$radioY`, y su posición inicial se establece en el centro del espacio de juego. Se utilizan funciones matemáticas para calcular la dirección hacia el punto más cercano y realizar movimientos aleatorios.

El programa incluye la capacidad de regenerar puntos de comida (rojo, verde y azul) periódicamente, lo que agrega dinamismo a la simulación. El valor de vida del punto blanco se ve afectado por el color de la comida consumida.

## Como ejecutarlo

Para poder visualizar los datos generados tiene un index.php puedes crear un servidor web con php

```php -S 0.0.0.0:2000```

y en el navegador usa **https://127.0.0.1:2000**

ejecuta en otra terminal

```php loco.php```

* Recuenda ajustar tu php.ini para que incluyan los modulos necesarios

## Archivos Generados

El programa genera dos archivos:

1. **imagen_salida.png:** Una imagen que representa el estado actual de la simulación, mostrando los puntos de comida y el punto blanco.

2. **datos.txt:** Un archivo de texto que registra información relevante durante la simulación, como la vida restante, la cantidad de comida consumida, el tiempo transcurrido y el costo de movimiento.

## Parámetros Configurables

El programa incluye varios parámetros configurables, como la cantidad inicial de puntos de comida, el intervalo de regeneración, y los valores asociados a la vida por color.

## Ejecución

La simulación se ejecuta en un bucle principal hasta que la vida del punto blanco llega a cero. Durante cada iteración, se actualiza la posición del punto blanco, se verifican colisiones con la comida, se regeneran los puntos periódicamente, se crea una imagen del estado actual y se guarda información en el archivo datos.txt.

## Requisitos

- PHP instalado en el entorno de ejecución.

## Instrucciones de Uso

1. Asegúrate de tener PHP instalado en tu entorno.
2. Ejecuta el archivo PHP para iniciar la simulación.
3. Observa la generación de la imagen y la actualización de datos en el archivo datos.txt.
4. La simulación continuará hasta que la vida del punto blanco llegue a cero.

¡Disfruta explorando Zoobit, la simulación de una neurona en busca de supervivencia!