<?php 
define("CONSTANTE", "Hello World!");

$saludo = "Hola Mundo!";

echo "Los ingleses dicen " . CONSTANTE . "<br>Los demas decimos $saludo<br>";

$lista = array("100"=>"Cien", "50"=>"Cincuenta", "30"=>"Treinta");

echo $lista[50];

echo "<br>";

$test[1] = "Uno";
$test[3] = "Tres";

echo count($test) . "<br>";

foreach ($test as $key => $value) {
    echo "$key and $value <br>";
}

echo "Accediste a la pagina y le pusiste a la URL: " . $_SERVER["QUERY_STRING"];

$algo = $_POST["algo"] ?? $_GET["algo"] ?? "Nada";

echo "<br>La variable algo es: $algo";

$tiempo = strtotime("friday next week");
echo "<br>El tiempo especificado es: " . date("Y-m-d h:i:sa", $tiempo);

$nombre = substr($_SERVER["PHP_SELF"], 1);
$amount = readfile($nombre);

echo "<br>El nombre de este archivo es $nombre.<br>El archivo tiene " . $amount . "bytes.";

$file = fopen("filing.txt", "w");
fwrite($file, "1 nombre" . PHP_EOL. "Otro nombre");
fclose($file);
?>