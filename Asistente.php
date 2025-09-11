<?php

$nombre_asistente = readline("Ingrese su nombre: ");

$asistencia = readline("¿Asistió a la reunión? (1 = Sí, 0 = No): ");

if ($asistencia == 1) {
    echo "$nombre_asistente SÍ asistió a la reunión.\n";
} elseif ($asistencia == 0) {
    echo "$nombre_asistente  NO asistió a la reunión.\n";
} else {
    echo "Opción inválida.\n";
}

$comite = readline("¿Pertenece al comité? (1 = Sí, 0 = No): ");
if ($comite == 1) {
} elseif ($comite == 0) {
    echo "$nombre_asistente No pertenece al comité.\n";
} else {
    echo "Opción inválida.\n";
}

if ($asistencia == 1 && $comite == 1) {
    echo "$nombre_asistente PUEDE votar en la reunión.\n";
} else {
    echo "$nombre_asistente NO puede votar en la reunión.\n";
}
?>