<?php
require_once "menu.inc.php";

return array(
// Basic plugin info
// --------------------------------------
    "permission"    => 1000,
    "moduleName"    => "NNData",
    "args"          => array(
        "menuEntry" => function($value, $plugin) {
            Menu::AddEntry($value, $obj);
        },
        "menuPage"  => function($value, $plugin) {
            Menu::AddPageRef($value, $obj);
        }
    ),
);
?>