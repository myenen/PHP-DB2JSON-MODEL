<?php
include_once ("models.php");
include_once ("global/global.config.php");
$user = models::get("user")->find("'subuser2'","name");

    var_dump($user);

?>
