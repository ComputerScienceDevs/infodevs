<?php
require_once "menu.inc.php";

return array(
// Basic plugin info
// --------------------------------------
    "permission"    => 1000,
    "moduleName"    => "Menu",
    "args"          => array(
        "menu"      => function($value, $plugin) {
            Menu::AddEntry($value, $plugin);
        }
    ),
    "events"        => array(
        "onBody"    => function() {
            Menu::onBody();
        }
    )
);
?>