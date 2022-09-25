<?php
require_once "PDO.inc.php";

return array(
    "permission"    => 1000,
    "moduleName"    => "PDO",
    "setup"         => function() {
        PDOManager::Setup();
    }
)

?>