<?php
try {
    $dbc = new PDO("mysql:host=localhost; dbname=nxwaddql_bms19", "root", "");
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>
