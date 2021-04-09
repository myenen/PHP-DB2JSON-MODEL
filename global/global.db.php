<?php

    $dbo = $settings["DBO"];
    $dbu = $settings["DBU"];
    $dbp = $settings["DBP"];

try {
        define("DB_DATABASE", "$dbo");
        define("DB_USER", "$dbu");
        define("DB_PASS", "$dbp");
        define("DB_SERVER", "localhost");

        $db = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_DATABASE.";charset=utf8", DB_USER, DB_PASS);
        $db->exec("SET NAMES 'utf8'; SET CHARSET 'utf8'");
} catch ( PDOException $e ){
    echo $e->getMessage();
     exit;
}
?>
