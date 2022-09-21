<?php
require_once "ThemeManager.inc.php";

return array(
// Basic plugin info
// --------------------------------------
    "permission"    => 1000,
    "moduleName"    => basename(__DIR__),
    "events"        => array(
        "head"      => function() {
            ThemeManager\Manager::onHead();
        }
    )
);

?>