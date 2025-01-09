<?
exit;
/*$url = "https://pbe.redvoiss.net:9025/api/DireccionACoordenada?direccion=parque de la hacienda 6159 peñalolen santiago chile";
$json = file_get_contents($url);
$obj = json_decode($json);
echo $obj->lat . ";" . $obj->lon;
 */

$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);  

$encode = rawurlencode("Padre mariano 82 providencia Santiago Chile");
echo $encode;
echo "<br>";
$response = file_get_contents("https://pbe.redvoiss.net:9025/api/DireccionACoordenada?direccion=" . $encode, false, stream_context_create($arrContextOptions));

$obj = json_decode($response);
echo $response;
echo "<br><br>";

echo $obj->lat . " ; " . $obj->lon;
?>
