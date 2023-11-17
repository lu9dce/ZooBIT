<?php
// Nombre del archivo del proceso
$archivoProceso = 'zbit.php';

// Comando a ejecutar para matar el proceso existente
$comandoMatar = 'pkill -f "' . $archivoProceso . '"';

// Ejecutar el comando para matar el proceso existente
shell_exec($comandoMatar);

// Comando a ejecutar para iniciar el proceso
$comandoIniciar = 'php ' . $archivoProceso . ' >/dev/null 2>/dev/null&';

// Ejecutar el comando para iniciar el proceso
shell_exec($comandoIniciar);

echo 'El proceso se ha reiniciado.';
?>
