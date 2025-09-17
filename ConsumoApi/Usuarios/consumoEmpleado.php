<?php
// Mostrar opciones de CRUD
echo "Seleccione una opción:\n";
echo "1. Ver GET\n";
echo "2. Insertar datos\n";
echo "3. Actualizar datos\n";
echo "4. Eliminar Datos\n";

$opcion = (int) readline("Ingrese una opción (1-4): ");

if ($opcion === 1) {
    $url = "http://localhost:8080/empleados";

    echo ("===============================Metodo Get==========================================\n");
    $consumo = file_get_contents($url);

    if ($consumo === false) {
        die("Error al consumir el servicio de empleados");
    }

    $empleados = json_decode($consumo);

    // Mostrar menú de opciones
    echo "Seleccione una opción:\n";
    echo "1. Ver solo los nombres de los empleados\n";
    echo "2. Ver nombres y fecha de contratación\n";
    echo "3. Ver toda la información de todos los empleados\n";
    echo "4. Ver cuántos empleados hay\n";

    $opcion = (int) readline("Ingrese una opción (1-4): ");

    switch ($opcion) {
        case 1:
            echo "=== Nombres de empleados ===\n";
            foreach ($empleados as $empleado) {
                $datos = explode(" | ", $empleado);
                echo "Nombre: " . trim($datos[1]) . "\n";
            }
            break;

        case 2:
            echo "=== Nombres y fecha de contratación ===\n";
            foreach ($empleados as $empleado) {
                $datos = explode(" | ", $empleado);
                echo "Nombre: " . trim($datos[1]) . " | Fecha: " . trim($datos[5]) . "\n";
            }
            break;

        case 3:
            echo "=== Toda la información ===\n";
            foreach ($empleados as $empleado) {
                $datos = explode(" | ", $empleado);
                echo "ID: " . trim($datos[0]) . " | ";
                echo "Nombre: " . trim($datos[1]) . " | ";
                echo "Cargo: " . trim($datos[2]) . " | ";
                echo "Correo: " . trim($datos[3]) . " | ";
                echo "Contraseña: " . trim($datos[4]) . " | ";
                echo "Fecha: " . trim($datos[5]) . " | ";
                echo "Estado: " . trim($datos[6]) . "\n";
            }
            break;

        case 4:
            $cantidad = count($empleados);
            echo "=== Cantidad de empleados ===\n";
            echo "Hay un total de $cantidad empleados registrados.\n";
            break;

        default:
            echo "Opción inválida.\n";
    }
}
elseif ($opcion === 2) {
    $url = "http://localhost:8080/empleados";

    echo ("\n==========================================Metodo Post================================================\n");

    $respuesta = readline("¿Desea agregar algún empleado? Coloque s para sí, n para no: ");

    if ($respuesta === "s") {
        $nombre = readline("Ingrese el nombre del empleado: ");
        $cargo = readline("Ingrese el cargo: ");
        $correo = readline("Ingrese el correo: ");
        $contrasena = readline("Ingrese la contraseña: ");

        $datos = array(
            "nombre"     => $nombre,
            "cargo"      => $cargo,
            "correo"     => $correo,
            "contrasena" => $contrasena
        );

        $data_json = json_encode($datos);

        $proceso = curl_init($url);
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
            die("Error en la petición Post: " . curl_errno($proceso) . "\n");
        }
        curl_close($proceso);

        if ($http_code === 200) {
            echo ("Empleado guardado correctamente (200)\n");
        } else {
            echo ("Error en el servidor, respuesta $http_code\n");
        }
    } else {
        echo ("Hasta un próximo vistazo.");
    }
}
elseif ($opcion === 3) {
    echo ("\n==========================================Metodo Put================================================\n");

    $id = readline("Ingrese el ID del empleado a actualizar: ");
    $url = "http://localhost:8080/empleados/$id";

    $respuesta = readline("¿Desea actualizar este empleado? Coloque s para sí, n para no: ");

    if ($respuesta === "s") {
        $nombre = readline("Ingrese el nombre del empleado: ");
        $cargo = readline("Ingrese el cargo: ");
        $correo = readline("Ingrese el correo: ");
        $estado = readline("Ingrese el nuevo estado: ");
        $contrasena = readline("Ingrese la contraseña: ");

        $datos = array(
            "nombre"     => $nombre,
            "cargo"      => $cargo,
            "correo"     => $correo,
            "estado"     => $estado,
            "contrasena" => $contrasena
        );

        $data_json = json_encode($datos);

        $proceso = curl_init($url);
        curl_setopt($proceso, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($proceso, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($proceso, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($proceso, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_json)
        ));

        $respuestapet = curl_exec($proceso);
        $http_code = curl_getinfo($proceso, CURLINFO_HTTP_CODE);

        if (curl_errno($proceso)) {
            die("Error en la petición Put: " . curl_errno($proceso) . "\n");
        }
        curl_close($proceso);

        if ($http_code === 200) {
            echo ("Empleado actualizado correctamente (200)\n");
        } else {
            echo ("Error en el servidor, respuesta $http_code\n");
        }
    } else {
        echo ("Hasta un próximo vistazo.");
    }
}
elseif ($opcion === 4) {
    echo ("\n==========================================Metodo Delete================================================\n");

    $id = readline("Ingrese el ID del empleado a eliminar: ");
    $url = "http://localhost:8080/empleados/$id";

    $respuesta = readline("¿Está seguro que desea eliminar este empleado? Coloque s para sí, n para no: ");

    if ($respuesta === "s") {
        $proceso = curl_init($url);
        curl_setopt($proceso, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($proceso, CURLOPT_RETURNTRANSFER, true);

        $respuestapet = curl_exec($proceso);
        $http_code = curl_getinfo($proceso, CURLINFO_HTTP_CODE);

        if (curl_errno($proceso)) {
            die("Error en la petición Delete: " . curl_errno($proceso) . "\n");
        }
        curl_close($proceso);

        if ($http_code === 200) {
            echo ("Empleado eliminado correctamente (200)\n");
        } else {
            echo ("Error en el servidor, respuesta $http_code\n");
        }
    } else {
        echo ("Operación cancelada.\n");
    }
}


?>
