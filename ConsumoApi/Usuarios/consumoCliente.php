<?php
require_once __DIR__ . "/../Confi.php";


$consumo = file_get_contents($urlCliente);

if ($consumo === false) {
    die("Error al consumir el servicio");
}

echo "Seleccione una opción:\n";
echo "1. Ver GET\n";
echo "2. Insertar datos\n";
echo "3. Actualizar datos\n";
echo "4. Eliminar Datos\n";

$opcion = (int) readline("Ingrese una opción (1-4): ");

if ($opcion === 1) {

    $clientes = json_decode($consumo);

    $id = (int) readline("¿Qué usuario desea ver por ID? De 1 a 10: ");

    if ($id >= 1 && $id <= 10) {
        foreach ($clientes as $cliente) {
            $datos = explode(" | ", $cliente);
            $idCliente = (int) trim($datos[0]);
            if ($idCliente == $id) {
                echo $cliente . "\n";
            }
        }
    } else {
        echo ("ID fuera de rango.\n");
    }

} elseif ($opcion === 2) {
    echo "================ Metodo Post ======================\n";

    $respuesta = readline("¿Desea agregar algún usuario? Coloque s para sí, n para no: ");
    if ($respuesta === "s") {
        $nombre = readline("Ingrese su nombre: ");
        $correo = readline("Ingrese su correo: ");
        $contrasena = readline("Ingrese su contraseña: ");
        $documento = readline("Ingrese su número de documento: ");
        $telefono = readline("Ingrese su número telefónico: ");

        // Hash de la contraseña
        $contrasenaHash = password_hash($contrasena, PASSWORD_BCRYPT);

        $datos = array(
            "nombre"     => $nombre,
            "correo"     => $correo,
            "contrasena" => $contrasenaHash,
            "documento"  => $documento,
            "telefono"   => $telefono
        );

        $data_json = json_encode($datos);

        $proceso = curl_init($urlCliente);
        curl_setopt($proceso, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($proceso, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($proceso, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($proceso, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_json)
        ));

        $respuestapet = curl_exec($proceso);
        $http_code = curl_getinfo($proceso, CURLINFO_HTTP_CODE);

        if (curl_errno($proceso)) {
            die("Error en la petición Post: " . curl_error($proceso) . "\n");
        }
        curl_close($proceso);

        if ($http_code === 200) {
            echo ("Usuario guardado correctamente (200)\n");
        } else {
            echo ("Error en el servidor respuesta $http_code\n");
        }
    } else {
        echo ("Operación cancelada.\n");
    }

} elseif ($opcion === 3) {
    $id = readline("Ingrese el ID del cliente que desea actualizar: ");

    echo "================ Metodo Put ======================\n";

    $respuestaPut = readline("¿Desea actualizar este usuario? Coloque s para sí, n para no: ");
    if ($respuestaPut === "s") {
        $nombreNuevo  = readline("Nuevo nombre: ");
        $correoNuevo  = readline("Nuevo correo: ");
        $contrasenaNueva = readline("Nueva contraseña: ");

        // 🔒 Hash de la nueva contraseña
        $contrasenaHash = password_hash($contrasenaNueva, PASSWORD_BCRYPT);

        $datosPut = array(
            "nombre"     => $nombreNuevo,
            "correo"     => $correoNuevo,
            "contrasena" => $contrasenaHash
        );

        $data_json_put = json_encode($datosPut);

        $procesoPut = curl_init($urlCliente . "/$id");
        curl_setopt($procesoPut, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($procesoPut, CURLOPT_POSTFIELDS, $data_json_put);
        curl_setopt($procesoPut, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($procesoPut, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_json_put)
        ));

        $resPut = curl_exec($procesoPut);
        $http_code_put = curl_getinfo($procesoPut, CURLINFO_HTTP_CODE);

        if (curl_errno($procesoPut)) {
            die("Error en la petición Put: " . curl_error($procesoPut) . "\n");
        }
        curl_close($procesoPut);

        if ($http_code_put === 200) {
            echo ("Usuario actualizado correctamente (200)\n");
        } else {
            echo ("Error en el servidor respuesta $http_code_put\n");
        }
    }

} elseif ($opcion === 4) {
    $id = readline("Ingrese el ID del cliente que desea eliminar: ");

    echo "================ Metodo Delete ======================\n";

    $respuestaDel = readline("¿Desea eliminar este usuario? Coloque s para sí, n para no: ");
    if ($respuestaDel === "s") {
        $procesoDel = curl_init($urlCliente . "/$id");
        curl_setopt($procesoDel, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($procesoDel, CURLOPT_RETURNTRANSFER, true);

        $resDel = curl_exec($procesoDel);
        $http_code_del = curl_getinfo($procesoDel, CURLINFO_HTTP_CODE);

        if (curl_errno($procesoDel)) {
            die("Error en la petición Delete: " . curl_error($procesoDel) . "\n");
        }
        curl_close($procesoDel);

        if ($http_code_del === 200) {
            echo ("Usuario eliminado correctamente (200)\n");
        } else {
            echo ("Error en el servidor respuesta $http_code_del\n");
        }
    }
}

?>
