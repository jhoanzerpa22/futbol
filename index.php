<?php
require 'vendor/autoload.php';
require 'conectar.php';

/*
Realizamos la conexion a la bd y cargamos la libreria Slim que nos proporciona facilidad de manejo de rutas y respuestas
*/
$app = new Slim\App();
$estrella = new Conectar();

/*
Url para mostrar estadisticas de historicos de estrellas
*/
$app->get('/stats', function ($request, $res, $next) use ($estrella) {
return json_encode($estrella->getHistorico());
});

/*
Url para verificar si el jugador es una estrella
*/
$app->post('/star', function ($request, $res, $next) use ($estrella) {
    $response = array();
    $allPost = $request->getParsedBody();
    //$allPost['history']= '{ "history": ["GPPEAG", "EGEAAG", "PAGEPG", "PGGGAE", "AEEEEG", "GPAPPA"] }';
    $history = json_decode($allPost['history'], TRUE);
    $star = isStar($history);
    if($star == false){
    $response["status"] = 403;
    $response["error"] = true;
    $response["message"] = 'Not Star ';
    $status=403;
    } else {
    $response["status"] = 200;
    $response["error"] = false;
    $response["message"] = "Is a Star.";
    $status=200;
    }
    //Guardamos el historico de la consulta
    $estrella->saveHistorico($allPost['history'],$star);
    return $res->withStatus($status)->withJson($response);
});

//Funcion que verifica si el jugador es una estrella
function isStar($history){
$i=0;
$h=0;
$v=0;
$o=0;
$historial = $history["history"];
//Recorremos el array por fila
foreach ($historial as $key => $value) {
    $data = str_split($value);
    $j=0;
    //Recorremos el array por columnas
    foreach ($data as $posicion => $valor) {
        //Verificamos horizontalmente
    if (isset($data[$j+1]) && isset($data[$j+2]) && isset($data[$j+3]) && $valor == $data[$j+1] && $valor == $data[$j+2] && $valor == $data[$j+3] && ($valor == "G" || $valor == "E")) {
        $h++;
    }
        //Verificamos Verticalmente y de forma oblicua
    if (isset($historial[$i+1]) && isset($historial[$i+2]) && isset($historial[$i+3])){
    $data2= str_split($historial[$i+1]);
    $data3= str_split($historial[$i+2]);
    $data4= str_split($historial[$i+3]);
        //Verificamos Verticalmente
    if (isset($data2[$j]) && isset($data3[$j]) && isset($data4[$j]) && $valor == $data2[$j] && $valor == $data3[$j] && $valor == $data4[$j] && ($valor == "G" || $valor == "E")) {
        $v++;
    }
        // De manera oblicua
    if (isset($data2[$j+1]) && isset($data3[$j+2]) && isset($data4[$j+3]) && $valor == $data2[$j+1] && $valor == $data3[$j+2] && $valor == $data4[$j+3] && ($valor == "G" || $valor == "E")) {
        $o++;
    }
    }
    $j++;
    }
    $i++;
}
    if ($h > 0 || $v > 0 || $o > 0) {
        return true;
    }else{
        return false;
    }
}

$app->run();

?>