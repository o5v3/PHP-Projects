<?php 
        
function showForm() {
    $html = "<input type='button' onclick='main.showFullData();' value='Show data'>";
    $html .= "<input type='button' onclick='main.showIndividualData();' value='Update data'>";
    $html .= "<input class='official-button' style='display: none;' type='button' onclick='main.data_num = this.number; main.deleteRecord();' value='Delete record'></input>";
    $html .= "<input class='official-button' style='display: none;' type='button' onclick='main.data_num = this.number; main.showUpdateForm();' value='Update'></input>";
    $html .= "<input class='official-button' style='display: none;' type='button' onclick='main.data_num = this.number; main.showIndividualData();' value='Show record'></input>";

    //Si quieres cambiar el archivo del codigo fuente, cambia el nombre aqui.
    $source = fopen("file.txt", "r");
    $html .= parser($source);
    fclose($source);
    return $html;
};

//Crea una tabla a partir de un array asociativo.
function createTable($data) {
    $table = "<table style='text-align: center; margin: auto; width: auto;'>";
    foreach ($data as $property => $value) {
        $table.= "<tr><td>$property</td><td>$value</td></tr>";
    };
    $table .= "</table><br>";
    return $table;
};

//Obtiene un archivo de input y lo analiza, cambiando las instrucciones por sus equivalentes
//y lo demas se deja igual. Retorna un string de HTML valido.
function parser($sourceFile) {
    //Instrucciones: "+" (Pregunta), "-" (Nombre de variable. Debe ir con una instruccion /)
    // "/" (Tipo de input. Debe seguir inmediatamente despues de la instruccion "-")
    // "{" (Titulo)
    $instructionSet = array("+", "-", "/", "{");
    $html = "<ul>";
        while (!(feof($sourceFile))) {
            $line = fgets($sourceFile);
            $line = str_split($line);
            //Si el char de inicio es una instruccion, ejecutarla.
            //Si no, añadir la linea tal y como esta. Esto permite añadir
            //HTML en el archivo fuente y este se  mostrara en la pagina.
            if (in_array($line[0], $instructionSet)){
                $html .= execute($line[0], substr(implode("", $line), 1)) . "<br>";
            } else {
                $html .= implode("", $line) . "<br>";
            };
        };
    $html .= "</ul>";
    $html .= "<input type='button' onclick='main.showSentForm();' value='Send form'>";
    return $html;
};

//Usada por el parser para decidir que instruccion ejecutar.
function execute($operator, $data) {
    if ($operator == "+") {
        return "<li>$data</li>";
    };
    if ($operator == "-") {
        $pieces = explode("/", $data);
        //El rtrim es necesario ya que el tipo siempre termina con algo de whitespace.
        return "<input class='userData' type='" . rtrim($pieces[1]) . "' name='$pieces[0]'>";
    };
    if ($operator == "{") {
        return "<h2>$data</h2>";
    }
};      

//Muestra tablas creadas a partir de la database.
function showFullData() {
    $database = fopen("database.txt", "r");
    $data = array();
    $html = "";
    while (!feof($database)) {
        //Cada linea equivale a un par Propiedad-Valor.
        $line = fgets($database);
        //Las propiedades y los valores estan separados por el signo "+".
        $words = explode("+", $line);
        //Si hay 2 o mas "+" en la linea, crear una tabla con los datos presentes y reiniciar la data.
        //Esto se usa para separar las tablas en fechas, ya que la fecha esta formateada:
        //Ejemplo: (Fecha)+(Hora)+(Separador).
        //
        //El separador puede ser cualquier char, lo que importa es que este presente.
        if (isset($words[2])) {
            $html .= createTable($data);
            $data = array();
        }
        if ($words[0] == "") {continue;};
        if (!isset($words[1])) {continue;};
        $data[$words[0]] = $words[1];
    };
    $html .= createTable($data);
    fclose($database);
    $html .= "<input type='button' onclick='main.showHeader();' value='Close'>";
    return $html;
};
//Se muestra una vez que se hace submit en la forma principal
function showSentForm($sentForm) {
    $html = "Great! Thanks for filling the form<br><br>";
    $html .= "These are your details:<br><br>";

    $html .= createTable($sentForm);
    $html .= "Do you want to add the data to the record?<br><br>";
    $html .= "<input type='button' onclick='main.showMainForm();' value='Return'></input>";
    $html .= "<input type='button' onclick='main.addRecord();' value='Add record'></input>";
    $html .= "<br>";
    $html .= "<input type='button' onclick='main.showFullData();' value='Show data'>";
    return $html;
};

//Muestra un entry a la vez. Permite actualizarlos, eliminarlos y pasar entre ellos
function viewData($data, $dataNum) {
    $html = "";
    if (!isset($data[$dataNum])) {
        if (isset($data[$dataNum - 1])) {
            $html = viewData($data, $dataNum -1);
            return $html;
        } else {
            $html = "<input type='button' onclick='main.showMainForm();' value='Return'></input>";
            return $html;
        }
    }
    $html .= createTable($data[$dataNum]);
    if ($dataNum != 1){
        $html .= "<input type='button' onclick='main.data_num-- ;main.showIndividualData();' value='Anterior'></input>";
    };
    if ($dataNum != count($data)) {
        $html .= "<input type='button' onclick='main.data_num++ ;main.showIndividualData();' value='Siguiente'></input>";
    };
    $html .= "<input type='button' onclick='main.showMainForm();' value='Return'></input>"; 
    $html .= "<input type='button' onclick='main.showUpdateForm();' value='Update'></input>";
    $html .= "<input type='button' onclick='main.deleteRecord();' value='Delete record'></input>";
    return $html;
};    

//Obtiene los datos desde el registro en la forma:
//  $data[numeroData][propiedad] = valor
//(Los datos son indexados desde 1, no desde 0)
function getData($filename) {
    $database = fopen($filename, "r");
    $data = array();
    $dataNum = 0;
    while (!feof($database)) {
        $line = fgets($database);
        $words = explode("+", $line);
        //Esto se ejecuta al principio tambien, lo que provoca que se indexe desde el 1.
        if (isset($words[2])) {
            $dataNum += 1;
        };
        if ($words[0] == "") {continue;};
        if (!isset($words[1])) {continue;};
        $data[$dataNum][$words[0]] = $words[1];
    };
    fclose($database);
    return $data;
};

//Crea una forma con los datos elegidos para proceder a modificarlos
function updateData($data, $dataNum) {
    $html = "";
    $exclusive = false;
    foreach ($data[$dataNum] as $property => $value) {
        if (!$exclusive) {
            $html .= "<li>$property</li>";
            $html .= "<li>$value</li>";  
            $exclusive = true;
            continue;                  
        };
        $html .= "<ul>";
        $html .= "<li>$property</li>";
        $type = "text";
        if (strpos($value, ":")) {
            $type = "time";
        } elseif (intval($value) !== 0) {
            $type = "number";
        };
        $html .= "<input class='userData' type='$type' name='$property' value='" . rtrim($value) . "'></input>";
        $html .= "</ul>";
    };
    $html .= "<input type='button' onclick='main.updateSingleRecord();' value='Add record'></input>";                    
    $html .= "<input type='button' onclick='main.showIndividualData();' value='Return'></input>";
    return $html;
};

//Registra los campos en el archivo con el formato:
//         Dia/Mes/Año+Hora:Minutos:Segundos+1
//                 Propiedad+Valor
//                 Propiedad+Valor
//                 Propiedad+Valor
//                       ...
//(El 1 puede ser intercambiado por cualquier cosa y funcionara igual)
//(Cualquier cosa excepto un +)
function addRecord($data) {
    $database = fopen("database.txt", "a");
    fwrite($database, date("d/m/Y+H:i:s") . "+1\r\n");
    foreach ($data as $property => $value) {
        fwrite($database, "$property+$value\r\n");            
    };
    fclose($database);
    $html = "Record successfully added to database!";
    $html .= "<input type='button' onclick='main.showMainForm();' value='Return'></input>";
    return $html;
};

//Actua de manera similar a addRecord con dos diferencias:
//1) Usa el modo "w", por lo que sobreescribe los contenidos del archivo.
//2) No obtiene la data del array $_SESSION, la obtiene de un array $data que se le da como argumento.
//Si quieres mantener los datos del archivo completo, pasa todo su contenido a un array, realiza los
//cambios que requieras y pasa el array como el primer argumento.
function updateRecord($data, $dataNum = null, $delete = false) {
    $database = fopen("database.txt", "w");
    foreach ($data as $record) {
        $keys = array_keys($record);
        $first = array_shift($keys);
        $element = array_shift($record);
        fwrite($database, "$first+$element+1\r\n");
        foreach ($record as $property => $value) {
            fwrite($database, "$property+$value\r\n");            
        };
    };
    fclose($database);
    if ($delete) {
        return viewData(getData("database.txt"), $dataNum);
    } else {
        $html = "Record successfully updated to database!";
        $html .= "<input type='button' onclick='main.showIndividualData();' value='Return'></input>";
        return $html;
    };
};

//Lee el archivo completo, borra el record y sobreescribe el archivo original.
function deleteRecord($dataNum) {
    $data = getData("database.txt");
    array_splice($data, $dataNum - 1, 1);
    return updateRecord($data, $dataNum, true);        
};

//Lee el archivo, modifica los datos de un record especifico y sobreescribe el archivo
//con los datos cambiados.
function updateSingleRecord($dataNum, $updateData) {
    $data = getData("database.txt");
    foreach ($updateData as $property => $value) {
        $data[$dataNum][$property] = $value;
    };
    return updateRecord($data);
};

function main() {
    $post = json_decode(file_get_contents("php://input"), true);

    switch ($post["request"]) {
        case "Main form":
            echo showForm();
            break;
        case "Show full data":
            echo showFullData();
            break;
        case "Show sent form":
            echo showSentForm($post["data"]);
            break;
        case "View database":
            echo viewData(getData("database.txt"), $post["dataNum"]);
            break;
        case "View single record":
            echo updateData(getData("database.txt"), $post["dataNum"]);
            break;
        case "Add record":
            echo addRecord($post["data"]);
            break;
        case "Delete record":
            echo deleteRecord($post["dataNum"]);
            break;
        case "Update single record":
            echo updateSingleRecord($post["dataNum"], $post["data"]);
            break;
    };
};

main();
?>