<?php

class IDSearch {
    public $url = "http://www.cne.gob.ve/web/registro_electoral/ce.php";
    public $DOM;

    function __construct() {
        $this->DOM = new DOMDocument();
    }
    
    public function makeRequest($nacionalidad, $cedula) {
        $this->url .= "?nacionalidad=$nacionalidad&cedula=$cedula";
        $handler = curl_init($this->url);

        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        

        $response = curl_exec($handler);
        curl_close($handler);
        return $response;
    }

    public function getData($response) {
        $internalErrors = libxml_use_internal_errors(true);
        $this->DOM->loadHTML($response);
        libxml_use_internal_errors($internalErrors);
        $tables = $this->DOM->getElementsByTagName("table");
        foreach ($tables as $table) {
            if (($table->getAttribute("cellpadding") == "2") && ($table->getAttribute("width") == "530")) {
                $data = $table;
                break;
            };
        };
        return $data;
    }

    public function getIDData($IDTable) {
        $properties = ["Cédula", "Nombre", "Estado", "Municipio", "Parroquia", "Centro", "Dirección"];
        $rows = $IDTable->childNodes;
        foreach ($rows as $node) {
        echo $this->DOM->saveHTML($node);
        echo "Divisor<br>";
        };
        $values = [];
        $index = 0;
        foreach ($rows as $row) {
            var_dump($row->lastChild);
            $values[$index] = $row->lastChild->textContent;
            $index += 1;
        };
        return array_combine($properties, $values);
    }
};
$main = new IDSearch();
$data = $main->makeRequest("V", 12394907);
$table = $main->getData($data);
echo "<pre>";
print_r($main->getIDData($table));
echo "</pre>";
echo $main->DOM->saveHTML($table);


?>