<?php

//consumo de la tabla de proveedores
require_once "ConsumoApi/Confi.php";

$consumo = file_get_contents($urlProveedores);

if ($consumo === FALSE) {
    die("Error al consumir el servicio.");
}

$productos = json_decode($consumo, true); //array

echo "Elige una opción:\n";
echo "1. Mostrar todos los proveedores\n";
echo "2. Mostrar solo contacto y teléfono de los proveedores\n";

$opcion = (int) readline("Opción: ");

echo "=== RESULTADO ===\n";

foreach ($productos as $pr) {
    // se separan por |
    $partes = preg_split('/\s+/', $pr);

$id            = trim($partes[0] ?? "");
$nombreEmpresa = trim($partes[1] ?? "");
$contacto = trim(($partes[2] ?? "") . " " . ($partes[3] ?? ""));
$telefono = trim($partes[4] ?? "");
$correo   = trim($partes[5] ?? "");
$direccion = trim(implode(" ", array_slice($partes, 6)));
  
    if ($opcion == 1) {
        echo "ID: $id | Nombre Empresa: $nombreEmpresa | Contacto: $contacto | Teléfono: $telefono | Correo: $correo | Dirección: $direccion\n";
    } elseif ($opcion == 2) {
        echo "Contacto: $contacto | Teléfono: $telefono\n";
    }
}
    //agregar proveedor
    $respuesta = readline("¿Desea agregar un nuevo proveedor? Coloca s para (SI) n para (NO): ");    
    if ($respuesta === "s") {
        $nombreEmpresa = readline("Ingrese nombre de la empresa: ");
        $contacto = readline("Ingrese contacto: ");
        $telefono = readline("Ingrese teléfono: ");
        $correo = readline("Ingrese correo: ");
        $direccion = readline("Ingrese dirección: ");

        $datos = array(
            "nombre_Empresa" => $nombreEmpresa,
            "contacto" => $contacto,
            "telefono" => $telefono,
            "correo" => $correo,
            "direccion" => $direccion
        );

        $data_json = json_encode($datos);

        $proceso = curl_init($urlProveedores);

        curl_setopt($proceso, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($proceso, CURLOPT_POSTFIELDS, $data_json);//cuerpo de la peticion
        curl_setopt($proceso, CURLOPT_RETURNTRANSFER, true);//sirve para que no muestre el resultado en pantalla
        curl_setopt($proceso, CURLOPT_HTTPHEADER, array( 
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_json))// contenido en json
        );

        $respuestapet = curl_exec($proceso);

        $http_code = curl_getinfo($proceso, CURLINFO_HTTP_CODE);
        if ($http_code === 200) {
            echo "Proveedor agregado exitosamente.\n";
        } else {
            echo "Error al agregar el proveedor. Código HTTP: $http_code\n";
    }
}
    //Metodo put
    $respuesta = readline("¿Desea editar un proveedor? Coloca s para (SI) n para (NO): ");
    if ($respuesta === "s") {
        $id = readline("Ingrese el ID del proveedor a editar: ");
        $nombreEmpresa = readline("Ingrese nombre de la empresa: ");
        $contacto = readline("Ingrese contacto: ");
        $telefono = readline("Ingrese teléfono: ");
        $correo = readline("Ingrese correo: ");
        $direccion = readline("Ingrese dirección: ");

        $datos = array(
            "nombre_Empresa" => $nombreEmpresa,
            "contacto" => $contacto,
            "telefono" => $telefono,
            "correo" => $correo,
            "direccion" => $direccion
        );

        $data_json = json_encode($datos);

        $proceso = curl_init($urlProveedores . '/' . $id);

        curl_setopt($proceso, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($proceso, CURLOPT_POSTFIELDS, $data_json);//cuerpo de la peticion
        curl_setopt($proceso, CURLOPT_RETURNTRANSFER, true);//sirve para que no muestre el resultado en pantalla
        curl_setopt($proceso, CURLOPT_HTTPHEADER, array( 
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_json))// contenido en json
        );

        $respuestapet = curl_exec($proceso);

        $http_code = curl_getinfo($proceso, CURLINFO_HTTP_CODE);
        if ($http_code === 200) {
            echo "Proveedor editado exitosamente.\n";
        } else {
            echo "Error al editar proveedor. Código HTTP: $http_code\n";
    }
}
    //Metodo Delete
    $respuesta = readline("¿Desea eliminar un proveedor? Coloca s para (SI) n para (NO): ");
    if ($respuesta === "s") {
        $id = readline("Ingrese el ID del proveedor a eliminar: ");

        $proceso = curl_init($urlProveedores . '/' . $id);

        curl_setopt($proceso, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($proceso, CURLOPT_RETURNTRANSFER, true);//sirve para que no muestre el resultado en pantalla
        curl_setopt($proceso, CURLOPT_HTTPHEADER, array( 
            'Content-Type: application/json')
        );

        $respuestapet = curl_exec($proceso);

        $http_code = curl_getinfo($proceso, CURLINFO_HTTP_CODE);
        if ($http_code === 200) {
            echo "Proveedor eliminado exitosamente.\n";
        } else {
            echo "Error al eliminar proveedor. Código HTTP: $http_code\n";
    }
    
echo "respuesta del servidor:\n";
var_dump($respuestapet);
}


?>