<?php
require_once "ConsumoApi/Confi.php";

$consumo = file_get_contents($urlProducto);

if ($consumo === FALSE) {
    die("Error al consumir el servicio.");
}

$productos = json_decode($consumo, true); //array

echo "Elige una opción:\n";
echo "1. Mostrar todos los productos\n";
echo "2. Mostrar solo productos con stock disponible\n";
echo "3. Mostrar productos con precio mayor a 100000\n";

$opcion = (int) readline("Opción: ");

echo "=== RESULTADO ===\n";

foreach ($productos as $p) {
    // se separan por |
    $partes = explode("|", $p);

    $id     = trim($partes[0] ?? "");
    $nombre = trim($partes[1] ?? ""); 
    $precio = (float) trim($partes[3] ?? 0);
    $stock  = (int) filter_var($partes[4] ?? "0", FILTER_SANITIZE_NUMBER_INT);//elimina todo lo que no sea numero

    if ($opcion == 1) {
        echo "ID: $id | Nombre: $nombre | Precio: $precio | Stock: $stock\n";
    } elseif ($opcion == 2 && $stock > 0) {
        echo "ID: $id | Nombre: $nombre | Precio: $precio | Stock: $stock\n";
    } elseif ($opcion == 3 && $precio > 100000) {
        echo "ID: $id | Nombre: $nombre | Precio: $precio | Stock: $stock\n";
    }
}
//agregar poducto
$respuesta = readline("Desea agregar un nuevo producto? Coloca s para si, n para no: ");
if ($respuesta === 's') {
    $nombre = readline("Nombre del producto: ");
    $descripcion = readline("Descripción del producto: ");
    $precio = readline("Precio del producto: ");
    $stock = readline("Stock del producto: ");
    $id_proveedor = readline("ID del proveedor: ");

    $datos = array(
        "nombre" => $nombre,
        "descripcion" => $descripcion,
        "precio" => $precio,
        "stock" => $stock,
        "idProveedor" => $id_proveedor
    );

    $data_json = json_encode($datos);

    //peticion curl
    $proceso = curl_init($urlProducto);


    curl_setopt($proceso, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($proceso, CURLOPT_POSTFIELDS, $data_json);
    curl_setopt($proceso, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($proceso, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Content-Length: " . strlen($data_json))
    );

    //ejecucion
    $respuestapet = curl_exec($proceso);

    //codigo http
    $http_code = curl_getinfo($proceso, CURLINFO_HTTP_CODE);

    if(curl_errno($proceso )){
        die('Error en la petición: ' . curl_error($proceso)."\n");  
    }
    curl_close($proceso);
    if ($http_code === 200) {
        echo "Producto agregado exitosamente, respuesta (200).\n";
    } else {
        echo "Error al agregar el producto. $http_code\n";
}

}
//Metodo Put
$respuesta = readline("¿Desea editar un producto? Coloca s para (SI) n para (NO): ");
if ($respuesta === "s") {
    $id = readline("Ingrese el ID del producto a editar: ");
    $nombre = readline("Nuevo nombre del producto: ");
    $descripcion = readline("Nueva descripción del producto: ");
    $precio = readline("Nuevo precio del producto: ");
    $stock = readline("Nuevo stock del producto: ");
    $id_proveedor = readline("Nuevo ID del proveedor: ");

    $datos = array(
        "nombre" => $nombre,
        "descripcion" => $descripcion,
        "precio" => $precio,
        "stock" => $stock,
        "idProveedor" => $id_proveedor
    );

    $data_json = json_encode($datos);

    //peticion curl
    $proceso = curl_init($urlProducto . '/' . $id);
    curl_setopt($proceso, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($proceso, CURLOPT_POSTFIELDS, $data_json);
    curl_setopt($proceso, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($proceso, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Content-Length: " . strlen($data_json))
    );
    //ejecucion
    $respuestapet = curl_exec($proceso);
    
    $http_code = curl_getinfo($proceso, CURLINFO_HTTP_CODE);
    if(curl_errno($proceso )){
        die('Error en la petición: ' . curl_error($proceso)."\n");  
    }
    curl_close($proceso);
    if ($http_code === 200) {
        echo "Producto editado exitosamente, respuesta (200).\n";
    } else {
        echo "Error al editar producto. $http_code\n";
    }
}
//Metodo Delete
$respuesta = readline("¿Desea eliminar un producto? Coloca s para (SI) n para (NO): ");
if ($respuesta === "s") {
    $id = readline("Ingrese el ID del producto a eliminar: ");

    //peticion curl
    $proceso = curl_init($urlProducto . '/' . $id);
    curl_setopt($proceso, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($proceso, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($proceso, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json")
    );
    //ejecucion
    $respuestapet = curl_exec($proceso);
    
    $http_code = curl_getinfo($proceso, CURLINFO_HTTP_CODE);
    if(curl_errno($proceso )){
        die('Error en la petición: ' . curl_error($proceso)."\n");  
    }
    curl_close($proceso);
    if ($http_code === 200) {
        echo "Producto eliminado exitosamente, respuesta (200).\n";
    } else {
        echo "Error al eliminar producto. $http_code\n";
    }
}

echo "respuesta del servidor:\n";
var_dump($respuestapet);



?>




