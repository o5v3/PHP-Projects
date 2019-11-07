<html>
    <head>
        <title>PHP Challenge 1</title>
    </head>
    <body> 
        <?php 
        session_start();
        echo "<br><br><br>";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            foreach ($_POST as $property => $value) {
                $_SESSION[$property] = $value;
            };
        function main() {
            echo "Great! Thanks " . $_POST["firstName"] . " for responding to our survey<br>";
            echo "These are your details:<br>";
            echo "<ul>";
            foreach ($_POST as $property => $value) {
                if ($property == "insert") {continue;};
                echo "<li>$property : $value</li>";
            };
            echo "</ul>";
			echo "Do you want to add the student to the record?<br>";
			echo "<form method='POST' action='challenge.php'>";
			    echo "<input type='submit' value='Return'></input>";
			echo "</form>";
			echo "<form method='POST' action='results.php'>";
                echo "<input type='submit' name='insert' value='Add record'></button>";
            echo "</form>";
        }
        
            if (isset($_SESSION["insert"]) && $_SESSION["insert"] == "Add record") {
			    $database = fopen("students.txt", "w");
			    foreach ($_SESSION as $property => $value) {
                    if ($property == "insert") {continue;};
                    fwrite($database, "$property $value\r\n");            
			    };
                fclose($database);
                echo "Record successfully added to database!";
                $_SESSION["insert"] = null;
            } else {
                main();
            };
        }
        ?>
        <style>body {text-align: center;}</style>
    </body>
</html>
