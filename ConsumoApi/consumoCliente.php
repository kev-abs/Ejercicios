
<?php
/* Bueno, teniendo en cuenta lo solicitado en clase
se decidio hacer un consumo en el cual el cliente
o usuario solicita los datos de clientes por medio
de un ID. Sin embargo no solo se va a manejar por Id
infinito, ya que así el cliente puede agregar un
ID que no exista; sino que se limito a 10, para
tener control sobre el mismo. */

$url = "http://localhost:8080/clientes";

$consumo = file_get_contents($url);

if ($consumo === false) {
    die("Error al consumir el servicio");
}

$clientes = json_decode($consumo);

$id = (INT) readline("¿Que usuario desea ver por ID? De 1 a 10: ");

if ($id >=1 && $id <=10){
    foreach ($clientes as $cliente) {
        $datos = explode(" | ", $cliente);
        $idCliente = (int) trim($datos[0]);
        
        
        if ($idCliente==$id) {
            echo $cliente. "\n";
        }
    }
}
else {
    echo ("ID fuera de rango.");
}
?>