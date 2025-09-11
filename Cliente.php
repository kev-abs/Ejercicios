<?php
$nombre = readline("Ingrese su nombre: ");
$edad = readline("Ingrese su edad: ");
$VIP = readline("¿Es cliente VIP? (1 = Sí, 0 = No): ");
$activo = true;

if ($activo && ($edad >= 60 || $VIP == 1)) {
    echo " El cliente $nombre es VIP tiene descuento.\n";
} else {
    echo "El cliente $nombre NO es VIP No tiene descuento.\n";
}


echo "Su nombre es: $nombre\n";
echo "Su edad es: $edad\n";
?>
