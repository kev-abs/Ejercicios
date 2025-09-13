
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

echo ("================Metodo Post======================")."\n";
//Metodo Post

$respuesta = readline("Desea agregar algún usuario? s para si n para no: ");

if ($respuesta === "s"){
    $nombre = readline("Ingrese su nombre: ");
    $correo = readline("Ingrese su correo: ");
    $contrasena = readline("Ingrese su contraseña: ");
    $documento = readline("Ingrese su numero de documento: ");
    $telefono = readline("Ingrese su numero telefonico: ");

    $datos = array(
        "nombre"     => $nombre,
        "correo"     => $correo,
        "contrasena" => $contrasena,
        "documento"  => $documento,
        "telefono"   => $telefono
    );

    $data_json = json_encode($datos);

    $proceso = curl_init($url);

    curl_setopt($proceso, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($proceso, CURLOPT_POSTFIELDS, $data_json);
    curl_setopt($proceso, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($proceso,CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' .
        strlen($data_json)
    ));

    $respuestapet = curl_exec($proceso);

    $http_code = curl_getinfo($proceso, CURLINFO_HTTP_CODE);

    if (curl_errno($proceso)){
        die("Error en la petición Post". curl_errno($proceso)."\n");
    }
    curl_close($proceso);

    if($http_code === 200){
        echo ("Usuario guardado correctamente respuesta (200)");
    }
    else{
        echo ("Error en el servidor respuesta $http_code");
    }
}
else{
    echo ("Hasta un proximo vistazo.");
}
?>