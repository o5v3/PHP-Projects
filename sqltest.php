<?php
const DBPATH = "db/database.db";

class SQLiteConnection {
    private $pdo;

    public function connect() {
        if ($this->pdo == null) {
            $this->pdo = new PDO("sqlite:" . DBPATH);
        };
        return $this->pdo;
    }
};

class SQLiteDatabase {
    private $pdo;

    public function __construct() {
        $this->pdo = (new SQLiteConnection())->connect();
    }

    public function createTable($name, $fields) {
        $command = "CREATE TABLE $name (";
        $command .= "id INTEGER PRIMARY_KEY, ";
        foreach ($fields as $column) {
            if ($column == end($fields)) {
                $command .= "$column INTEGER)";                
            } else {
                $command .= "$column INTEGER, ";
            };
        };
        $this->pdo->exec($command);
    }

    public function getTableList() {
        $stmt = $this->pdo->query("SELECT name FROM sqlite_master WHERE type = 'table' ORDER BY name");
        $tables = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tables[] = $row["name"];
        };
        return $tables;
    }
};


function main() {
    try {
        $database = new SQLiteDatabase();
        echo "Connected to database!";
    } catch (PDOException $exception) {
        echo "Error 404, please try again :)";
        echo $exception;
    };
    $database->createTable("Test", ["number", "another", "num"]);
    $tables = $database->getTableList();
    print_r($tables);
};

main();
?> 
<style>body {text-align: center;} table, td {border: 1px dotted black;}</style>