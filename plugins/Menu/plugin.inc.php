<?php
require_once "menu.inc.php";

return array(
// Basic plugin info
// --------------------------------------
    "permission"    => 1000,
    "moduleName"    => "Menu",
    "args"          => array(
        "menu"      => function($value, $plugin) {
            foreach($value as $entry) {
                Menu::AddEntry($entry, $plugin);
            }
        }
    ),
    "events"        => array(
        "body"    => function() {
            Menu::onBody();
        }
    )
);
?>