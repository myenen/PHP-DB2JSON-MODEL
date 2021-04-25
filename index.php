<?php
include_once ("models.php");
$user = models::get("user")->find("'subuser2'","name");

    var_dump($user);

?>
